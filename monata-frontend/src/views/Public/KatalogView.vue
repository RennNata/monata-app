<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from '../../components/Navbar.vue'

const route = useRoute()
const selectedCategory = ref('Semua')
const searchQuery = ref('')

// Dummy Data Produk SMK Assalaam
const products = ref([
  { id: 1, name: 'Seragam OSIS SMK (Lengan Pendek)', category: 'Seragam', price: 95000, icon: '👔', stock: 15 },
  { id: 2, name: 'Seragam Batik Assalaam', category: 'Seragam', price: 110000, icon: '👕', stock: 8 },
  { id: 3, name: 'Celana / Rok Abu-Abu', category: 'Seragam', price: 100000, icon: '👖', stock: 20 },
  { id: 4, name: 'Buku Tulis A5 (Isi 10 Pack)', category: 'Buku & Alat Tulis', price: 45000, icon: '📚', stock: 50 },
  { id: 5, name: 'Pulpen Gel Black 0.5mm (Box)', category: 'Buku & Alat Tulis', price: 25000, icon: '✏️', stock: 30 },
  { id: 6, name: 'Buku Gambar A3', category: 'Buku & Alat Tulis', price: 15000, icon: '🎨', stock: 12 },
  { id: 7, name: 'Dasi SMK Assalaam', category: 'Atribut Sekolah', price: 20000, icon: '👔', stock: 40 },
  { id: 8, name: 'Sabuk Logo SMK Assalaam', category: 'Atribut Sekolah', price: 25000, icon: '🏷️', stock: 25 },
  { id: 9, name: 'Kaos Kaki Hitam (3 Pasang)', category: 'Atribut Sekolah', price: 30000, icon: '🧦', stock: 18 }
])

const categories = ['Semua', 'Seragam', 'Buku & Alat Tulis', 'Atribut Sekolah']

// Sync filter dari Query URL (dari Navbar / Home)
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
})

// Filter logic
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchCategory = selectedCategory.value === 'Semua' || product.category === selectedCategory.value
    const matchSearch = product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchCategory && matchSearch
  })
})

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number)
}
</script>

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
        <div class="category-pills">
          <button 
            v-for="cat in categories" 
            :key="cat"
            class="pill-btn"
            :class="{ active: selectedCategory === cat }"
            @click="selectedCategory = cat"
          >
            {{ cat }}
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

      <!-- Grid Produk -->
      <div v-if="filteredProducts.length > 0" class="product-grid">
        <div v-for="item in filteredProducts" :key="item.id" class="product-card">
          <div class="product-image">
            <span class="product-icon">{{ item.icon }}</span>
            <span class="category-tag">{{ item.category }}</span>
          </div>
          <div class="product-info">
            <h3 class="product-title">{{ item.name }}</h3>
            <div class="product-meta">
              <span class="price">{{ formatRupiah(item.price) }}</span>
              <span class="stock">Stok: {{ item.stock }}</span>
            </div>
            <button class="btn-buy">
              + Tambah Pesanan
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
  height: 160px;
  background-color: #0f172a;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  border-bottom: 1px solid #334155;
}

.product-icon {
  font-size: 4rem;
}

.category-tag {
  position: absolute;
  top: 12px;
  left: 12px;
  background-color: rgba(30, 41, 59, 0.85);
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

.btn-buy:hover {
  background-color: #38bdf8;
  color: #0f172a;
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