<template>
  <div class="dashboard-container admin-theme">
    <h1>Admin Dashboard</h1>

    <div class="dashboard-grid">
      <div class="dashboard-card" @click="goManageTellers">
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
      <br><br>
    </div>
<div class="dashboard-grid">
    <div class="dashboard-card">
      <h2>Recent Logs</h2>
      <table class="logs-table">
        <thead>
          <tr>
            <th>Time</th>
            <th>Event Level</th>
            <th>User</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in recentLogs" :key="log.id">
            <td>{{ formatDate(log.created_at) }}</td>
            <td :class="levelClass(log.event_level)">{{ log.event_level }}</td>
            <td>User ID {{ log.user_id }}</td>
            <td>{{ log.message }}</td>
          </tr>
        </tbody>
      </table>
    </div>
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../plugins/axios.js'
import Chart from 'chart.js/auto'
import '../../main.css'

const router = useRouter()

// Users and transactions
const users = ref([])
const transactions = ref([])

const totalTellers = computed(() =>
  users.value.filter(u => u.role === 'teller').length
)
const totalCustomers = computed(() =>
  users.value.filter(u => u.role === 'customer').length
)
const totalTransactions = computed(() => transactions.value.length)

// Logs
const logs = ref([])
const recentLogs = computed(() => {
  return logs.value
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 10)
})

// Navigation
const goManageTellers = () => router.push('/admin/manage-tellers')
const goRegisterTeller = () => router.push('/admin/register-teller')

const formatDate = (isoString) =>
  new Date(isoString).toLocaleString()

const levelClass = (level) => {
  switch (level) {
    case 'info': return 'level-info'
    case 'warning': return 'level-warning'
    case 'error': return 'level-error'
    default: return ''
  }
}

// Fetch data
onMounted(async () => {
  try {
    // Users
    const userResponse = await api.get('/users', { withCredentials: true })
    users.value = userResponse.data

    // Transactions
    const txResponse = await api.get('/transactions', { withCredentials: true })
    transactions.value = txResponse.data

    // Logs (all)
    const logsResponse = await api.get('/audit-logs', { withCredentials: true })
    logs.value = Array.isArray(logsResponse.data) ? logsResponse.data : []

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
        plugins: { legend: { display: false } }
      }
    })
  } catch (err) {
    console.error('Failed to fetch dashboard data', err)
  }
})
</script>
