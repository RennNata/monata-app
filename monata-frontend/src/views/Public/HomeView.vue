<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Navbar from '../../components/Navbar.vue'
import Assalaam from '../../assets/logos/assalaam.png'
import Assalaam1 from '../../assets/logos/assalaam1.png'

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

const router = useRouter()
const images = [Assalaam, Assalaam1]
const currentImageIndex = ref(0)
let timer = null

const categories = ref([])
const isLoadingCategories = ref(false)

const startTimer = () => {
  timer = setInterval(() => {
    currentImageIndex.value = (currentImageIndex.value + 1) % images.length
  }, 5000)
}

const setImage = (index) => {
  currentImageIndex.value = index
  clearInterval(timer)
  startTimer()
}

// Fetch Kategori Asli dari Database
const fetchCategories = async () => {
  isLoadingCategories.value = true
  try {
    const res = await api.get('/categories')
    categories.value = res.data.data || res.data || []
  } catch (error) {
    console.error('Gagal mengambil data kategori:', error)
  } finally {
    isLoadingCategories.value = false
  }
}

// Navigasi ke katalog sambil ngirim ID Kategori
const navigateToCategory = (cat) => {
  router.push({ path: '/katalog', query: { category: cat.id } })
}

// Helper Ikon berdasarkan nama kategori
const getCategoryIcon = (nama) => {
  if (!nama) return '📦'
  const lower = nama.toLowerCase()
  if (lower.includes('seragam')) return '👔'
  if (lower.includes('buku') || lower.includes('tulis')) return '📚'
  if (lower.includes('atribut')) return '🏷️'
  if (lower.includes('sepatu') || lower.includes('kaos kaki')) return '🧦'
  return '📦'
}

