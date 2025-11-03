<template>
  <div class="dashboard-container teller-theme">
    <h1>Search Customer</h1>

    <div class="dashboard-card">
      <label>Customer ID or Email:</label>
      <input v-model="query" type="text" class="input-field"/>
      <button class="dashboard-btn" style="background:#60a5fa" @click="searchCustomer">Search</button>
    </div>

    <CustomerList v-if="results.length" :customers="results" @open="openCustomer"/>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import CustomerList from '../components/teller/CustomerList.vue'

const router = useRouter()
const query = ref('')
const results = ref([])

// Dummy data
const allCustomers = [
  { id: 1, name: 'John Doe', email: 'john@bank.com', balance: 1200 },
  { id: 2, name: 'Jane Smith', email: 'jane@bank.com', balance: 450 },
]

const searchCustomer = () => {
  results.value = allCustomers.filter(c => 
    c.id.toString() === query.value || c.email.includes(query.value)
  )
  if (!results.value.length) alert('No customer found')
}

const openCustomer = (customer) => {
  router.push({ name: 'CustomerAccount', params: { id: customer.id } })
}
</script>

<style scoped>
@import '../../style.css';
</style>
