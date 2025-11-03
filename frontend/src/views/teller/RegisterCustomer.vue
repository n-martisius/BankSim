<template>
  <div class="dashboard-container teller-theme">
    <h1>Register Customer</h1>

    <form @submit.prevent="registerCustomer" class="dashboard-card">
      <label>Name:</label>
      <input v-model="name" type="text" required class="input-field"/>
      <label>Email:</label>
      <input v-model="email" type="email" required class="input-field"/>
      <label>Initial Balance:</label>
      <input v-model.number="balance" type="number" required class="input-field"/>
      <button class="dashboard-btn" style="background:#34d399">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const email = ref('')
const balance = ref(0)

const registerCustomer = () => {
  alert(`Customer ${name.value} registered with $${balance.value}`)
  router.push('/dashboard/teller')
}
</script>

<style scoped>
@import '../../style.css';
</style>
