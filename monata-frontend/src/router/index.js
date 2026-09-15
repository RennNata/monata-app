import { createRouter, createWebHistory } from 'vue-router'

import LandingPageView from '../views/LandingPageView.vue'

// Public
import HomeView from '../views/Public/HomeView.vue'

// Auth
import LoginView from '../views/Auth/LoginView.vue'
import RegisterView from '../views/Auth/RegisterView.vue'

// Admin
import AdminLayout from '../views/Admin/AdminLayout.vue' 
import DashboardView from '../views/Admin/DashboardView.vue'

import KelolaKategori from '../views/Admin/KelolaKategori.vue'
import KelolaProduk from '../views/Admin/KelolaProduk.vue'
// 1. Import komponen KelolaTransaksi dari folder Admin
import KelolaTransaksi from '../views/Admin/KelolaTransaksi.vue'

const routes = [
  { path: '/', component: LandingPageView, name: 'landing-page' },
  { path: '/home-page', component: HomeView, name: 'home-page' },
  { path: '/login', component: LoginView, name: 'login' },
  { path: '/register', component: RegisterView, name: 'register' },
  
  // Grouping khusus Admin (Nested Routes)
  { path: '/dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/dashboard/categories', component: KelolaKategori, meta: { requiresAuth: true } },
  { path: '/dashboard/products', component: KelolaProduk, meta: { requiresAuth: true } },
  
  // 2. Tambahkan route /dashboard/transaksi di sini
  { path: '/dashboard/transaksi', component: KelolaTransaksi, meta: { requiresAuth: true } }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Route Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    next('/login')
  } else {
    next()
  }
})

export default router