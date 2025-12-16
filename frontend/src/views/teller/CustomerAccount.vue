<template>
  <div class="dashboard-container customer-theme">
    <h1>Customer Dashboard</h1>

    <div v-if="loading">Loading accounts...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else>
      <div
        v-for="account in customerAccounts"
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

        <div class="account-buttons">
          <button @click="goToTransactions(account)" class="btn-primary">
            Transfer / Transactions
          </button>
          <button @click="closeAccount(account)" class="btn-danger">
            Close Account
          </button>
        </div>
      </div>
<!-- Close customer button -->
<button
  @click="closeCustomer"
  class="btn-danger"
  style="margin-top: 1rem;"
>
  Close Customer
</button>
      <div v-if="customerAccounts.length === 0" class="error">
        No accounts found for this customer.
      </div>
    </div>
    
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../plugins/axios'

const route = useRoute()
const router = useRouter()

const customerId = Number(route.params.id)

const accounts = ref([])
const loading = ref(true)
const error = ref('')

const customerAccounts = computed(() =>
  accounts.value.filter(acc => acc.user_id === customerId && acc.status !== 'closed')
)

const fetchAccounts = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/accounts')
    accounts.value = Array.isArray(response.data) ? response.data : []
  } catch (err) {
    console.error(err)
    error.value = 'Failed to load account data'
  } finally {
    loading.value = false
  }
}

const formatBalance = (amount) =>
  new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'EUR' }).format(amount)

const goToTransactions = (account) => {
  router.push({ 
    name: 'TransferFunds', 
    params: { 'account': account.id } 
  })
}


// Close account
const closeAccount = async (account) => {
  if (!confirm(`Are you sure you want to close account ${account.number}?`)) return

  try {
    // Close the account first
    await api.put(`/accounts/${account.id}`, { status: 'closed' })

    // Update local state
    accounts.value = accounts.value.map(acc =>
      acc.id === account.id ? { ...acc, status: 'closed' } : acc
    )

    alert(`Account ${account.number} closed successfully`)

    // Check if this was the last active account
    const remaining = customerAccounts.value.filter(acc => acc.id !== account.id)
    if (remaining.length === 0) {
      // No more active accounts → close customer
      await api.put(`/users/${customerId}`, { status: 'closed' })
      alert('Customer has no active accounts and was closed')
    }

  } catch (err) {
    console.error(err)
    alert('Failed to close account')
  }
}
// Close the customer even if they have no accounts
const closeCustomer = async () => {
  if (!confirm('Are you sure you want to close this customer?')) return

  try {
    // Optional: warn if customer has active accounts
    const activeAccounts = customerAccounts.value
    if (activeAccounts.length > 0) {
      if (!confirm('This customer has active accounts. Closing will not affect accounts. Continue?')) {
        return
      }
    }

    await api.put(`/users/${customerId}`, { status: 'closed' })
    alert('Customer closed successfully')
    router.push('/teller/customer-search')

  } catch (err) {
    console.error(err)
    alert('Failed to close customer')
  }
}

onMounted(fetchAccounts)
</script>


<style scoped>
@import '../../style.css';
</style>
