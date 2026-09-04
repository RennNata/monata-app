import { createRouter, createWebHistory } from 'vue-router'

import LandingPageView from '../views/LandingPageView.vue'

// Public
import HomeView from '../views/Public/HomeView.vue'

// Auth
import LoginView from '../views/Auth/LoginView.vue'
import RegisterView from '../views/Auth/RegisterView.vue'

// Admin
// PERBAIKAN: Tambahkan folder /Admin/ pada path import di bawah ini
import AdminLayout from '../views/Admin/AdminLayout.vue' 
import DashboardView from '../views/Admin/DashboardView.vue'

const routes = [
  { path: '/', component: LandingPageView, name: 'landing-page' },
  { path: '/home-page', component: HomeView, name: 'home-page' },
  { path: '/login', component: LoginView, name: 'login' },
  { path: '/register', component: RegisterView, name: 'register' },
  
  // Grouping khusus Admin (Nested Routes)
  { 
    path: '/admin', 
    component: AdminLayout, // Layout Sidebar & Topbar
    meta: { requiresAuth: true },
    children: [
      {
        path: 'dashboard', // URL menjadi: /admin/dashboard
        component: DashboardView, // Konten dashboard masuk ke dalam layout
        name: 'admin-dashboard'
      }
    ]
  }
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