<template>
  <aside class="sidebar">
    <div class="brand">
      <span class="dot blue"></span>
      <span class="dot dark"></span>
      <span class="brand-name">Monata Admin</span>
    </div>

    <nav class="nav-menu">
      <router-link to="/admin/kategori" class="nav-item" active-class="active">
        <span class="icon">📁</span><span>Kelola Kategori</span>
      </router-link>
      <router-link to="/admin/produk" class="nav-item" active-class="active">
        <span class="icon">📦</span><span>Kelola Produk</span>
      </router-link>
      <router-link to="/admin/transaksi" class="nav-item" active-class="active">
        <span class="icon">💳</span><span>Kelola Transaksi</span>
      </router-link>
      <router-link to="/admin/detail-transaksi" class="nav-item" active-class="active">
        <span class="icon">📄</span><span>Detail Transaksi</span>
      </router-link>
      <div class="menu-divider"></div>
      <router-link to="/admin/kelola-admin" class="nav-item" active-class="active">
        <span class="icon">👤</span><span>Kelola Admin</span>
      </router-link>
    </nav>

    <div class="sidebar-footer">
      <button @click="handleLogout" class="btn-logout">
        <span class="icon">🚪</span><span>Keluar</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  router.push('/login')
}
</script>

<style scoped>
.sidebar { width: 260px; background-color: #090d16; color: #ffffff; display: flex; flex-direction: column; padding: 24px 16px; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; }
.brand { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 18px; padding: 0 12px; margin-bottom: 36px; }
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot.blue { background: #3b82f6; }
.dot.dark { background: #94a3b8; }
.nav-menu { display: flex; flex-direction: column; gap: 6px; flex: 1; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #94a3b8; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 10px; transition: all 0.2s ease; }
.nav-item:hover { background-color: #1e293b; color: #ffffff; }
.nav-item.active { background-color: #3b82f6; color: #ffffff; font-weight: 600; }
.menu-divider { height: 1px; background-color: #1e293b; margin: 12px 0; }
.sidebar-footer { margin-top: auto; padding-top: 16px; border-top: 1px solid #1e293b; }
.btn-logout { width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: transparent; border: none; color: #ef4444; font-size: 14px; font-weight: 600; border-radius: 10px; cursor: pointer; transition: 0.2s; }
.btn-logout:hover { background-color: rgba(239, 68, 68, 0.1); }
</style>