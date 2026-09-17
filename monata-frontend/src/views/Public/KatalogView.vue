<template>
  <div class="katalog-page">
    <Navbar />

    <main class="container">
      <!-- Header Section -->
      <div class="catalog-header">
        <h1>Katalog Produk</h1>
        <p>Temukan perlengkapan sekolah lengkap khusus siswa SMK Assalaam</p>
      </div>

      <!-- Filter Bar -->
      <div class="filter-container">
        <!-- Dynamic Category Pills -->
        <div class="category-pills">
          <button 
            class="pill-btn"
            :class="{ active: selectedCategory === 'Semua' }"
            @click="selectedCategory = 'Semua'"
          >
            Semua
          </button>
          <button 
            v-for="cat in categories" 
            :key="cat.id"
            class="pill-btn"
            :class="{ active: selectedCategory == cat.id || selectedCategory === cat.nama_kategori }"
            @click="selectedCategory = cat.id"
          >
            {{ cat.nama_kategori }}
          </button>
        </div>

        <div class="search-box-inline">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Cari barang..." 
            class="filter-input"
          />
          <button v-if="searchQuery" class="clear-btn" @click="searchQuery = ''">✕</button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="loading-state">
        <p>Sedang memuat katalog produk...</p>
      </div>

      <!-- Grid Produk Asli dari Database -->
      <div v-else-if="filteredProducts.length > 0" class="product-grid">
        <div v-for="item in filteredProducts" :key="item.id" class="product-card">
          <div class="product-image">
            <img 
              v-if="item.foto" 
              :src="getFotoUrl(item.foto)" 
              :alt="item.nama_produk" 
              class="real-product-img" 
            />
            <span v-else class="product-icon">📦</span>
            <span class="category-tag">{{ getCategoryName(item) }}</span>
          </div>
          <div class="product-info">
            <h3 class="product-title">{{ item.nama_produk }}</h3>
            <div class="product-meta">
              <span class="price">{{ formatRupiah(item.harga) }}</span>
              <span class="stock">Stok: {{ item.stok }}</span>
            </div>
            <button class="btn-buy" :disabled="item.stok <= 0" @click="handleOrder(item)">
              {{ item.stok > 0 ? '+ Tambah Pesanan' : 'Stok Habis' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <span class="empty-icon">🔍</span>
        <h3>Barang gak ketemu!</h3>
        <p>Coba cari kata kunci lain atau ganti kategori filter di atas.</p>
        <button class="btn-reset" @click="selectedCategory = 'Semua'; searchQuery = ''">Reset Filter</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router' // <-- DIBENERIN: 'vue-router' bukan 'vue-route'
import axios from 'axios'
import Navbar from '../../components/Navbar.vue'

const NGROK_URL = 'https://mayra-glaucous-cloudlessly.ngrok-free.dev'

const api = axios.create({
  baseURL: `${NGROK_URL}/api`,
  headers: {
    'Accept': 'application/json',
    'ngrok-skip-browser-warning': 'true'
  }
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

const route = useRoute()
const router = useRouter()
const selectedCategory = ref('Semua')
const searchQuery = ref('')
const products = ref([])
const categories = ref([])
const isLoading = ref(false)

// Helper foto Ngrok
const getFotoUrl = (fotoPath) => {
  if (!fotoPath) return ''
  if (typeof fotoPath !== 'string') return ''
  if (fotoPath.startsWith('http://') || fotoPath.startsWith('https://')) return fotoPath
  
  const cleanPath = fotoPath.replace(/^\/?(public\/|storage\/)?/, '')
  return `${NGROK_URL}/storage/${cleanPath}?ngrok-skip-browser-warning=true`
}

// Fetch Data Asli dari Backend Laravel
const fetchData = async () => {
  isLoading.value = true
  try {
    const [resProducts, resCategories] = await Promise.all([
      api.get('/products'),
      api.get('/categories')
    ])
    
    products.value = resProducts.data.data || resProducts.data || []
    categories.value = resCategories.data.data || resCategories.data || []
  } catch (error) {
    console.error('Gagal mengambil data katalog:', error)
  } finally {
    isLoading.value = false
  }
}

// Helper Nama Kategori
const getCategoryName = (product) => {
  if (product.category?.nama_kategori) return product.category.nama_kategori
  const found = categories.value.find(c => c.id === product.id_kategori)
  return found ? found.nama_kategori : 'Umum'
}

// Sync query URL
const syncQueryParams = () => {
  if (route.query.search) {
    searchQuery.value = route.query.search
  }
  if (route.query.category) {
    selectedCategory.value = route.query.category
  }
}

watch(() => route.query, () => {
  syncQueryParams()
}, { deep: true })

onMounted(() => {
  syncQueryParams()
  fetchData()
})

// Filter logic untuk data DB
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    // Check Kategori ID atau Kategori Name
    const matchCategory = selectedCategory.value === 'Semua' || 
                          product.id_kategori == selectedCategory.value || 
                          getCategoryName(product) === selectedCategory.value

    // Check Nama Produk
    const productName = product.nama_produk || product.name || ''
    const matchSearch = productName.toLowerCase().includes(searchQuery.value.toLowerCase())

    return matchCategory && matchSearch
  })
})

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number || 0)
}

