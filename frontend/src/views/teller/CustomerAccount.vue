<template>
  <div v-if="customer" class="dashboard-container teller-theme">
    <h1>Customer Account: {{ customer.name }}</h1>

    <div class="dashboard-card">
      <h2>Account Details</h2>
      <p>Email: {{ customer.email }}</p>
      <p>Balance: ${{ customer.balance.toFixed(2) }}</p>
    </div>

    <div class="dashboard-card">
      <h2>Transactions</h2>
      <TransactionTable :transactions="transactions"/>
    </div>

    <div class="dashboard-card">
      <button class="dashboard-btn" @click="deposit">Deposit</button>
      <button class="dashboard-btn" @click="withdraw">Withdraw</button>
      <button class="dashboard-btn" @click="transfer">Transfer Funds</button>
      <button class="dashboard-btn" style="background:#facc15" @click="closeAccount">Close Account</button>
    </div>
  </div>

  <!-- Optional: loading state -->
  <div v-else class="dashboard-container teller-theme">
    <p>Loading customer data...</p>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import TransactionTable from '../components/teller/TransactionTable.vue'

// dummy customer data
const allCustomers = [
  { id: 1, name: 'John Doe', email: 'john@bank.com', balance: 1200 },
  { id: 2, name: 'Jane Smith', email: 'jane@bank.com', balance: 450 },
]

const allTransactions = {
  1: [
    { date: '2025-11-01', type: 'Deposit', amount: 200 },
    { date: '2025-11-02', type: 'Withdrawal', amount: 50 },
  ],
  2: [
    { date: '2025-11-01', type: 'Deposit', amount: 100 },
  ]
}

const route = useRoute()
const router = useRouter()
const customer = ref(null)
const transactions = ref([])

onMounted(() => {
  const c = allCustomers.find(c => c.id === Number(route.params.id))
  if (c) {
    customer.value = { ...c } // clone to make it reactive
    transactions.value = allTransactions[c.id] || []
  } else {
    alert('Customer not found!')
    router.push('/teller/customer-search')
  }
})

const deposit = () => {
  const amt = parseFloat(prompt('Enter deposit amount:'))
  if (!isNaN(amt) && amt > 0) {
    customer.value.balance += amt
    transactions.value.push({ date: new Date().toISOString().split('T')[0], type: 'Deposit', amount: amt })
  }
}

const withdraw = () => {
  const amt = parseFloat(prompt('Enter withdrawal amount:'))
  if (!isNaN(amt) && amt > 0 && amt <= customer.value.balance) {
    customer.value.balance -= amt
    transactions.value.push({ date: new Date().toISOString().split('T')[0], type: 'Withdrawal', amount: amt })
  }
}

const transfer = () => {
  router.push('/teller/transfer-funds')
}

const closeAccount = () => {
  if (confirm(`Close account for ${customer.value.name}?`)) {
    alert('Account closed (demo)')
    router.push('/teller/customer-search')
  }
}
</script>

<style scoped>
@import '../../style.css';
</style>
