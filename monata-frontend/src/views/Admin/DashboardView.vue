<template>
  <div class="dashboard-container">
    <Sidebar />

    <main class="main-content">
      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h2>Dashboard</h2>
          <p>Ringkasan statistik toko dan aktivitas transaksi kamu.</p>
        </div>
      </div>

      <!-- Stat Cards Summary -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon bg-blue">📦</div>
          <div class="stat-info">
            <p class="stat-label">Total Produk</p>
            <h3 class="stat-value">{{ products.length }}</h3>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-purple">🏷️</div>
          <div class="stat-info">
            <p class="stat-label">Total Kategori</p>
            <h3 class="stat-value">{{ categories.length }}</h3>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-green">💳</div>
          <div class="stat-info">
            <p class="stat-label">Total Transaksi</p>
            <h3 class="stat-value">{{ transactions.length }}</h3>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-amber">👥</div>
          <div class="stat-info">
            <p class="stat-label">Total Admin</p>
            <h3 class="stat-value">{{ admins.length }}</h3>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="dashboard-grid">
        <!-- Tabel Transaksi Terbaru -->
        <div class="table-card">
          <div class="card-header">
            <div>
              <h3>Transaksi Terbaru</h3>
              <p>Daftar transaksi masuk belakangan ini.</p>
            </div>
            <router-link to="/dashboard/transaksi" class="link-more">Lihat Semua →</router-link>
          </div>

          <table class="custom-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="4" class="empty-state">Lagi ngambil data dashboard... Sabar ya!</td>
              </tr>
              <tr v-else-if="recentTransactions.length === 0">
                <td colspan="4" class="empty-state">Belum ada transaksi tercatat.</td>
              </tr>
              <tr v-else v-for="trx in recentTransactions" :key="trx.id">
                <td class="font-bold">#{{ trx.id }}</td>
                <td>{{ formatDate(trx.created_at) }}</td>
                <td>Rp {{ Number(trx.total_harga || trx.total || 0).toLocaleString('id-ID') }}</td>
                <td>
                  <span class="badge" :class="getStatusClass(trx.status)">
                    {{ trx.status || 'Selesai' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Side Widgets -->
        <div class="side-widgets">
          <!-- Stok Menipis -->
          <div class="widget-card">
            <div class="card-header">
              <h3>⚠️ Stok Menipis</h3>
              <router-link to="/dashboard/products" class="link-more">Atur</router-link>
            </div>
            <div class="widget-body">
              <div v-if="isLoading" class="empty-text">Memuat stok...</div>
              <div v-else-if="lowStockProducts.length === 0" class="empty-text text-green">
                Semua stok produk aman! 🎉
              </div>
              <div v-else class="stock-list">
                <div v-for="prod in lowStockProducts" :key="prod.id" class="stock-item">
                  <span class="prod-name">{{ prod.nama_produk }}</span>
                  <span class="stock-badge">Sisa {{ prod.stok }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="widget-card">
            <div class="card-header">
              <h3>⚡ Akses Cepat</h3>
            </div>
            <div class="quick-actions">
              <router-link to="/dashboard/products" class="btn-action">
                <span>+</span> Kelola Produk
              </router-link>
              <router-link to="/dashboard/categories" class="btn-action">
                <span>+</span> Kelola Kategori
              </router-link>
              <router-link to="/dashboard/admins" class="btn-action">
                <span>+</span> Kelola Admin
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Sidebar from '../../components/Sidebar.vue'

const api = axios.create({
  baseURL: 'https://mayra-glaucous-cloudlessly.ngrok-free.dev/api',
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

const products = ref([])
const categories = ref([])
const transactions = ref([])
const admins = ref([])
const isLoading = ref(false)

const fetchDashboardData = async () => {
  isLoading.value = true
  try {
    const [resProd, resCat, resTrx, resAdmin] = await Promise.allSettled([
      api.get('/dashboard/products'),
      api.get('/dashboard/categories'),
      api.get('/dashboard/transactions'),
      api.get('/dashboard/admins')
    ])

    if (resProd.status === 'fulfilled') products.value = resProd.value.data.data || resProd.value.data || []
    if (resCat.status === 'fulfilled') categories.value = resCat.value.data.data || resCat.value.data || []
    if (resTrx.status === 'fulfilled') transactions.value = resTrx.value.data.data || resTrx.value.data || []
    if (resAdmin.status === 'fulfilled') admins.value = resAdmin.value.data.data || resAdmin.value.data || []
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

const recentTransactions = computed(() => {
  return [...transactions.value].reverse().slice(0, 5)
})

const lowStockProducts = computed(() => {
  return products.value.filter(p => Number(p.stok) <= 5).slice(0, 4)
})

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

// Fungsi penyesuaian warna badge berdasarkan status
const getStatusClass = (status) => {
  if (!status) return 'badge-success'
  const s = status.toLowerCase()
  if (s.includes('batal') || s.includes('cancel') || s.includes('failed')) return 'badge-danger'
  if (s.includes('pending') || s.includes('menunggu')) return 'badge-warning'
  if (s.includes('proses') || s.includes('process')) return 'badge-info'
  return 'badge-success'
}

onMounted(() => {
  fetchDashboardData()
})
</script>

<style scoped>
.dashboard-container {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  color: #0f172a;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.main-content {
  flex: 1;
  padding: 32px;
  overflow-y: auto;
  margin-left: 260px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.page-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 4px 0;
  color: #0f172a;
}

.page-header p {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
}

/* Stats Cards Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.stat-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.bg-blue { background-color: rgba(59, 130, 246, 0.15); }
.bg-purple { background-color: rgba(168, 85, 247, 0.15); }
.bg-green { background-color: rgba(16, 185, 129, 0.15); }
.bg-amber { background-color: rgba(245, 158, 11, 0.15); }

.stat-info .stat-label {
  margin: 0 0 4px 0;
  font-size: 0.8125rem;
  color: #94a3b8;
}

.stat-info .stat-value {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #f8fafc;
}

/* Dashboard Content Grid */
.dashboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}

@media (max-width: 1024px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

.table-card {
  background-color: #1e293b;
  border-radius: 12px;
  border: 1px solid #334155;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
}

.card-header {
  padding: 20px;
  border-bottom: 1px solid #334155;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h3 {
  margin: 0 0 4px 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #ffffff;
}

.card-header p {
  margin: 0;
  font-size: 0.8125rem;
  color: #64748b;
}

.link-more {
  color: #38bdf8;
  font-size: 0.875rem;
  text-decoration: none;
  font-weight: 600;
}

.link-more:hover {
  text-decoration: underline;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.875rem;
}

.custom-table th {
  background-color: #0f172a;
  padding: 12px 16px;
  color: #94a3b8;
  font-weight: 600;
  border-bottom: 1px solid #334155;
  text-align: center;
}

.custom-table td {
  padding: 14px 16px;
  border-bottom: 1px solid #334155;
  color: #cbd5e1;
  text-align: center;
}

.custom-table tr:hover {
  background-color: #243248;
}

.font-bold {
  font-weight: 600;
  color: #f8fafc;
}

.empty-state {
  text-align: center;
  padding: 28px;
  color: #64748b;
}

/* Status Badges */
.badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
  text-transform: capitalize;
}

.badge-success {
  background-color: rgba(16, 185, 129, 0.15);
  color: #34d399;
  border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-danger {
  background-color: rgba(239, 68, 68, 0.15);
  color: #f87171;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.badge-warning {
  background-color: rgba(245, 158, 11, 0.15);
  color: #fbbf24;
  border: 1px solid rgba(245, 158, 11, 0.3);
}

.badge-info {
  background-color: rgba(59, 130, 246, 0.15);
  color: #60a5fa;
  border: 1px solid rgba(59, 130, 246, 0.3);
}

/* Side Widgets */
.side-widgets {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.widget-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
}

.widget-body {
  padding: 16px 20px;
}

.empty-text {
  font-size: 0.875rem;
  color: #64748b;
  text-align: center;
  padding: 12px 0;
}

.text-green {
  color: #34d399;
}

.stock-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.stock-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background-color: #0f172a;
  border-radius: 8px;
  border: 1px solid #334155;
}

.prod-name {
  font-size: 0.875rem;
  color: #e2e8f0;
  font-weight: 500;
}

.stock-badge {
  font-size: 0.75rem;
  font-weight: 600;
  background-color: rgba(239, 68, 68, 0.2);
  color: #f87171;
  padding: 2px 8px;
  border-radius: 4px;
}

.quick-actions {
  padding: 16px 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.btn-action {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #e2e8f0;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-action:hover {
  background-color: #243248;
  border-color: #3b82f6;
  color: #38bdf8;
}
</style>