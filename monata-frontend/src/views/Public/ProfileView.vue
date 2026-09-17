<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Navbar from '../../components/Navbar.vue'

const router = useRouter()

// Reactive States
const user = ref(null)
const orders = ref([])
const isLoading = ref(true)
const errorMessage = ref('')

// Function Ngambil Data User Login
const fetchProfile = async () => {
  try {
    isLoading.value = true
    const token = localStorage.getItem('token')

    // Kalo kagak ada token, langsung lepar ke login
    if (!token) {
      router.push('/login')
      return
    }

    // GANTI URL INI sesuai endpoint API Laravel/Express lu!
    const response = await axios.get('http://localhost:8000/api/user', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    // Masukin data dari backend (handle response struktur standar)
    const userData = response.data.user || response.data.data || response.data
    user.value = userData

    // Kalo backend lu ngirimin data orders sekalian di response user
    if (userData.orders || response.data.orders) {
      orders.value = userData.orders || response.data.orders
    }

  } catch (error) {
    console.error('Gagal ngambil profile:', error)
    // Kalo token invalid / kedaluwarsa (401), hapus token & tendang ke login
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      errorMessage.value = 'Gagal memuat data akun. Pastikan server backend lu nyala!'
    }
  } finally {
    isLoading.value = false
  }
}

// Function Logout
const handleLogout = () => {
  // Hapus token dari browser
  localStorage.removeItem('token')
  // Lempar balik ke login
  router.push('/login')
}

// Formatter Rupiah
const formatRupiah = (number) => {
  if (!number) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number)
}

onMounted(() => {
  fetchProfile()
})
</script>

<template>
  <div class="profile-page">
    <Navbar />

    <main class="container">
      <!-- 1. TAMPILAN LAGI LOADING DATA -->
      <div v-if="isLoading" class="loading-state">
        <div class="spinner"></div>
        <p>Lagi ngambil data akun lu...</p>
      </div>

      <!-- 2. TAMPILAN KALO ERROR SERVER -->
      <div v-else-if="errorMessage" class="error-state">
        <span>⚠️</span>
        <p>{{ errorMessage }}</p>
        <button class="btn-retry" @click="fetchProfile">Coba Lagi</button>
      </div>

      <!-- 3. TAMPILAN PROFIL KALO DATA USER UDAH ADA -->
      <div v-else-if="user" class="profile-grid">
        <!-- Sidebar Kartu Profil -->
        <div class="profile-card">
          <div class="avatar-wrapper">
            <span class="avatar-icon">{{ user.avatar || '👨‍🎓' }}</span>
          </div>
          <h2 class="user-name">{{ user.name }}</h2>
          <span class="user-role">{{ user.role || 'Siswa SMK Assalaam' }}</span>

          <div class="info-list">
            <div class="info-item">
              <span class="label">NISN</span>
              <span class="val">{{ user.nis_nip || '-' }}</span>
            </div>
            <!-- <div class="info-item">
              <span class="label">Kelas</span>
              <span class="val">{{ user.class || user.kelas || '-' }}</span>
            </div>
            <div class="info-item">
              <span class="label">Email</span>
              <span class="val">{{ user.email }}</span>
            </div>
            <div class="info-item">
              <span class="label">No. HP</span>
              <span class="val">{{ user.phone || user.no_hp || '-' }}</span>
            </div> -->
          </div>

          <button @click="handleLogout" class="btn-logout">Keluar Akun</button>
        </div>

        <!-- Detail Activity & Orders -->
        <div class="main-content">
          <!-- Banner Alert Info -->
          <div class="status-banner">
            <div class="banner-icon">💡</div>
            <div>
              <h4>Sistem Pengambilan Barang</h4>
              <p>Tunjukkan ID Pesanan ke petugas koperasi sekolah saat melakukan pembayaran tunai.</p>
            </div>
          </div>

          <!-- Section Riwayat Pesanan -->
          <div class="section-box">
            <h3 class="box-title">Riwayat Pesanan Saya</h3>

            <div v-if="orders.length > 0" class="order-list">
              <div v-for="order in orders" :key="order.id" class="order-card">
                <div class="order-header">
                  <div>
                    <span class="order-id">{{ order.id || order.order_number }}</span>
                    <span class="order-date">• {{ order.date || order.created_at }}</span>
                  </div>
                  <span 
                    class="status-badge"
                    :class="{ 
                      'ready': order.status === 'Siap Diambil',
                      'done': order.status === 'Selesai' 
                    }"
                  >
                    {{ order.status }}
                  </span>
                </div>

                <div class="order-body">
                  <p class="order-items">{{ order.items || order.item_details }}</p>
                  <div class="order-total">
                    <span>Total Pembayaran:</span>
                    <strong>{{ formatRupiah(order.total || order.total_price) }}</strong>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="empty-orders">
              <p>Belum ada riwayat pesanan.</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.profile-page {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-color: #0f172a;
  color: #f8fafc;
  min-height: 100vh;
}

