<template>
  <main class="home-layout">
    <AppNavbar />
    <HeroSection />
  </main>
</template>

<script setup>
import AppNavbar from '../components/AppNavbar.vue'
import HeroSection from '../components/HeroSection.vue'
</script>

<style scoped>
.home-layout {
  min-height: 100vh;
  background-color: #ffffff;
}
</style>