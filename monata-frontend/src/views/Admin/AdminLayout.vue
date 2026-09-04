<template>
  <div class="admin-layout">
    <!-- Panggil komponen Sidebar -->
    <AdminSidebar />

    <div class="main-wrapper">
      <!-- Panggil komponen Topbar -->
      <AdminTopbar />

      <main class="content-area">
        <!-- Tempat ganti-ganti halaman konten (Kategori, Produk, dll) -->
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
// Gunakan ../../ untuk mundur dua kali ke folder src, lalu masuk ke folder components
import AdminSidebar from '../../components/AdminSidebar.vue'
import AdminTopbar from '../../components/AdminTopbar.vue'
</script>

<style scoped>
.admin-layout { display: flex; min-height: 100vh; background-color: #f8fafc; font-family: 'Inter', sans-serif; }
.main-wrapper { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-width: 0; }
.content-area { padding: 32px; flex: 1; }
</style>