<template>
  <div class="dashboard-container">
    <Sidebar />

    <main class="main-content">
      <div class="page-header">
        <div>
          <h2>Kelola Kategori</h2>
          <p>Atur dan rapihin semua kategori produk toko lu di sini.</p>
        </div>
        <button class="btn-primary" @click="openModal('add')">
          <span>+</span> Tambah Kategori
        </button>
      </div>

      <div class="table-card">
        <table class="custom-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="isLoading">
              <td colspan="5" class="empty-state">Lagi ngambil data dari server... Sabar!</td>
            </tr>
            <tr v-else-if="categories.length === 0">
              <td colspan="5" class="empty-state">Belum ada data kategori. Sepi amat!</td>
            </tr>
            <tr v-else v-for="(cat, index) in categories" :key="cat.id">
              <td>#{{ index + 1 }}</td>
              <td class="font-bold">{{ cat.nama_kategori }}</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-edit" @click="openModal('edit', cat)">Edit</button>
                  <button class="btn-delete" @click="deleteCategory(cat.id)">Hapus</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-box">
          <h3>{{ isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h3>
          <form @submit.prevent="saveCategory">
            <div class="form-group">
              <label>Nama Kategori</label>
              <input 
                v-model="form.nama_kategori" 
                type="text" 
                placeholder="Contoh: Seragam Sekolah" 
                required 
              />
            </div>
            <div class="modal-actions">
              <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
              <button type="submit" class="btn-primary" :disabled="isSaving">
                {{ isSaving ? 'Ngesave...' : (isEditMode ? 'Simpan Perubahan' : 'Tambah Kategori') }}
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

const categories = ref([])
const isLoading = ref(false)
const isSaving = ref(false)
const isModalOpen = ref(false)
const isEditMode = ref(false)
const selectedId = ref(null)

const form = reactive({
  nama_kategori: '',
  description: ''
})

const fetchCategories = async () => {
  isLoading.value = true
  try {
    const response = await api.get('/dashboard/categories')
    // Pake response.data.data kalo dari Laravel API Resource, atau response.data biasa
    categories.value = response.data.data || response.data
  } catch (error) {
    console.error('Error fetch categories:', error)
    alert(error.response?.data?.message || 'Gagal ngambil data kategori dari database!')
  } finally {
    isLoading.value = false
  }
}

const saveCategory = async () => {
  isSaving.value = true
  try {
    if (isEditMode.value) {
      await api.put(`/dashboard/categories/${selectedId.value}`, form)
    } else {
      await api.post('/dashboard/categories', form)
    }
    
    await fetchCategories()
    closeModal()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menyimpan data kategori!')
  } finally {
    isSaving.value = false
  }
}

const deleteCategory = async (id) => {
  if (!confirm('Yakin mau hapus kategori ini? Data gak bisa diganti lagi pas dihapus!')) return

  try {
    await api.delete(`/dashboard/categories/${id}`)
    await fetchCategories()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal ngehapus data!')
  }
}

const openModal = (mode, category = null) => {
  isEditMode.value = mode === 'edit'
  if (mode === 'edit' && category) {
    selectedId.value = category.id
    form.nama_kategori = category.nama_kategori
    form.description = category.description
  } else {
    selectedId.value = null
    form.nama_kategori = ''
    form.description = ''
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

onMounted(() => {
  fetchCategories()
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

.page-header h2{
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 4px 0;
  color: #ffffff;
}

h2 {
  color: #243248 !important;
}

.page-header p {
  margin: 0;
  font-size: 0.875rem;
  color: #94a3b8;
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

.badge-slug {
  background-color: #0f172a;
  color: #38bdf8;
  padding: 4px 8px;
  border-radius: 6px;
  font-family: monospace;
  font-size: 0.8rem;
  border: 1px solid #1e293b;
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