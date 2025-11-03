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



const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/dashboard/admin', component: AdminDashboard },
  { path: '/dashboard/teller', component: TellerDashboard },
  { path: '/dashboard/customer', component: CustomerDashboard },
  {
    path: '/admin/manage-tellers',
    component: ManageTellers
  },
  {
    path: '/admin/register-teller',
    component: RegisterTeller
  },
  {
    path: '/admin/edit-teller/:id',
    name: 'EditTeller',
    component: EditTeller
  },
  { path: '/teller/register-customer', component: RegisterCustomer },
  { path: '/teller/customer-search', component: CustomerSearch },
  { path: '/teller/customer-account/:id', name: 'CustomerAccount', component: CustomerAccount },
  { path: '/teller/transfer-funds', component: TransferFunds }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
