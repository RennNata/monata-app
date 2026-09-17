<template>
  <div class="dashboard-container">
    <Sidebar />

    <main class="main-content">
      <div class="page-header">
        <div>
          <h2>Kelola Admin</h2>
          <p>Atur data akun admin dan super admin di sini.</p>
        </div>
        <button class="btn-primary" @click="openModal('add')">
          <span>+</span> Tambah Admin
        </button>
      </div>

      <div class="table-card">
        <table class="custom-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Admin</th>
              <th>NIS / NIP</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="isLoading">
              <td colspan="5" class="empty-state">Lagi ngambil data admin... Sabar ya!</td>
            </tr>
            <tr v-else-if="admins.length === 0">
              <td colspan="5" class="empty-state">Belum ada data admin. Tambahin dulu yuk!</td>
            </tr>
            <tr v-else v-for="(admin, index) in admins" :key="admin.id">
              <td>{{ index + 1 }}</td>
              <td class="font-bold">{{ admin.name }}</td>
              <td>{{ admin.nis_nip }}</td>
              <td>
                <span class="badge-role" :class="admin.role">{{ admin.role }}</span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="btn-edit" @click="openModal('edit', admin)">Edit</button>
                  <button class="btn-delete" @click="deleteAdmin(admin.id)">Hapus</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Tambah/Edit Admin -->
      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-box">
          <h3>{{ isEditMode ? 'Edit Admin' : 'Tambah Admin Baru' }}</h3>
          <form @submit.prevent="saveAdmin">
            <div class="form-group">
              <label>Nama Admin</label>
              <input 
                v-model="form.name" 
                type="text" 
                placeholder="Contoh: Budi Santoso" 
                required 
              />
            </div>

            <div class="form-group">
              <label>NIS / NIP</label>
              <input 
                v-model="form.nis_nip" 
                type="text" 
                placeholder="Contoh: 198203152008011002" 
                required 
              />
            </div>

            <div class="form-group">
              <label>Password {{ isEditMode ? '(Kosongkan jika tak diubah)' : '' }}</label>
              <input 
                v-model="form.password" 
                type="password" 
                :placeholder="isEditMode ? '••••••••' : 'Masukkan password'" 
                :required="!isEditMode" 
              />
            </div>

            <div class="form-group">
              <label>Role</label>
              <select v-model="form.role" required>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </div>

            <div class="modal-actions">
              <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
              <button type="submit" class="btn-primary" :disabled="isSaving">
                {{ isSaving ? 'Ngesave...' : (isEditMode ? 'Simpan Perubahan' : 'Tambah Admin') }}
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

const admins = ref([])
const isLoading = ref(false)
const isSaving = ref(false)
const isModalOpen = ref(false)
const isEditMode = ref(false)
const selectedId = ref(null)

const form = reactive({
  name: '',
  nis_nip: '',
  password: '',
  role: 'admin'
})

const fetchAdmins = async () => {
  isLoading.value = true
  try {
    const res = await api.get('/dashboard/admins')
    admins.value = res.data.data || res.data
  } catch (error) {
    console.error('Error fetching admins:', error)
    alert(error.response?.data?.message || 'Gagal ngambil data admin!')
  } finally {
    isLoading.value = false
  }
}

const saveAdmin = async () => {
  isSaving.value = true
  try {
    const payload = {
      name: form.name,
      nis_nip: form.nis_nip,
      role: form.role
    }

    if (form.password) {
      payload.password = form.password
    }

    if (isEditMode.value) {
      await api.put(`/dashboard/admins/${selectedId.value}`, payload)
    } else {
      await api.post('/dashboard/admins', payload)
    }
    
    await fetchAdmins()
    closeModal()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menyimpan data admin!')
  } finally {
    isSaving.value = false
  }
}

const deleteAdmin = async (id) => {
  if (!confirm('Yakin mau hapus admin ini?')) return

  try {
    await api.delete(`/dashboard/admins/${id}`)
    await fetchAdmins()
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal ngehapus data!')
  }
}

const openModal = (mode, admin = null) => {
  isEditMode.value = mode === 'edit'
  if (mode === 'edit' && admin) {
    selectedId.value = admin.id
    form.name = admin.name
    form.nis_nip = admin.nis_nip
    form.password = ''
    form.role = admin.role || 'admin'
  } else {
    selectedId.value = null
    form.name = ''
    form.nis_nip = ''
    form.password = ''
    form.role = 'admin'
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

onMounted(() => {
  fetchAdmins()
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

.badge-role {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-role.super_admin {
  background-color: rgba(236, 72, 153, 0.2);
  color: #f472b6;
  border: 1px solid rgba(236, 72, 153, 0.4);
}

.badge-role.admin {
  background-color: rgba(59, 130, 246, 0.2);
  color: #60a5fa;
  border: 1px solid rgba(59, 130, 246, 0.4);
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