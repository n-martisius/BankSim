<template>
  <div class="dashboard-container teller-theme">
    <h1>Teller Dashboard</h1>

    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h2>Recent Cases</h2>
        <ul>
          <li>✔ Closed Account: #3256</li>
          <li>💸 Processed Deposit: $500</li>
          <li>📄 Opened Account: John Doe</li>
        </ul>
      </div>
          <div class="dashboard-card">
        <h2>Register Customer</h2>
        <button class="dashboard-btn" @click="goRegisterCustomer" style="background:#34d399">Go</button>
      </div>

      <div class="dashboard-card">
        <h2>Manage Customer Accounts</h2>
        <button class="dashboard-btn" @click="goCustomerSearch" style="background:#60a5fa">Go</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import '../../main.css'
import { useRouter } from 'vue-router'
const router = useRouter()

const goRegisterCustomer = () => router.push('/teller/register-customer')
const goCustomerSearch = () => router.push('/teller/customer-search')
</script>
