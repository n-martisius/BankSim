<template>
  <div class="dashboard-container admin-theme">
    <h1>Register Teller</h1>

    <form @submit.prevent="registerTeller" class="dashboard-card">
      <label>Name:</label>
      <input v-model="name" type="text" required class="input-field"/>
      <label>Email:</label>
      <input v-model="email" type="email" required class="input-field"/>
      <button class="dashboard-btn" style="background:#34d399">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const router = useRouter()

const registerTeller = () => {
  // placeholder logic; in a real app, send data to backend
  alert(`Teller ${name.value} registered!`)
  router.push('/admin/manage-tellers')
}
</script>

<style scoped>
@import '../../style.css';
</style>
