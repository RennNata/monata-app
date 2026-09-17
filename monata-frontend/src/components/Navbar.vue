<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const searchQuery = ref('')
const router = useRouter()

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/katalog', query: { search: searchQuery.value } })
  }
}
</script>

<template>
  <nav class="navbar">
    <!-- Logo SMK Assalaam -->
    <div class="nav-brand">
      <router-link to="/home">
        <img src="../assets/logos/logo-smkassalaam.png" alt="Logo SMK Assalaam" class="logo" />
      </router-link>
    </div>

    <!-- Form Pencarian (Tengah) -->
    <form @submit.prevent="handleSearch" class="search-form">
      <input 
        v-model="searchQuery" 
        type="text" 
        placeholder="Cari seragam, buku, atau pulpen..." 
        class="search-input"
      />
      <button type="submit" class="search-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </button>
    </form>

    <!-- Menu Navigasi (Kanan) -->
    <div class="nav-links">
      <router-link to="/katalog" class="nav-item">Katalog</router-link>
      <router-link to="/profile" class="nav-item">Profile</router-link>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 32px;
  background-color: #1e293b;
  border-bottom: 1px solid #334155;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  position: sticky;
  top: 0;
  z-index: 100;
  backdrop-filter: blur(8px);
}

.logo {
  height: 42px;
  object-fit: contain;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.logo:hover {
  transform: scale(1.03);
}

.search-form {
  display: flex;
  align-items: center;
  flex: 0 1 420px;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 10px 42px 10px 16px;
  background-color: #0f172a;
  border: 1px solid #334155;
  border-radius: 99px;
  outline: none;
  font-size: 14px;
  color: #f8fafc;
  transition: all 0.3s ease;
}

.search-input::placeholder {
  color: #94a3b8;
}

.search-input:focus {
  border-color: #38bdf8;
  box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
}

.search-btn {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  color: #94a3b8;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.search-input:focus + .search-btn,
.search-btn:hover {
  color: #38bdf8;
}

.nav-links {
  display: flex;
  gap: 24px;
}

.nav-item {
  text-decoration: none;
  color: #94a3b8;
  font-weight: 600;
  font-size: 15px;
  padding: 6px 12px;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  color: #38bdf8;
  background-color: rgba(56, 189, 248, 0.08);
}

.router-link-active {
  color: #38bdf8;
  background-color: rgba(56, 189, 248, 0.12);
}
</style>