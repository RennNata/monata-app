<template>
  <header class="navbar">
    <div class="navbar__brand">
      <span class="navbar__dot navbar__dot--blue"></span>
      <span class="navbar__dot navbar__dot--dark"></span>
      <span class="navbar__name">Monata</span>
    </div>

    <nav class="navbar__links">
      <a href="#fitur">Fitur</a>
      <a href="#tentang">Tentang</a>
      <a href="#bantuan">Bantuan</a>
      <!-- Tombol Masuk Dashboard sudah terpasang di sini -->
      <router-link to="/dashboard" class="navbar__signin">Masuk Dashboard</router-link>
    </nav>
  </header>
</template>

<script setup>
// Navbar masih statis dulu, belum butuh data dinamis dari mana pun
</script>

<style scoped>
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 5%; /* Diubah agar proporsional secara full screen */
  max-width: 100%; /* Dihilangkan batas 1280px */
  margin: 0 auto;
  background-color: var(--color-bg-panel); /* Menyesuaikan background hero */
}

.navbar__brand {
  display: flex;
  align-items: center;
  gap: 4px;
}

.navbar__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.navbar__dot--blue {
  background: var(--color-primary);
}

.navbar__dot--dark {
  background: var(--color-dark);
}

.navbar__name {
  margin-left: 8px;
  font-weight: 700;
  font-size: 18px;
}

.navbar__links {
  display: flex;
  gap: 32px;
  align-items: center;
}

.navbar__links a {
  color: #4b5563;
  text-decoration: none;
  font-size: 14px;
}

.navbar__links a:hover {
  color: var(--color-dark);
}

.navbar__actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.navbar__signin {
  color: var(--color-primary) !important;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
}

.navbar__cta {
  background: var(--color-primary);
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.navbar__cta:hover {
  background: var(--color-primary-dark);
}

@media (max-width: 768px) {
  .navbar__links {
    display: none;
  }
}
</style>