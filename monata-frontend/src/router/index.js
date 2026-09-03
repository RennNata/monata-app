import { createRouter, createWebHistory } from 'vue-router'

import LandingPageView from '../views/LandingPageView.vue'

// Public
import HomeView from '../views/Public/HomeView.vue'

// Auth
import LoginView from '../views/Auth/LoginView.vue'
import RegisterView from '../views/Auth/RegisterView.vue'

// Admin
import DashboardView from '../views/Admin/DashboardView.vue'

const routes = [
  { path: '/', component: LandingPageView, name: 'landing-page' },

  { path: '/home-page', component: HomeView, name: 'home-page  ' },

  { path: '/login', component: LoginView, name: 'login' },
  { path: '/register', component: RegisterView, name: 'register' },
  { 
    path: '/dashboard', component: DashboardView, meta: { requiresAuth: true } } // Tandai halaman butuh login
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Penjaga Rute (Route Guard)
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    next('/login') // Kalau mau masuk dashboard tapi tida ada token, lempar ke login
  } else {
    next()
  }
})

export default router