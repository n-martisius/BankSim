import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import AdminDashboard from '../views/dashboards/AdminDashboard.vue'
import TellerDashboard from '../views/dashboards/TellerDashboard.vue'
import CustomerDashboard from '../views/dashboards/CustomerDashboard.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/dashboard/admin', component: AdminDashboard },
  { path: '/dashboard/teller', component: TellerDashboard },
  { path: '/dashboard/customer', component: CustomerDashboard }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
