<template>
  <div class="dashboard-container teller-theme">
    <h1>Search Customer</h1>

    <div class="dashboard-card">
      <label>Search by ID, Name, or Email:</label>
      <input
        v-model="query"
        type="text"
        class="input-field"
        placeholder="Enter search term"
      />
      <button
        class="dashboard-btn"
        style="background:#60a5fa"
        @click="filterResults"
      >
        Search
      </button>
    </div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="filteredResults.length > 0" class="customer-list">
      <div
        v-for="customer in filteredResults"
        :key="customer.id"
        class="customer-row"
        @click="openCustomer(customer)"
      >
        <strong>{{ customer.name || 'N/A' }}</strong>
        <span>{{ customer.email || 'N/A' }}</span>
        <span>ID: {{ customer.id }}</span>
        <span>Status: {{ customer.status || 'N/A' }}</span>
      </div>
    </div>

    <p v-else-if="allCustomers.length > 0" class="empty">
      No matching customers found.
    </p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../plugins/axios'

const router = useRouter()

const query = ref('')
const allCustomers = ref([])       // full data from backend
const filteredResults = ref([])    // filtered on frontend
const error = ref('')

// fetch all customers on mount
onMounted(async () => {
  error.value = ''
  try {
    const response = await api.get('/users', { params: { role: 'customer' } })
    allCustomers.value = Array.isArray(response.data) ? response.data : response.data?.data || []
    filteredResults.value = [...allCustomers.value]
  } catch (err) {
    console.error(err)
    error.value = 'Failed to load customers'
  }
})

// frontend filtering function
const filterResults = () => {
  const term = query.value.toLowerCase().trim()
  if (!term) {
    filteredResults.value = [...allCustomers.value]
    return
  }

  filteredResults.value = allCustomers.value.filter(c => {
    return (
      String(c.id).includes(term) ||
      (c.name && c.name.toLowerCase().includes(term)) ||
      (c.email && c.email.toLowerCase().includes(term))
    )
  })

  if (filteredResults.value.length === 0) {
    error.value = 'No matching customers found'
  } else {
    error.value = ''
  }
}

// navigate to customer account
const openCustomer = (customer) => {
  router.push(`/teller/customer-account/${customer.id}`)
}
</script>

<style scoped>
@import '../../style.css';
</style>
