<template>
  <div class="dashboard-container teller-theme">
    <h1>Teller Dashboard</h1>

    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h2>Recent Logs</h2>
        <div v-if="loading">Loading logs...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <table class="logs-table">
        <thead>
          <tr>
            <th>Time</th>
            <th>Event Level</th>
            <th>Issued By</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id">
            <td>{{ formatDate(log.created_at) }}</td>
            <td :class="levelClass(log.event_level)">{{ log.event_level }}</td>
            <td> User ID {{ log.user_id }}</td>
            <td>{{ log.message }}</td>
          </tr>
        </tbody>
      </table>
    </div>
      </div>
          <div class="dashboard-card">
        <h2>Register Customer User</h2>
        <button class="dashboard-btn" @click="goRegisterCustomer" style="background:#34d399">Go</button>
        <h2>Manage Customer Users</h2>
        <button class="dashboard-btn" @click="goCustomerSearch" style="background:#60a5fa">Go</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import '../../main.css'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { store } from '../../store'
import api from '../../plugins/axios'

const logs = ref([])
const loading = ref(true)
const error = ref('')

const router = useRouter()
const goRegisterCustomer = () => router.push('/teller/register-customer')
const goCustomerSearch = () => router.push('/teller/customer-search')

const fetchLogs = async () => {
  loading.value = true
  error.value = ''
  try {
    const userId = store.state.user.id
    const response = await api.get(`/audit-logs?user_id=${userId}`)
        // Filter out login messages
    logs.value = response.data.filter(log => log.message !== 'User logged in')
  } catch (err) {
    console.error(err)
    error.value = 'Failed to fetch logs'
  } finally {
    loading.value = false
  }
}

const formatDate = (isoString) => {
  const date = new Date(isoString)
  return date.toLocaleString()
}

const levelClass = (level) => {
  switch (level) {
    case 'info': return 'level-info'
    case 'warning': return 'level-warning'
    case 'error': return 'level-error'
    default: return ''
  }
}

onMounted(fetchLogs)
</script>