<template>
  <div class="dashboard-container teller-theme">
    <h1>Transaction</h1>

    <div class="dashboard-card">
      <form @submit.prevent="submitTransaction" class="transfer-form">

        <!-- Operation Type -->
        <label>Operation:</label>
        <select v-model="operation" class="input-field" required>
          <option value="transfer">Transfer</option>
          <option value="withdrawal">Withdrawal</option>
          <option value="deposit">Deposit</option>
        </select>

        <!-- From Account IBAN (auto-filled) -->
        <div v-if="operation === 'transfer' || operation === 'withdrawal'">
          <label>From Account (IBAN):</label>
          <input type="text" v-model="fromIban" class="input-field" readonly />
        </div>

        <!-- To Account IBAN -->
        <div v-if="operation === 'transfer' || operation === 'deposit'">
          <label>To Account IBAN:</label>
          <input type="text" v-model="toIban" class="input-field" placeholder="Enter receiver IBAN" required />
        </div>

        <!-- Amount -->
        <label>Amount:</label>
        <input v-model.number="amount" type="number" class="input-field" placeholder="Enter amount" required />

        <!-- Notes -->
        <label>Notes (optional):</label>
        <textarea v-model="notes" class="input-field" placeholder="Enter notes"></textarea>

        <button type="submit" class="dashboard-btn" style="background:#34d399">
          {{ operationLabel }}
        </button>
      </form>
    </div>

    <!-- Feedback Messages -->
    <div v-if="successMessage" class="dashboard-card success-message">{{ successMessage }}</div>
    <div v-if="errorMessage" class="dashboard-card error">{{ errorMessage }}</div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../plugins/axios'

const route = useRoute()

// Form state
const operation = ref('transfer') // transfer, withdrawal, deposit
const fromIban = ref('')          // auto-filled from route account id
const toIban = ref('')            // user input
const amount = ref(0)
const notes = ref('')

// Feedback messages
const successMessage = ref('')
const errorMessage = ref('')

// Get account ID from route
const fromAccountId = Number(route.params['account'])

// Accounts
const accounts = ref([])

// Auto-fetch account IBAN for fromAccountId
onMounted(async () => {
  try {
    const res = await api.get(`/accounts/${fromAccountId}`, { withCredentials: true })
    const acc = res.data
    fromIban.value = acc?.number || ''
  } catch (err) {
    console.error('Failed to fetch from account', err)
    errorMessage.value = 'Failed to load account information.'
  }
})

// Compute button label
const operationLabel = computed(() => {
  switch (operation.value) {
    case 'transfer': return 'Transfer'
    case 'withdrawal': return 'Withdraw'
    case 'deposit': return 'Deposit'
  }
})

// Submit transaction
const submitTransaction = async () => {
  successMessage.value = ''
  errorMessage.value = ''

  if (amount.value <= 0) {
    errorMessage.value = 'Amount must be greater than zero'
    return
  }

  try {
    // Fetch accounts first
    const res = await api.get('/accounts', { withCredentials: true })
    accounts.value = Array.isArray(res.data) ? res.data : []

    let fromAcc = accounts.value.find(acc => acc.id === fromAccountId)
    let toAcc = accounts.value.find(acc => acc.number === toIban.value)
    

    if (operation.value === 'transfer') {
      if (!fromAcc || !toAcc) {
        errorMessage.value = 'Invalid from or to account'
        return
      }

      await api.post('/transfer', {
        type: 'transfer',
        from_account_id: fromAcc.id,
        to_account_id: toAcc.id,
        amount: amount.value,
        details: notes.value
      }, { withCredentials: true })

      successMessage.value = `Transferred €${amount.value.toFixed(2)} from IBAN ${fromIban.value} to IBAN ${toIban.value}`

    } else if (operation.value === 'withdrawal') {
      if (!fromAcc) {
        errorMessage.value = 'Invalid from account'
        return
      }
      if (fromAcc.balance < amount.value) {
        errorMessage.value = 'Insufficient funds'
        return
      }

      await api.post('/transactions', {
        type: 'withdrawal',
        from_account_id: fromAcc.id,
        to_account_id: null,
        amount: amount.value,
        details: notes.value
      }, { withCredentials: true })

      successMessage.value = `Withdrawal of €${amount.value.toFixed(2)} from IBAN ${fromIban.value} successful`

    } else if (operation.value === 'deposit') {
      if (!toAcc) {
        errorMessage.value = 'Invalid destination account IBAN'
        return
      }
      

      await api.post('/transactions', {
        type: 'deposit',
        from_account_id: null,
        to_account_id: toAcc.id,
        amount: amount.value,
        details: notes.value
      }, { withCredentials: true })

      successMessage.value = `Deposit of €${amount.value.toFixed(2)} to IBAN ${toIban.value} successful`
    }

    // Reset form
    if (operation.value === 'transfer') toIban.value = ''
    amount.value = 0
    notes.value = ''

  } catch (err) {
    console.error(err)
    errorMessage.value = err.response?.data?.message || 'Transaction failed'
  }
}
</script>

<style scoped>
@import '../../style.css';
</style>
