<template>
  <!-- router-view ini yang akan berubah-ubah jadi Home atau Dashboard -->
  <router-view />
</template>

<style>
/* Taruh warna dasar di sini biar bisa dipakai di semua komponen */
:root {
  --color-primary: #3b82f6;
  --color-primary-dark: #2563eb;
  --color-dark: #1f2937;
  --color-bg-panel: #f8fafc;
  --color-dot: #e2e8f0;
  --color-yellow: #fef08a;
  --radius-lg: 24px;
  --radius-md: 16px;
  --shadow-card: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}
</style>