<template>
  <div class="register-container teller-theme">
    <h1>Register Customer</h1>

    <form @submit.prevent="registerCustomer" class="dashboard-card">
      <label>Full Name:</label>
      <input v-model="fullName" type="text" required class="input-field"/>

      <label>Username:</label>
      <input v-model="name" type="text" required class="input-field"/>

      <label>Email:</label>
      <input v-model="email" type="email" required class="input-field"/>

      <label>Password:</label>
      <input v-model="password" type="password" required class="input-field"/>

      <button class="dashboard-btn" :disabled="loading" style="background:#34d399">
        {{ loading ? 'Registering...' : 'Register' }}
      </button>
    </form>

    <p v-if="success" class="success">{{ success }}</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../plugins/axios'

const fullName = ref('')
const name = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const loading = ref(false)
const success = ref('')
const error = ref('')

const router = useRouter()

const registerCustomer = async () => {
  loading.value = true
  success.value = ''
  error.value = ''

  try {
    const payload = {
      full_name: fullName.value,  // sending full name as 'name' field for backend
      name: name.value,
      email: email.value,
      phone: phone.value,
      password: password.value,
      role: 'customer'  // set role as 'customer'
    }

    const response = await api.post('/users', payload)

    success.value = `Customer ${response.data.name} registered successfully!`

    // Optional: redirect after 1 second
    setTimeout(() => router.push('/teller/customer-search'), 1000)

  } catch (err) {
    console.error(err)
    if (err.response && err.response.data) {
      error.value = err.response.data.message || 'Failed to register customer'
    } else {
      error.value = 'Network error'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '../../style.css';

.success {
  color: green;
  margin-top: 1rem;
}

.error {
  color: red;
  margin-top: 1rem;
}
</style>