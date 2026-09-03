import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DashboardView from '../views/DashboardView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'

const routes = [
  { path: '/', component: HomeView },
  { path: '/login', component: LoginView },
  { path: '/register', component: RegisterView },
  { 
    path: '/dashboard', 
    component: DashboardView, 
    meta: { requiresAuth: true } // Tandai halaman butuh login
  }
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