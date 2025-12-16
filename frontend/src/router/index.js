import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import AdminDashboard from '../views/dashboards/AdminDashboard.vue'
import TellerDashboard from '../views/dashboards/TellerDashboard.vue'
import CustomerDashboard from '../views/dashboards/CustomerDashboard.vue'
import EditTeller from '../views/admin/EditTeller.vue'
import ManageTellers from '../views/admin/ManageTellers.vue'
import RegisterTeller from '../views/admin/RegisterTeller.vue'
// Teller Views
import RegisterCustomer from '../views/teller/RegisterCustomer.vue'
import CustomerSearch from '../views/teller/CustomerSearch.vue'
import CustomerAccount from '../views/teller/CustomerAccount.vue'
import TransferFunds from '../views/teller/TransferFunds.vue'
import Profile from '../views/Profile.vue'

import { store } from '../store' // your store


const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },

  { path: '/dashboard/admin', component: AdminDashboard, meta: { roles: ['admin'] } },
  { path: '/dashboard/teller', component: TellerDashboard, meta: { roles: ['teller'] } },
  { path: '/dashboard/customer', component: CustomerDashboard, meta: { roles: ['customer'] } },

  { path: '/admin/manage-tellers', component: ManageTellers, meta: { roles: ['admin'] } },
  { path: '/admin/register-teller', component: RegisterTeller, meta: { roles: ['admin'] } },
  { path: '/admin/edit-teller/:id', component: EditTeller, meta: { roles: ['admin'] } },

  { path: '/teller/register-customer', component: RegisterCustomer, meta: { roles: ['teller'] } },
  { path: '/teller/customer-search', component: CustomerSearch, meta: { roles: ['teller'] } },
  { path: '/teller/customer-account/:id', name: 'CustomerAccount', component: CustomerAccount, meta: { roles: ['teller'] } },
 { 
  path: '/teller/transfer-funds/:account', 
  name: 'TransferFunds',   // <--- add this
  component: TransferFunds, 
  meta: { roles: ['teller'] } 
},

  { path: '/profile', component: Profile, meta: { roles: ['admin', 'teller', 'customer'] } },
]


const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const user = store.state.user
  const isLoggedIn = !!user

  // If route has no meta.roles, allow everyone
  if (!to.meta.roles) {
    return next()
  }

  // Redirect if not logged in
  if (!isLoggedIn) {
    return next('/login')
  }

  // Redirect if user role is not allowed
  if (!to.meta.roles.includes(user.role)) {
    alert('You do not have permission to access this page.')
    return next(false) // cancel navigation
  }

  next()
})


export default router
