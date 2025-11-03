<template>
  <main class="login-page">
    <div class="login-card">
      <h1>Login</h1>
      <form @submit.prevent="login">
        <input type="text" v-model="username" placeholder="Username" required />
        <select v-model="role" required>
          <option disabled value="">Select Role</option>
          <option value="admin">Admin</option>
          <option value="teller">Teller</option>
          <option value="customer">Customer</option>
        </select>
        <button type="submit">Log In</button>
      </form>
    </div>
  </main>
</template>

<script>
import { ref } from 'vue'
import { store } from '../store'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const username = ref('')
    const role = ref('')
    const router = useRouter()

    const login = () => {
      if (!username.value.trim() || !role.value) {
        alert('Please enter a username and select a role.')
        return
      }

      store.login(username.value, role.value)
      router.push(`/dashboard/${role.value}`)
    }

    return { username, role, login }
  }
}
</script>
