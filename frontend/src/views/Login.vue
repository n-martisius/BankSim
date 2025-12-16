<template>
  <main class="login-page">
    <div class="login-card">
      <h1>Login</h1>
      <form @submit.prevent="login">
        <input type="email" v-model="email" placeholder="Email" required />
        <input type="password" v-model="password" placeholder="Password" required />
        <button type="submit" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Log In' }}
        </button>
      </form>
      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </main>
</template>

<script>
import { ref } from 'vue'
import { store } from '../store'
import { useRouter } from 'vue-router'
import axios from 'axios'
import api from '../plugins/axios'

export default {
  setup() {
    const email = ref('')
    const password = ref('')
    const error = ref('')
    const loading = ref(false)
    const router = useRouter()

    const login = async () => {
      error.value = ''
      loading.value = true

      try {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });


        const response = await api.post('/auth/login', {
          email: email.value,
          password: password.value
        }, { withCredentials: true })

        const { token, user} = response.data

        // save in store
        store.login(token, user)


        // redirect based on role
        switch (user.role) {
          case 'admin':
            router.push('/dashboard/admin')
            break
          case 'teller':
            router.push('/dashboard/teller')
            break
          case 'customer':
            router.push('/dashboard/customer')
            break
          default:
            router.push('/') // fallback
        }
      } catch (err) {
        if (err.response) {
          error.value = err.response.data.message || 'Login failed'
        } else {
          error.value = 'Network error'
        }
      } finally {
        loading.value = false
      }
    }

    return { email, password, login, loading, error }
  }
}
</script>