// DIBENERIN: Data lemparan disesuaiin lengkap buat BuatPesananView
const handleOrder = (product) => {
  if (product.stok <= 0) {
    alert('Stok habis, gak bisa bikin pesanan!')
    return
  }
  router.push({
    path: '/buat-pesanan',
    query: {
      productId: product.id,
      productName: product.nama_produk,
      productPrice: product.harga,
      productIcon: product.foto ? getFotoUrl(product.foto) : '📦',
      productStock: product.stok
    }
  })
}
</script>

<style scoped>
.katalog-page {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-color: #0f172a;
  color: #f8fafc;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 24px;
}

.catalog-header {
  margin-bottom: 32px;
}

.catalog-header h1 {
  font-size: 2.2rem;
  font-weight: 800;
  margin: 0 0 8px 0;
}

.catalog-header p {
  color: #94a3b8;
  margin: 0;
}

/* Filter Section */
.filter-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 32px;
  background-color: #1e293b;
  padding: 16px 20px;
  border-radius: 16px;
  border: 1px solid #334155;
}

.category-pills {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.pill-btn {
  background: #0f172a;
  color: #94a3b8;
  border: 1px solid #334155;
  padding: 8px 18px;
  border-radius: 99px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pill-btn:hover {
  color: #f8fafc;
  border-color: #38bdf8;
}

.pill-btn.active {
  background-color: #38bdf8;
  color: #0f172a;
  border-color: #38bdf8;
}

.search-box-inline {
  position: relative;
  min-width: 240px;
}

.filter-input {
  width: 100%;
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #f8fafc;
  padding: 8px 36px 8px 14px;
  border-radius: 8px;
  outline: none;
  font-size: 14px;
}

.filter-input:focus {
  border-color: #38bdf8;
}

.clear-btn {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
}

/* Loading State */
.loading-state {
  text-align: center;
  padding: 60px;
  color: #94a3b8;
}

/* Product Grid */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 24px;
}

.product-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-4px);
  border-color: #38bdf8;
  box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}

.product-image {
  height: 180px;
  background-color: #0f172a;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  border-bottom: 1px solid #334155;
  overflow: hidden;
}

.real-product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-icon {
  font-size: 4rem;
}

.category-tag {
  position: absolute;
  top: 12px;
  left: 12px;
  background-color: rgba(15, 23, 42, 0.85);
  border: 1px solid #334155;
  color: #38bdf8;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 99px;
  backdrop-filter: blur(4px);
}

.product-info {
  padding: 20px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.product-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 12px 0;
  color: #f8fafc;
  line-height: 1.4;
}

.product-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  margin-bottom: 16px;
}

.price {
  font-size: 1.2rem;
  font-weight: 700;
  color: #38bdf8;
}

.stock {
  font-size: 12px;
  color: #94a3b8;
}

.btn-buy {
  width: 100%;
  padding: 10px;
  background-color: rgba(56, 189, 248, 0.1);
  color: #38bdf8;
  border: 1px solid rgba(56, 189, 248, 0.3);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-buy:hover:not(:disabled) {
  background-color: #38bdf8;
  color: #0f172a;
}

.btn-buy:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  border-color: #475569;
  color: #94a3b8;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  background-color: #1e293b;
  border-radius: 16px;
  border: 1px dashed #334155;
}

.empty-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 12px;
}

.empty-state h3 {
  margin: 0 0 8px 0;
  color: #f8fafc;
}

.empty-state p {
  color: #94a3b8;
  margin: 0 0 20px 0;
}

.btn-reset {
  background-color: #38bdf8;
  color: #0f172a;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
}
</style>