<template>
  <div class="dashboard-container customer-theme">
    <h1>Customer Dashboard</h1>

    <div v-if="loading">Loading account...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else>
      <div
        v-for="account in accounts"
        :key="account.id"
        class="dashboard-card account-card"
      >
        <h2>Bank Account</h2>
<p><strong>Name:</strong> {{ account.name }}</p>
        <p><strong>IBAN:</strong> {{ account.number }}</p>
        <p><strong>Currency:</strong> {{ account.currency }}</p>

        <div class="balance">
          <span>Current Balance</span>
          <h3>{{ formatBalance(account.balance) }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../plugins/axios'

const accounts = ref([])
const loading = ref(true)
const error = ref('')

const fetchAccounts = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/accounts')
    accounts.value = response.data
  } catch (err) {
    console.error(err)
    error.value = 'Failed to load account data'
  } finally {
    loading.value = false
  }
}

const formatBalance = (amount) => {
  return new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'EUR'
  }).format(amount)
}

onMounted(fetchAccounts)
</script>

