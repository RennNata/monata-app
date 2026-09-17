import { createRouter, createWebHistory } from 'vue-router'

import LandingPageView from '../views/LandingPageView.vue'

// Public
import HomeView from '../views/Public/HomeView.vue'
import KatalogView from '../views/Public/KatalogView.vue'
import ProfileView from '../views/Public/ProfileView.vue'

// Auth
import LoginView from '../views/Auth/LoginView.vue'
import RegisterView from '../views/Auth/RegisterView.vue'

// Admin
import DashboardView from '../views/Admin/DashboardView.vue'

import KelolaKategori from '../views/Admin/KelolaKategori.vue'
import KelolaProduk from '../views/Admin/KelolaProduk.vue'
import KelolaTransaksi from '../views/Admin/KelolaTransaksi.vue'
import KelolaAdmin from '../views/Admin/KelolaAdmin.vue'

const routes = [
  { path: '/', component: LandingPageView, name: 'landing-page' },
  { path: '/home', component: HomeView, name: 'home' },
  { path: '/login', component: LoginView, name: 'login' },
  { path: '/register', component: RegisterView, name: 'register' },
  { path: '/katalog', component: KatalogView, name: 'katalog' },
  { path: '/profile', component: ProfileView, name: 'profile' },

  // admin
  { path: '/dashboard', component: DashboardView, meta: { requiresAuth: true, roles: ['admin', 'super_admin'] } },
  { path: '/dashboard/categories', component: KelolaKategori, meta: { requiresAuth: true, roles: ['admin', 'super_admin'] } },
  { path: '/dashboard/products', component: KelolaProduk, meta: { requiresAuth: true, roles: ['admin', 'super_admin'] } },
  { path: '/dashboard/admins', component: KelolaAdmin, meta: { requiresAuth: true, roles: ['admin', 'super_admin'] } },
  // 2. Tambahkan route /dashboard/transaksi di sini
  { path: '/dashboard/transaksi', component: KelolaTransaksi, meta: { requiresAuth: true, roles: ['admin', 'super_admin'] } }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Route Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const role = localStorage.getItem('role')

  if (to.meta.roles && !to.meta.roles.includes(role)) {
    // Jika role tidak sesuai, redirect ke halaman home atau halaman lain yang sesuai
    next('/home')
  } else if (to.meta.requiresAuth && !token) {
    // Jika route membutuhkan autentikasi tapi token tidak ada, redirect ke login
    next('/login')
  } else {
    next()
  }
})

export default router