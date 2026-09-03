<template>
  <div class="landing-container">
    
    <!-- TOP SECTION -->
    <header class="top-section">
      <!-- Pojok Kiri Atas: Placeholder Mockup Seragam -->
      <div class="seragam-wrapper">
        <div class="placeholder-content">
          <svg xmlns="http://www.w3.org/2000/svg" class="shirt-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.42 0 .805.228 1.008.598l1.617 2.91a2.25 2.25 0 002.008 1.242h.008c.783 0 1.51-.383 1.952-1.03l1.706-2.52a1.125 1.125 0 01.936-.492h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .42-.228.805-.598 1.008l-2.91 1.617a2.25 2.25 0 00-1.242 2.008v5.25c0 .621-.504 1.125-1.125 1.125H6.375a1.125 1.125 0 01-1.125-1.125v-5.25a2.25 2.25 0 00-1.242-2.008l-2.91-1.617A1.125 1.125 0 01.5 8.625v-3.75z" />
          </svg>
          <span>Mockup Seragam</span>
        </div>
      </div>

      <!-- Pojok Kanan Atas: Jam Buka & Tutup -->
      <div class="jam-wrapper">
        <div class="jam-header">
          <span class="dot-indicator"></span>
          <span class="jam-label">Jam Operasional</span>
        </div>
        <div class="jam-time">07.00 - 15.00 WIB</div>
        <span class="jam-sub">Senin - Jumat</span>
      </div>
    </header>

    <!-- CENTER SECTION -->
    <main class="hero-section">
      <h1 class="title-monata">MONATA</h1>
      <p class="subtitle-monata">Layanan Tata Usaha SMK Assalaam Bandung</p>

      <div class="action-buttons">
        <router-link to="/register" class="btn btn-register">Register</router-link>
        <router-link to="/login" class="btn btn-login">Login</router-link>
      </div>
    </main>

    <!-- BOTTOM SECTION -->
    <footer class="bottom-section">
      <!-- Pojok Kiri Bawah: Alat Tulis -->
      <div class="decor-wrapper">
        <img :src="alatTulisImg" alt="Alat Tulis" class="img-decor" />
      </div>

      <!-- Pojok Kanan Bawah: Logo SMK Assalaam -->
      <div class="logo-wrapper">
        <img :src="LogoSmkImg" alt="Logo SMK Assalaam" class="img-logo" />
      </div>
    </footer>

  </div>
</template>

<script setup>
import alatTulisImg from '../assets/decor/alat-tulis.png'
import LogoSmkImg from '../assets/logos/logo-smkassalaam.png'
</script>

<style scoped>
.landing-container {
  position: relative;
  height: 100vh;
  width: 100%;
  background-color: #f8fafc;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 1.5rem 2rem;
  box-sizing: border-box;
  overflow: hidden;
  user-select: none;
}

/* TOP SECTION */
.top-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 100%;
  z-index: 10;
}

.seragam-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem;
  background-color: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(4px);
  border: 2px dashed #cbd5e1;
  border-radius: 0.75rem;
  width: 8.5rem;
  height: 8.5rem;
  transition: border-color 0.2s ease;
}

.seragam-wrapper:hover {
  border-color: #94a3b8;
}

.placeholder-content {
  color: #94a3b8;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.shirt-icon {
  width: 2.5rem;
  height: 2.5rem;
}

.jam-wrapper {
  background-color: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(4px);
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
  border-radius: 0.75rem;
  padding: 0.75rem 1.25rem;
  border: 1px solid #f1f5f9;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.jam-header {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  margin-bottom: 0.125rem;
}

.dot-indicator {
  width: 0.5rem;
  height: 0.5rem;
  border-radius: 9999px;
  background-color: #10b981;
}

.jam-label {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  color: #64748b;
  text-transform: uppercase;
}

.jam-time {
  font-size: 1.35rem;
  font-weight: 700;
  color: #0f172a;
}

.jam-sub {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 0.125rem;
}

/* HERO SECTION */
.hero-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin: auto 0;
  z-index: 10;;
}

.title-monata {
  font-size: 3.75rem;
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -0.025em;
  margin: 0 0 0.25rem 0;
  line-height: 1;
}

.subtitle-monata {
  font-size: 0.95rem;
  font-weight: 500;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  max-width: 32rem;
  margin: 0 0 1.5rem 0;
}

.action-buttons {
  display: flex;
  gap: 0.875rem;
  width: 100%;
  justify-content: center;
}

.btn {
  width: 9.5rem;
  padding: 0.65rem 1.25rem;
  border-radius: 0.625rem;
  font-weight: 600;
  text-decoration: none;
  text-align: center;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.btn-register {
  background-color: #0f172a;
  color: #ffffff;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-register:hover {
  background-color: #1e293b;
}

.btn-login {
  background-color: #ffffff;
  border: 2px solid #0f172a;
  color: #0f172a;
}

.btn-login:hover {
  background-color: #f1f5f9;
}

/* BOTTOM SECTION */
.bottom-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  width: 100%;
  z-index: 10;
}

.decor-wrapper, .logo-wrapper {
  width: 7.5rem;
  height: 7.5rem;
  display: flex;
  align-items: flex-end;
}

.logo-wrapper {
  justify-content: flex-end;
}

.img-decor, .img-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  pointer-events: none;
}
</style>