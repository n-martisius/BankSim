<template>
  <div class="dashboard-container admin-theme">
    <h1>Admin Dashboard</h1>

    <div class="dashboard-grid" @click="goManageTellers">
      <div class="dashboard-card">
  <h2>Total Tellers</h2>
  <p class="text-accent-green text-3xl">{{ totalTellers }}</p>
</div>

<div class="dashboard-card">
  <h2>Customer Accounts</h2>
  <p class="text-accent-blue text-3xl">{{ totalCustomers }}</p>
</div>
<div class="dashboard-card">
  <h2>Transactions</h2>
  <p class="text-accent-purple text-3xl">{{ totalTransactions }}</p>
</div>
    </div>

     <div class="chart-wrapper">
      <h2>Overview Chart</h2>
    <canvas id="adminChart" height="100"></canvas>
    <br>
    <br>
  </div>
<div class="dashboard-grid">
    <div class="dashboard-card">
        <h2>Manage Tellers</h2>
        <button class="dashboard-btn" @click="goManageTellers" style="background:#60a5fa">Go</button>
      </div>
      <div class="dashboard-card">
        <h2>Register Teller</h2>
        <button class="dashboard-btn" @click="goRegisterTeller" style="background:#34d399">Go</button>
      </div>
</div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, h } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../plugins/axios.js'
import Chart from 'chart.js/auto'
import '../../main.css'

const router = useRouter()

const users = ref([])
const transactions = ref([])

const totalTellers = computed(() =>
  users.value.filter(u => u.role === 'teller').length
)

const totalCustomers = computed(() =>
  users.value.filter(u => u.role === 'customer').length
)
const totalTransactions = computed(() => transactions.value.length)

const goManageTellers = () => router.push('/admin/manage-tellers')
const goRegisterTeller = () => router.push('/admin/register-teller')

onMounted(async () => {
  // Fetch users
  const response = await api.get('/users', {
    withCredentials: true
  })

  users.value = response.data

  const transactionResponse = await api.get('/transactions', {
    withCredentials: true
  })

  transactions.value = transactionResponse.data

  // Chart
  const ctx = document.getElementById('adminChart')
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Tellers', 'Customers', 'Transactions'],
      datasets: [{
        label: 'Total Count',
        data: [totalTellers.value, totalCustomers.value, totalTransactions.value],
        backgroundColor: ['#34d399', '#60a5fa', '#a78bfa']
      }]
    },
    options: {
      plugins: {
        legend: { display: false }
      }
    }
  })
})
</script>
