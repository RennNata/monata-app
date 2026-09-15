<template>
  <div class="dashboard-container">
    <Sidebar />

    <main class="main-content">
      <div class="page-header">
        <div>
          <h2>Kelola Produk</h2>
          <p>Atur semua produk, stok, harga, dan kategorinya di sini.</p>
        </div>
        <button class="btn-primary" @click="openModal('add')">
          <span>+</span> Tambah Produk
        </button>
      </div>

      <div class="table-card">
        <table class="custom-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="isLoading">
              <td colspan="7" class="empty-state">Lagi ngambil data produk... Sabar ya!</td>
            </tr>
            <tr v-else-if="products.length === 0">
              <td colspan="7" class="empty-state">Belum ada data produk. Tambahin dulu yuk!</td>
            </tr>
            <tr v-else v-for="(prod, index) in products" :key="prod.id">
              <td>#{{ index + 1 }}</td>
              <td>
                <img 
                  v-if="prod.foto" 
                  :src="getFotoUrl(prod.foto)" 
                  alt="Foto Produk" 
                  class="img-thumbnail" 
                />
                <span v-else class="empty-text">-</span>
              </td>
              <td class="font-bold">{{ prod.nama_produk }}</td>
              <td>{{ getCategoryName(prod.id_kategori, prod.category) }}</td>
              <td>Rp {{ Number(prod.harga).toLocaleString('id-ID') }}</td>
              <td>{{ prod.stok }}</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-edit" @click="openModal('edit', prod)">Edit</button>
                  <button class="btn-delete" @click="deleteProduct(prod.id)">Hapus</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Tambah/Edit Produk -->
      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-box">
          <h3>{{ isEditMode ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
          <form @submit.prevent="saveProduct">
            <div class="form-group">
              <label>Nama Produk</label>
              <input 
                v-model="form.nama_produk" 
                type="text" 
                placeholder="Contoh: Seragam SD Lengan Pendek" 
                required 
              />
            </div>

            <div class="form-group">
              <label>Kategori</label>
              <select v-model="form.id_kategori" required>
                <option value="" disabled>-- Pilih Kategori --</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.nama_kategori }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Harga (Rp)</label>
              <input 
                v-model="form.harga" 
                type="number" 
                placeholder="Contoh: 75000" 
                required 
              />
            </div>

            <div class="form-group">
              <label>Stok</label>
              <input 
                v-model="form.stok" 
                type="number" 
                placeholder="Contoh: 50" 
                required 
              />
            </div>

            <div class="form-group">
              <label>Foto Produk (Opsional)</label>
              <input type="file" @change="handleFileUpload" accept="image/*" />
            </div>

            <div class="modal-actions">
              <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
              <button type="submit" class="btn-primary" :disabled="isSaving">
                {{ isSaving ? 'Ngesave...' : (isEditMode ? 'Simpan Perubahan' : 'Tambah Produk') }}
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

const products = ref([])
const categories = ref([])
const isLoading = ref(false)
const isSaving = ref(false)
const isModalOpen = ref(false)
const isEditMode = ref(false)
const selectedId = ref(null)

const form = reactive({
  id_kategori: '',
  nama_produk: '',
  harga: '',
  stok: '',
  foto: null
})

// Fetch data produk & data kategori buat dropdown
const fetchData = async () => {
  isLoading.value = true
  try {
    const [resProducts, resCategories] = await Promise.all([
      api.get('/dashboard/products'),
      api.get('/dashboard/categories')
    ])
    
    products.value = resProducts.data.data || resProducts.data
    categories.value = resCategories.data.data || resCategories.data
  } catch (error) {
    console.error('Error fetching data:', error)
    alert(error.response?.data?.message || 'Gagal ngambil data dari database!')
  } finally {
    isLoading.value = false
  }
}

// Handling upload file foto
const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.foto = file
  }
}

const saveProduct = async () => {
  isSaving.value = true
  try {
    const formData = new FormData()
    formData.append('id_kategori', form.id_kategori)
    formData.append('nama_produk', form.nama_produk)
    formData.append('harga', form.harga)
    formData.append('stok', form.stok)
    
    if (form.foto instanceof File) {
      formData.append('foto', form.foto)
    }

    if (isEditMode.value) {
      // Laravel butuh _method = PUT kalau ngirim FormData lewat POST
      formData.append('_method', 'PUT')
      await api.post(`/dashboard/products/${selectedId.value}`, formData)
    } else {
      await api.post('/dashboard/products', formData)
    }
    
    await fetchData()
    closeModal()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menyimpan data produk!')
  } finally {
    isSaving.value = false
  }
}

const deleteProduct = async (id) => {
  if (!confirm('Yakin mau hapus produk ini? Data bakal hilang permanen!')) return

  try {
    await api.delete(`/dashboard/products/${id}`)
    await fetchData()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal ngehapus data!')
  }
}

const openModal = (mode, product = null) => {
  isEditMode.value = mode === 'edit'
  if (mode === 'edit' && product) {
    selectedId.value = product.id
    form.id_kategori = product.id_kategori
    form.nama_produk = product.nama_produk
    form.harga = product.harga
    form.stok = product.stok
    form.foto = null // foto diisi cuma kalau user mau ubah gambar
  } else {
    selectedId.value = null
    form.id_kategori = ''
    form.nama_produk = ''
    form.harga = ''
    form.stok = ''
    form.foto = null
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

// Helper nampilin nama kategori di tabel
const getCategoryName = (categoryId, categoryObject) => {
  if (categoryObject?.nama_kategori) return categoryObject.nama_kategori
  const found = categories.value.find(c => c.id === categoryId)
  return found ? found.nama_kategori : '-'
}

// Helper nampilin URL Foto
const getFotoUrl = (fotoPath) => {
  if (!fotoPath) return ''
  if (fotoPath.startsWith('http')) return fotoPath
  return `https://mayra-glaucous-cloudlessly.ngrok-free.dev/storage/${fotoPath}`
}

onMounted(() => {
  fetchData()
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

.img-thumbnail {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 6px;
}

.empty-state {
  text-align: center;
  padding: 32px;
  color: #64748b;
}

.empty-text {
  color: #64748b;
}

.font-bold {
  font-weight: 600;
  color: #f8fafc;
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

.action-buttons {
  justify-content: center;
  display: flex;
  gap: 8px;
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
  max-width: 450px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
}

.modal-box h3 {
  margin-top: 0;
  margin-bottom: 16px;
  color: #ffffff;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  font-size: 0.875rem;
  color: #94a3b8;
  margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  background-color: #0f172a;
  border: 1px solid #334155;
  border-radius: 8px;
  color: #ffffff;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
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