<template>
  <div class="dashboard-container">
    <Sidebar />

    <main class="main-content">
      <div class="page-header">
        <div>
          <h2>Kelola Transaksi</h2>
          <p>Atur, pantau status, dan lihat rincian belanja transaksi toko Monata.</p>
        </div>
        <button class="btn-primary" @click="openModal('add')">
          <span>+</span> Tambah Transaksi
        </button>
      </div>

      <div class="table-card">
        <table class="custom-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Transaksi</th>
              <th>Pelanggan (User)</th>
              <th>Total Harga</th>
              <th>Status Pembayaran</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loading State -->
            <tr v-if="isLoading">
              <td colspan="7" class="empty-state">Lagi ngambil data transaksi...</td>
            </tr>

            <!-- Empty State -->
            <tr v-else-if="transactions.length === 0">
              <td colspan="7" class="empty-state">Belum ada transaksi masuk.</td>
            </tr>

            <!-- Data List -->
            <tr v-else v-for="(item, index) in transactions" :key="item.id || index">
              <td>{{ index + 1 }}</td>
              <td class="font-mono text-blue">{{ item.kode_transaksi || '-' }}</td>
              <td class="font-bold">
                {{ item.user?.name || item.user?.nama || (item.id_user ? 'User #' + item.id_user : 'Pelanggan Umum') }}
              </td>
              <td class="text-emerald font-bold">Rp {{ Number(item.total_harga || 0).toLocaleString('id-ID') }}</td>
              <td>
                <span class="badge-type">{{ item.status_pembayaran || 'belum_bayar' }}</span>
              </td>
              <td>
                <span 
                  class="badge-status"
                  :class="{
                    'status-pending': item.status === 'pending' || item.status === 'pending',
                    'status-success': item.status === 'success' || item.status === 'selesai',
                    'status-cancel': item.status === 'cancel' || item.status === 'dibatalkan'
                  }"
                >
                  {{ item.status || 'pending' }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="btn-detail" @click="openDetailModal(item)">Detail</button>
                  <button class="btn-edit" @click="openModal('edit', item)">Edit</button>
                  <!-- <button class="btn-delete" @click="deleteTransaction(item.id)">Hapus</button> -->
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- MODAL 1: DETAIL TRANSAKSI & RINCIAN BARANG -->
      <div v-if="isDetailOpen" class="modal-overlay" @click.self="closeDetailModal">
        <div class="modal-box modal-lg">
          <div class="modal-header-flex">
            <h3>Detail Transaksi <span class="text-blue font-mono">#{{ detailItem?.kode_transaksi }}</span></h3>
            <button class="btn-close" @click="closeDetailModal">&times;</button>
          </div>

          <!-- Metadata Ringkas -->
          <div class="meta-grid">
            <div class="meta-item">
              <span class="meta-label">Pelanggan</span>
              <span class="meta-val">{{ detailItem?.user?.name || detailItem?.user?.nama || 'User #' + detailItem?.id_user }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Status Pembayaran</span>
              <span class="meta-val uppercase">{{ detailItem?.status_pembayaran || '-' }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Status</span>
              <span class="meta-val text-amber uppercase font-bold">{{ detailItem?.status || '-' }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Total Pembayaran</span>
              <span class="meta-val text-emerald font-bold">Rp {{ Number(detailItem?.total_harga || 0).toLocaleString('id-ID') }}</span>
            </div>
          </div>

          <!-- Tabel Rincian Barang -->
          <h4 class="sub-title">Rincian Barang yang Dibeli</h4>
          <div class="sub-table-card">
            <table class="custom-table sub-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Produk</th>
                  <th>Harga Satuan</th>
                  <th>Jumlah</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="isFetchingDetail">
                  <td colspan="5" class="empty-state">Mengambil rincian produk...</td>
                </tr>
                <tr v-else-if="getDetails(detailItem).length === 0">
                  <td colspan="5" class="empty-state">Tidak ada item rincian transaksi.</td>
                </tr>
                <tr v-else v-for="(sub, idx) in getDetails(detailItem)" :key="sub.id || idx">
                  <td>{{ idx + 1 }}</td>
                  <td class="font-bold">{{ sub.product?.nama_produk || sub.produk?.nama_produk || sub.nama_produk || 'Produk #' + (sub.id_produk || sub.product_id) }}</td>
                  <td>Rp {{ Number(sub.harga_satuan || sub.harga || 0).toLocaleString('id-ID') }}</td>
                  <td class="font-bold text-blue">{{ sub.jumlah || sub.qty || 1 }}</td>
                  <td class="text-emerald font-bold">
                    Rp {{ Number(sub.subtotal || ((sub.harga_satuan || sub.harga || 0) * (sub.jumlah || sub.qty || 1))).toLocaleString('id-ID') }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="modal-footer-flex">
            <!-- <div class="quick-status">
              <label>Ubah Status:</label>
              <select 
                :value="detailItem?.status" 
                @change="quickUpdateStatus(detailItem, $event.target.value)"
              >
                <option value="pending">Pending</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Batal</option>
              </select>
            </div> -->
            <button class="btn-cancel" @click="closeDetailModal">Tutup</button>
          </div>
        </div>
      </div>

      <!-- MODAL 2: FORM TAMBAH / EDIT TRANSAKSI -->
      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-box">
          <h3>{{ isEditMode ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}</h3>
          <form @submit.prevent="saveTransaction">
            <div class="form-group">
              <label>Kode Transaksi</label>
              <input 
                v-model="form.kode_transaksi" 
                type="text" 
                placeholder="Contoh: TRX-20260915-001" 
                required 
              />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>ID User (Pelanggan)</label>
                <input 
                  v-model="form.id_user" 
                  type="number" 
                  placeholder="Contoh: 2" 
                  required 
                />
              </div>
            </div>

            <div class="form-group">
              <label>Total Harga (Rp)</label>
              <input 
                v-model="form.total_harga" 
                type="number" 
                placeholder="Contoh: 150000" 
                required 
              />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Status Pembayaran</label>
                <select v-model="form.status_pembayaran" required>
                  <option value="belum_bayar">Belum Bayar</option>
                  <option value="sudah_bayar">Sudah Bayar</option>
                </select>
              </div>
              <div class="form-group">
                <label>Status Transaksi</label>
                <select v-model="form.status" required>
                  <option value="pending">Pending</option>
                  <option value="selesai">Selesai</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>
            </div>

            <div class="modal-actions">
              <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
              <button type="submit" class="btn-primary" :disabled="isSaving">
                {{ isSaving ? 'Ngesave...' : (isEditMode ? 'Simpan Perubahan' : 'Tambah Transaksi') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
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

const transactions = ref([])
const isLoading = ref(false)
const isFetchingDetail = ref(false)
const isSaving = ref(false)
const isModalOpen = ref(false)
const isDetailOpen = ref(false)
const isEditMode = ref(false)
const selectedId = ref(null)
const detailItem = ref(null)

const form = reactive({
  kode_transaksi: '',
  id_user: '',
  id_admin: '',
  total_harga: '',
  status_pembayaran: 'belum_bayar',
  status: 'pending'
})

const fetchTransactions = async () => {
  isLoading.value = true
  try {
    const res = await api.get('/dashboard/transactions')
    transactions.value = res.data.data || res.data || []
  } catch (error) {
    console.error('Error fetching transactions:', error)
  } finally {
    isLoading.value = false
  }
}

// Mendukung semua kemungkinan penamaan relasi dari Laravel Backend
const getDetails = (item) => {
  if (!item) return []
  return (
    item.transaksi_detail ||
    item.transaksi_details ||
    item.transaction_detail ||
    item.transaction_details ||
    item.details ||
    item.detail ||
    item.items ||
    []
  )
}

// Mengambil detail lengkap transaksi jika list utama belum memuat array relasinya
const openDetailModal = async (item) => {
  detailItem.value = item
  isDetailOpen.value = true

  if (getDetails(item).length === 0 && item.id) {
    isFetchingDetail.value = true
    try {
      const res = await api.get(`/dashboard/transactions/${item.id}`)
      detailItem.value = res.data.data || res.data
    } catch (error) {
      console.error('Gagal mengambil rincian detail transaksi:', error)
    } finally {
      isFetchingDetail.value = false
    }
  }
}

const closeDetailModal = () => {
  isDetailOpen.value = false
}

const openModal = (mode, item = null) => {
  isEditMode.value = mode === 'edit'
  if (mode === 'edit' && item) {
    selectedId.value = item.id
    form.kode_transaksi = item.kode_transaksi
    form.id_user = item.id_user
    form.id_admin = item.id_admin || ''
    form.total_harga = item.total_harga
    form.status_pembayaran = item.status_pembayaran || 'belum_bayar'
    form.status = item.status || 'pending'
  } else {
    selectedId.value = null
    form.kode_transaksi = 'TRX-' + Date.now()
    form.id_user = ''
    form.id_admin = ''
    form.total_harga = ''
    form.status_pembayaran = 'belum_bayar'
    form.status = 'pending'
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveTransaction = async () => {
  isSaving.value = true
  try {
    if (isEditMode.value) {
      await api.patch(`/dashboard/transactions/${selectedId.value}/status`, form)
    } else {
      await api.post('/dashboard/transactions', form)
    }
    await fetchTransactions()
    closeModal()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menyimpan transaksi!')
  } finally {
    isSaving.value = false
  }
}

const quickUpdateStatus = async (item, newStatus) => {
  try {
    await api.patch(`/dashboard/transactions/${item.id}/status`, {
      status: newStatus,
      status_pembayaran: item.status_pembayaran || 'belum_bayar'
    })

    item.status = newStatus
    if (detailItem.value && detailItem.value.id === item.id) {
      detailItem.value.status = newStatus
    }
    await fetchTransactions()
    alert('Status transaksi berhasil diperbarui!')
  } catch (error) {
    console.error('Detail Error:', error.response?.data)
    alert(error.response?.data?.message || 'Gagal mengupdate status transaksi.')
  }
}

const deleteTransaction = async (id) => {
  if (!confirm('Yakin ingin menghapus transaksi ini?')) return
  try {
    await api.delete(`/dashboard/transactions/${id}`)
    await fetchTransactions()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus transaksi!')
  }
}

onMounted(() => {
  fetchTransactions()
})
</script>

<style scoped>
th, td {
  text-align: center;
}

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

.table-card {
  background-color: #1e293b;
  border-radius: 12px;
  border: 1px solid #334155;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.875rem;
}

.custom-table th {
  background-color: #0f172a;
  padding: 14px 16px;
  color: #94a3b8;
  font-weight: 600;
  border-bottom: 1px solid #334155;
}

.custom-table td {
  padding: 16px;
  border-bottom: 1px solid #334155;
  color: #cbd5e1;
}

.custom-table tr:hover {
  background-color: #243248;
}

.empty-state {
  text-align: center;
  padding: 32px;
  color: #64748b;
}

.font-bold {
  font-weight: 600;
  color: #f8fafc;
}

.font-mono {
  font-family: monospace;
}

.text-blue { color: #38bdf8; }
.text-emerald { color: #34d399; }
.text-amber { color: #fbbf24; }
.uppercase { text-transform: uppercase; }

/* Badges */
.badge-type {
  padding: 4px 8px;
  background-color: #0f172a;
  border: 1px solid #334155;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  color: #cbd5e1;
  text-transform: uppercase;
}

.badge-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-pending {
  background-color: rgba(251, 191, 36, 0.15);
  color: #fbbf24;
  border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-success {
  background-color: rgba(52, 211, 153, 0.15);
  color: #34d399;
  border: 1px solid rgba(52, 211, 153, 0.3);
}

.status-cancel {
  background-color: rgba(248, 113, 113, 0.15);
  color: #f87171;
  border: 1px solid rgba(248, 113, 113, 0.3);
}

/* Action Buttons */
.action-buttons {
  justify-content: center;
  display: flex;
  gap: 8px;
}

.btn-primary {
  background-color: #3b82f6;
  color: #ffffff;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 6px;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-detail {
  background-color: rgba(59, 130, 246, 0.2);
  color: #60a5fa;
  border: 1px solid rgba(59, 130, 246, 0.4);
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}

.btn-detail:hover {
  background-color: rgba(59, 130, 246, 0.4);
}

.btn-edit {
  background-color: #38bdf8;
  color: #0f172a;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}

.btn-edit:hover {
  background-color: #0284c7;
  color: #fff;
}

.btn-delete {
  background-color: #ef4444;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}

.btn-delete:hover {
  background-color: #dc2626;
}

.btn-cancel {
  background-color: #475569;
  color: #ffffff;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel:hover {
  background-color: #334155;
}

/* Modals */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-box {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 12px;
  padding: 24px;
  width: 100%;
  max-width: 480px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
}

.modal-lg {
  max-width: 680px;
}

.modal-box h3 {
  margin-top: 0;
  margin-bottom: 16px;
  color: #ffffff;
}

.modal-header-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.btn-close {
  background: transparent;
  border: none;
  color: #94a3b8;
  font-size: 1.5rem;
  cursor: pointer;
}

.btn-close:hover {
  color: #ffffff;
}

.meta-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  background-color: #0f172a;
  padding: 12px 16px;
  border-radius: 8px;
  border: 1px solid #334155;
  margin-bottom: 20px;
}

.meta-item {
  display: flex;
  flex-direction: column;
}

.meta-label {
  font-size: 0.75rem;
  color: #64748b;
}

.meta-val {
  font-size: 0.875rem;
  color: #f8fafc;
}

.sub-title {
  font-size: 0.875rem;
  color: #cbd5e1;
  margin: 0 0 10px 0;
}

.sub-table-card {
  background-color: #0f172a;
  border-radius: 8px;
  border: 1px solid #334155;
  overflow: hidden;
  margin-bottom: 20px;
}

.sub-table th {
  padding: 10px 12px;
  font-size: 0.75rem;
}

.sub-table td {
  padding: 10px 12px;
  font-size: 0.8rem;
}

.modal-footer-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 12px;
  border-top: 1px solid #334155;
}

.quick-status {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.875rem;
  color: #94a3b8;
}

.quick-status select {
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #ffffff;
  padding: 6px 10px;
  border-radius: 6px;
}

/* Form Styles */
.form-group {
  margin-bottom: 16px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.form-group label {
  display: block;
  font-size: 0.875rem;
  color: #94a3b8;
  margin-bottom: 6px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  background-color: #0f172a;
  border: 1px solid #334155;
  border-radius: 8px;
  color: #ffffff;
  box-sizing: border-box;
} 

.form-group input:focus,
.form-group select:focus {
  outline: 2px solid #3b82f6;
  border-color: transparent;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
}
</style>  