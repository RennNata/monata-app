<template>
  <div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <!-- Brand Logo -->
      <div class="brand">
        <span class="dot blue"></span>
        <span class="dot dark"></span>
        <span class="brand-name">Monata Admin</span>
      </div>

      <!-- Navigation Menu -->
      <nav class="nav-menu">
        <router-link to="/admin/dashboard" class="nav-item" active-class="active">
          <span class="icon">📊</span>
          <span>Dashboard</span>
        </router-link>

        <router-link to="/admin/kategori" class="nav-item" active-class="active">
          <span class="icon">📁</span>
          <span>Kelola Kategori</span>
        </router-link>

        <router-link to="/admin/produk" class="nav-item" active-class="active">
          <span class="icon">📦</span>
          <span>Kelola Produk</span>
        </router-link>

        <router-link to="/admin/transaksi" class="nav-item" active-class="active">
          <span class="icon">💳</span>
          <span>Kelola Transaksi</span>
        </router-link>

        <router-link to="/admin/detail-transaksi" class="nav-item" active-class="active">
          <span class="icon">📄</span>
          <span>Detail Transaksi</span>
        </router-link>

        <div class="menu-divider"></div>

        <router-link to="/admin/kelola-admin" class="nav-item" active-class="active">
          <span class="icon">👤</span>
          <span>Kelola Admin</span>
        </router-link>
      </nav>

      <!-- Logout Button -->
      <div class="sidebar-footer">
        <button @click="handleLogout" class="btn-logout">
          <span class="icon">🚪</span>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main-wrapper">
      <!-- Topbar Navbar -->
      <header class="topbar">
        <div class="topbar-left">
          <h1 class="page-title">Admin Control Panel</h1>
        </div>
        <div class="topbar-right">
          <div class="admin-badge">
            <span class="avatar">A</span>
            <div class="info">
              <span class="name">Administrator</span>
              <span class="role">Super Admin</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Router View buat ganti-ganti konten halaman -->
      <main class="content-area">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = () => {
  // Hapus kredensial
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  
  // Lempar balik ke login
  router.push('/login')
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  font-family: 'Inter', sans-serif;
}

/* SIDEBAR STYLES */
.sidebar {
  width: 260px;
  background-color: #090d16;
  color: #ffffff;
  display: flex;
  flex-direction: column;
  padding: 24px 16px;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  z-index: 100;
}

.brand {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 700;
  font-size: 18px;
  padding: 0 12px;
  margin-bottom: 36px;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
.dot.blue { background: #3b82f6; }
.dot.dark { background: #94a3b8; }

.nav-menu {
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex: 1;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  color: #94a3b8;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  border-radius: 10px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background-color: #1e293b;
  color: #ffffff;
}

.nav-item.active {
  background-color: #3b82f6;
  color: #ffffff;
  font-weight: 600;
}

.menu-divider {
  height: 1px;
  background-color: #1e293b;
  margin: 12px 0;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 16px;
  border-top: 1px solid #1e293b;
}

.btn-logout {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  background: transparent;
  border: none;
  color: #ef4444;
  font-size: 14px;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.2s;
}

.btn-logout:hover {
  background-color: rgba(239, 68, 68, 0.1);
}

/* MAIN CONTENT STYLES */
.main-wrapper {
  margin-left: 260px;
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.topbar {
  height: 70px;
  background-color: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
}

.page-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.admin-badge {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  width: 36px;
  height: 36px;
  background-color: #0f172a;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 14px;
}

.info {
  display: flex;
  flex-direction: column;
}

.info .name {
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
}

.info .role {
  font-size: 11px;
  color: #64748b;
}

.content-area {
  padding: 32px;
  flex: 1;
}
</style>