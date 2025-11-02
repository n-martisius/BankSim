<template>
  <header class="header">
    <div class="logo">
      <router-link to="/">Dashboard</router-link>
    </div>
    <div class="user-section">
      <span class="greeting">Hello, <strong>{{ userName }}</strong></span>
      <button v-if="!isLoggedIn" @click="goLogin">Login</button>
      <button v-else @click="logout">Logout</button>
    </div>
  </header>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Header',
  setup() {
    const userName = ref('Guest')
    const isLoggedIn = ref(false)
    const router = useRouter()

    const goLogin = () => {
      router.push('/login')
    }

    const logout = () => {
      isLoggedIn.value = false
      userName.value = 'Guest'
    }

    return { userName, isLoggedIn, goLogin, logout }
  }
}
</script>
