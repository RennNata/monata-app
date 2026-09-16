<template>
  <div class="hero-container">
    <!-- Full Width Background Image + Dark Overlay -->
    <div class="hero-bg-wrapper">
      <img 
        src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=1600" 
        alt="Seragam Sekolah SMK" 
        class="hero-bg-image"
      />
      <div class="hero-overlay"></div>
    </div>

    <!-- Header / Navbar -->
    <header class="navbar">
      <div class="nav-left">
        <h1 class="logo">MONATA</h1>
        <nav class="nav-links">
          <a href="#katalog">Katalog</a>
          <a href="#kategori">Kategori</a>
          <a href="#tentang">Tentang Kami</a>
        </nav>
      </div>

      <div class="nav-right">
        <!-- Search Bar -->
        <div class="search-box">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Cari produk..." 
            @keyup.enter="handleSearch"
          />
        </div>

        <!-- Tombol Keranjang -->
        <button class="cart-btn" @click="goToCart">
          <span>Keranjang</span>
          <span class="cart-badge">{{ cartCount }}</span>
        </button>
      </div>
    </header>

    <!-- Main Hero Headline (Pojok Kiri Bawah) -->
    <div class="hero-content">
      <div class="hero-headline">
        <h2>Seragam Presisi</h2>
        <h2>Untuk Masa Depan</h2>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')
const cartCount = ref(0)

const handleSearch = () => {
  if (!searchQuery.value.trim()) return
  console.log('Mencari:', searchQuery.value)
}

const goToCart = () => {
  console.log('Navigasi ke keranjang')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* Main Hero Container */
.hero-container {
  position: relative;
  width: 100%;
  height: 100vh;
  background-color: #0c0f14;
  color: #ffffff;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 32px 48px;
  box-sizing: border-box;
}

/* Background Image & Full Overlay (Bikin Nyatu Sama Background) */
.hero-bg-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.hero-bg-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center 30%;
  filter: contrast(1.1) brightness(0.85);
}

/* Lapisan Gelap Agar Teks & Navbar Tetap Jelas */
.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    180deg, 
    rgba(12, 15, 20, 0.75) 0%, 
    rgba(12, 15, 20, 0.4) 40%, 
    rgba(12, 15, 20, 0.9) 100%
  );
}

/* Navbar Layout */
.navbar {
  position: relative;
  z-index: 10;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 40px;
}

.logo {
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: -0.5px;
  margin: 0;
  color: #ffffff;
}

.nav-links {
  display: flex;
  gap: 28px;
}

.nav-links a {
  color: #cbd5e1;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  transition: color 0.2s;
}

.nav-links a:hover {
  color: #ffffff;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

/* Search Bar Styling */
.search-box {
  display: flex;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 99px;
  padding: 8px 16px;
  backdrop-filter: blur(12px);
  transition: all 0.2s ease;
}

.search-box:focus-within {
  background-color: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.4);
}

.search-icon {
  width: 16px;
  height: 16px;
  color: #cbd5e1;
  margin-right: 8px;
}

.search-box input {
  background: transparent;
  border: none;
  outline: none;
  color: #ffffff;
  font-size: 0.875rem;
  width: 160px;
}

.search-box input::placeholder {
  color: #94a3b8;
}

/* Tombol Keranjang Kanan Atas */
.cart-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  background-color: #ffffff;
  color: #0c0f14;
  border: none;
  padding: 10px 22px;
  border-radius: 99px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: transform 0.2s, background-color 0.2s;
}

.cart-btn:hover {
  background-color: #f1f5f9;
  transform: translateY(-1px);
}

.cart-badge {
  background-color: #0c0f14;
  color: #ffffff;
  font-size: 0.75rem;
  padding: 2px 8px;
  border-radius: 12px;
}

/* Hero Headline Position */
.hero-content {
  position: relative;
  z-index: 10;
  margin-bottom: 20px;
}

.hero-headline h2 {
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 3.75rem;
  font-weight: 400;
  line-height: 1.1;
  margin: 0;
  color: #f8fafc;
  letter-spacing: -1px;
  text-shadow: 0 10px 20px rgba(0,0,0,0.6);
}

/* Responsive Handling */
@media (max-width: 900px) {
  .hero-container {
    padding: 24px;
  }

  .nav-links {
    display: none;
  }

  .hero-headline h2 {
    font-size: 2.5rem;
  }

  .search-box input {
    width: 100px;
  }
}
</style>