.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 40px 24px;
}

/* Loading & Error States */
.loading-state, .error-state {
  text-align: center;
  padding: 80px 20px;
  background-color: #1e293b;
  border-radius: 16px;
  border: 1px solid #334155;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(56, 189, 248, 0.2);
  border-left-color: #38bdf8;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 16px auto;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-state span {
  font-size: 3rem;
  display: block;
  margin-bottom: 12px;
}

.btn-retry {
  background-color: #38bdf8;
  color: #0f172a;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 12px;
}

.profile-grid {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 32px;
}

@media (max-width: 850px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }
}

/* User Card */
.profile-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 16px;
  padding: 32px 24px;
  text-align: center;
  height: fit-content;
}

.avatar-wrapper {
  width: 90px;
  height: 90px;
  background-color: #0f172a;
  border: 2px solid #38bdf8;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px auto;
}

.avatar-icon {
  font-size: 3rem;
}

.user-name {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0 0 4px 0;
}

.user-role {
  color: #38bdf8;
  font-size: 13px;
  font-weight: 600;
  background-color: rgba(56, 189, 248, 0.1);
  padding: 4px 12px;
  border-radius: 99px;
  display: inline-block;
  margin-bottom: 24px;
}

.info-list {
  text-align: left;
  border-top: 1px solid #334155;
  padding-top: 16px;
  margin-bottom: 24px;
}

.info-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 14px;
}

.info-item .label {
  color: #94a3b8;
}

.info-item .val {
  color: #f8fafc;
  font-weight: 600;
}

.btn-logout {
  width: 100%;
  padding: 10px;
  background-color: transparent;
  color: #ef4444;
  border: 1px solid #ef4444;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-logout:hover {
  background-color: #ef4444;
  color: #ffffff;
}

/* Main Content Area */
.status-banner {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-left: 4px solid #38bdf8;
  padding: 16px 20px;
  border-radius: 12px;
  display: flex;
  gap: 16px;
  align-items: center;
  margin-bottom: 24px;
}

.banner-icon {
  font-size: 1.8rem;
}

.status-banner h4 {
  margin: 0 0 4px 0;
  font-size: 1rem;
  color: #f8fafc;
}

.status-banner p {
  margin: 0;
  font-size: 13px;
  color: #94a3b8;
}

.section-box {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 16px;
  padding: 24px;
}

.box-title {
  font-size: 1.2rem;
  margin: 0 0 20px 0;
  color: #f8fafc;
}

/* Order Cards */
.order-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.order-card {
  background-color: #0f172a;
  border: 1px solid #334155;
  border-radius: 12px;
  padding: 16px 20px;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  padding-bottom: 10px;
  border-bottom: 1px dashed #334155;
}

.order-id {
  font-weight: 700;
  color: #f8fafc;
}

.order-date {
  color: #94a3b8;
  font-size: 13px;
  margin-left: 6px;
}

.status-badge {
  font-size: 12px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 99px;
  background-color: rgba(148, 163, 184, 0.1);
  color: #94a3b8;
}

.status-badge.ready {
  background-color: rgba(234, 179, 8, 0.15);
  color: #facc15;
  border: 1px solid rgba(234, 179, 8, 0.3);
}

.status-badge.done {
  background-color: rgba(34, 197, 94, 0.15);
  color: #4ade80;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.order-body {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 16px;
}

.order-items {
  margin: 0;
  color: #94a3b8;
  font-size: 14px;
}

.order-total {
  text-align: right;
  font-size: 13px;
  color: #94a3b8;
}

.order-total strong {
  display: block;
  font-size: 1rem;
  color: #38bdf8;
}

.empty-orders {
  text-align: center;
  padding: 30px;
  color: #94a3b8;
  font-size: 14px;
}
</style>