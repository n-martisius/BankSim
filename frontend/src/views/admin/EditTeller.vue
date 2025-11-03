<template>
  <div class="dashboard-container admin-theme">
    <h1>Edit Teller</h1>

    <form @submit.prevent="updateTeller" class="dashboard-card">
      <label>Name:</label>
      <input v-model="name" type="text" required class="input-field"/>
      <label>Email:</label>
      <input v-model="email" type="email" required class="input-field"/>
      <button class="dashboard-btn" style="background:#60a5fa">Update</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

// dummy data for demo
const allTellers = [
  { id: 1, name: 'Alice Johnson', email: 'alice@bank.com' },
  { id: 2, name: 'Bob Smith', email: 'bob@bank.com' },
  { id: 3, name: 'Carol White', email: 'carol@bank.com' },
]

const route = useRoute()
const router = useRouter()
const name = ref('')
const email = ref('')

onMounted(() => {
  const teller = allTellers.find(t => t.id === Number(route.params.id))
  if (teller) {
    name.value = teller.name
    email.value = teller.email
  }
})

const updateTeller = () => {
  alert(`Teller ${name.value} updated!`)
  router.push('/admin/manage-tellers')
}
</script>


<style scoped>
@import '../../style.css';
</style>
