<template>
  <div class="register-container admin-theme">
    <h1>Register Teller</h1>

    <form @submit.prevent="registerTeller" class="dashboard-card">

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

const name = ref('')
const email = ref('')
const password = ref('')
const loading = ref(false)
const success = ref('')
const error = ref('')
const fullName = ref('')

const router = useRouter()

const registerTeller = async () => {
  loading.value = true
  success.value = ''
  error.value = ''

  try {
    const payload = {
      name: name.value,
      email: email.value,
      password: password.value,
      full_name: fullName.value,
      role: 'teller' // assign teller role
    }

    const response = await api.post('/users', payload)

    success.value = `Teller ${response.data.name} registered successfully!`

    // Optional: redirect after 1 second
    setTimeout(() => router.push('/admin/manage-tellers'), 1000)

  } catch (err) {
    console.error(err)
    if (err.response && err.response.data) {
      error.value = err.response.data.message || 'Failed to register teller'
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

</style>
