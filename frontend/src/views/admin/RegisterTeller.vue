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

const fullName = ref('')
const name = ref('')
const email = ref('')
const password = ref('')

const loading = ref(false)
const success = ref('')
const error = ref('')

const router = useRouter()

const registerTeller = async () => {
  loading.value = true
  success.value = ''
  error.value = ''

  try {
    // 🔹 Sanctum-protected API route
    const response = await api.post(
      '/users',
      {
        name: name.value,
        full_name: fullName.value,
        email: email.value,
        password: password.value,
        role: 'teller'
      },
      {
        withCredentials: true 
      }
    )

    success.value = `Teller ${response.data.full_name} registered successfully`

    setTimeout(() => {
      router.push('/admin/manage-tellers')
    }, 1000)

  } catch (err) {
    console.error(err)

    if (err.response?.data?.message) {
      error.value = err.response.data.message
    } else if (err.response?.data?.errors) {
      // Laravel validation errors
      error.value = Object.values(err.response.data.errors)[0][0]
    } else {
      error.value = 'Failed to register teller'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '../../style.css';

</style>