onMounted(() => {
  startTimer()
  fetchCategories()
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>

<template>
  <div class="home-page">
    <Navbar />

    <!-- Hero Section -->
    <section 
      class="hero" 
      :style="{ backgroundImage: `linear-gradient(to bottom, rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.85)), url(${images[currentImageIndex]})` }"
    >
      <div class="hero-content">
        <span class="badge">SMK Assalaam Bandung</span>
        <h1>Monata</h1>
        <p>Beli perlengkapan sekolahmu dengan cepat & praktis</p>
        <router-link to="/katalog" class="btn-cta">
          Belanja Sekarang
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </router-link>
      </div>

      <!-- Hero Slide Dots -->
      <div class="slider-dots">
        <button 
          v-for="(_, index) in images" 
          :key="index"
          class="dot"
          :class="{ active: currentImageIndex === index }"
          @click="setImage(index)"
        ></button>
      </div>
    </section>

    <!-- Kategori Populer Dinamis dari Database -->
    <section class="section categories-section">
      <div class="section-header">
        <h2 class="section-title">Kategori Populer</h2>
        <p class="section-subtitle">Pilih kategori barang yang kamu butuhkan</p>
      </div>

      <!-- Loading State -->
      <div v-if="isLoadingCategories" class="loading-state">
        <p>Memuat kategori...</p>
      </div>

      <!-- Grid Kategori Asli -->
      <div v-else-if="categories.length > 0" class="grid-categories">
        <div 
          v-for="cat in categories" 
          :key="cat.id" 
          class="category-card" 
          @click="navigateToCategory(cat)"
        >
          <div class="icon-wrapper">{{ getCategoryIcon(cat.nama_kategori) }}</div>
          <h3>{{ cat.nama_kategori }}</h3>
          <span class="card-link">Lihat Produk →</span>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <p>Belum ada kategori yang tersedia.</p>
      </div>
    </section>

    <!-- Keunggulan Belanja di Monata -->
    <section class="section features-section">
      <div class="section-header">
        <h2 class="section-title">Kenapa Belanja di Monata?</h2>
        <p class="section-subtitle">Kemudahan layanan belanja online untuk seluruh siswa</p>
      </div>
      <div class="grid-features">
        <div class="feature-box">
          <div class="feature-icon">⚡</div>
          <h4>Cepat & Tanpa Antri</h4>
          <p>Pesan online dari kelas, tinggal ambil pesananmu di koperasi sekolah.</p>
        </div>
        <div class="feature-box">
          <div class="feature-icon">💵</div>
          <h4>Bayar Tunai</h4>
          <p>Pembayaran tetap santai menggunakan uang tunai saat pengambilan barang.</p>
        </div>
        <div class="feature-box">
          <div class="feature-icon">👌</div>
          <h4>Pasti Resmi</h4>
          <p>Semua produk atribut dan buku standar resmi dari SMK Assalaam.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.home-page {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-color: #0f172a;
  color: #f8fafc;
  min-height: 100vh;
}

/* Hero Style */
.hero {
  height: 70vh;
  min-height: 480px;
  background-size: cover;
  background-position: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  position: relative;
  padding: 0 20px;
  transition: background-image 0.8s ease-in-out;
}

.hero-content {
  max-width: 650px;
  z-index: 2;
}

.badge {
  background-color: rgba(56, 189, 248, 0.15);
  color: #38bdf8;
  border: 1px solid rgba(56, 189, 248, 0.3);
  padding: 6px 16px;
  border-radius: 99px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.5px;
  display: inline-block;
  margin-bottom: 16px;
}

.hero-content h1 {
  font-size: 3.5rem;
  margin: 0;
  font-weight: 800;
  letter-spacing: -1px;
  color: #f8fafc;
}

.hero-content p {
  font-size: 1.2rem;
  margin: 16px 0 32px;
  color: #94a3b8;
  font-weight: 400;
}

.btn-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 32px;
  background-color: #38bdf8;
  color: #0f172a;
  text-decoration: none;
  font-weight: 700;
  border-radius: 12px;
  transition: all 0.25s ease;
  box-shadow: 0 4px 20px rgba(56, 189, 248, 0.35);
}

.btn-cta:hover {
  background-color: #7dd3fc;
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(56, 189, 248, 0.5);
}

/* Dots Indicator */
.slider-dots {
  position: absolute;
  bottom: 24px;
  display: flex;
  gap: 8px;
  z-index: 2;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 99px;
  background-color: rgba(255, 255, 255, 0.3);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dot.active {
  width: 28px;
  background-color: #38bdf8;
}

/* Section Style */
.section {
  padding: 64px 24px;
  max-width: 1100px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 40px;
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  color: #f8fafc;
  margin: 0 0 8px 0;
}

.section-subtitle {
  color: #94a3b8;
  font-size: 0.95rem;
  margin: 0;
}

.loading-state, .empty-state {
  text-align: center;
  color: #94a3b8;
  padding: 20px;
}

/* Categories Grid */
.grid-categories {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 24px;
}

.category-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  padding: 32px 24px;
  border-radius: 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.category-card:hover {
  transform: translateY(-6px);
  border-color: #38bdf8;
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3), 0 0 12px rgba(56, 189, 248, 0.15);
}

.icon-wrapper {
  font-size: 2.8rem;
  margin-bottom: 12px;
}

.category-card h3 {
  margin: 0 0 8px 0;
  font-size: 1.25rem;
  color: #f8fafc;
}

.card-link {
  font-size: 13px;
  color: #38bdf8;
  font-weight: 600;
  opacity: 0.8;
  transition: opacity 0.2s ease;
}

.category-card:hover .card-link {
  opacity: 1;
}

/* Features Grid */
.grid-features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.feature-box {
  background-color: #1e293b;
  border: 1px solid #334155;
  padding: 28px;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.feature-box:hover {
  border-color: rgba(56, 189, 248, 0.4);
  transform: translateY(-4px);
}

.feature-icon {
  font-size: 2rem;
  margin-bottom: 16px;
}

.feature-box h4 {
  margin: 0 0 8px 0;
  font-size: 1.15rem;
  color: #f8fafc;
}

.feature-box p {
  margin: 0;
  color: #94a3b8;
  font-size: 0.95rem;
  line-height: 1.5;
}
</style>