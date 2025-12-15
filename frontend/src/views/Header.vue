<template>
  <header class="header">
    <div class="logo" @click="goDashboard">
      <span class="dashboard-link">Dashboard</span>
    </div>

    <div class="user-section">
      <span class="greeting">Hello, <strong>{{ store.state.name }}</strong></span>
      <button v-if="store.isLoggedIn" @click="goProfile">Profile</button>
      <button v-if="!store.isLoggedIn" @click="goLogin">Login</button>
      <button v-else @click="goLogout">Logout</button>
    </div>
  </header>
</template>

<script>
import { store } from '../store'
import { useRouter } from 'vue-router'

export default {
  name: 'Header',
  setup() {
    const router = useRouter()

    const goLogin = () => {
      router.push('/login')
    }

    const goProfile = () => {
      router.push('/profile')
    }

    const goLogout = () => {
      router.push('/')
            store.logout()
    }

    const goDashboard = () => {
      if (!store.isLoggedIn) {
        router.push('/')
      } else {
        switch (store.state.role) {
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
            router.push('/')
        }
      }
    }

    return { store, goLogin, goLogout, goDashboard, goProfile }
  }
}
</script>