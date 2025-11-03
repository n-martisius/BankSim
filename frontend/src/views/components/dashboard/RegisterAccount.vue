<template>
  <div class="card">
    <h2>Register {{ type === 'teller' ? 'Teller' : 'Customer' }} Account</h2>
    <form @submit.prevent="register">
      <input type="text" v-model="name" placeholder="Full Name" required />
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  props: ['type'],
  setup() {
    const name = ref('')
    const register = () => {
      alert(`Registered new ${name.value}! (Demo only)`)
      name.value = ''
    }
    return { name, register }
  }
}
</script>
