<template>
  <main class="login-page">
    <div class="login-card">
      <h1>Login</h1>
      <form @submit.prevent="login">
        <input type="text" v-model="username" placeholder="Username" required />
        <input type="password" v-model="password" placeholder="Password" required />
        <button type="submit">Log In</button>
      </form>
    </div>
  </main>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Login',
  setup() {
    const username = ref('')
    const password = ref('')
    const router = useRouter()

    const login = () => {
      // Demo: just redirect after "login"
      alert(`Logging in as ${username.value}`)
      router.push('/')  // redirect to home
    }

    return { username, password, login }
  }
}
</script>
