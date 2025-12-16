<template>
  <div class="dashboard-container admin-theme">
    <h1>Edit Teller</h1>

    <form @submit.prevent="updateTeller" class="dashboard-card">
      <label>Full Name:</label>
      <input v-model="fullName" type="text" required class="input-field"/>

      <label>Username:</label>
      <input v-model="name" type="text" required class="input-field"/>

      <label>Email:</label>
      <input v-model="email" type="email" required class="input-field"/>

      <label>Status:</label>
      <select v-model="status" class="input-field">
        <option value="active">Active</option>
        <option value="suspended">Suspended</option>
        <option value="closed">Closed</option>
      </select>

      <label>Role:</label>
      <input value="Teller" disabled class="input-field disabled"/>

      <button class="dashboard-btn" :disabled="loading" style="background:#60a5fa">
        {{ loading ? 'Updating...' : 'Update Teller' }}
      </button>

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">{{ success }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../plugins/axios'

const route = useRoute()
const router = useRouter()

const fullName = ref('')
const name = ref('')
const email = ref('')
const status = ref('active')

const loading = ref(false)
const error = ref('')
const success = ref('')

const tellerId = route.params.id

// 🔹 Fetch teller from real API
onMounted(async () => {
  try {
    const response = await api.get(`/users/${tellerId}`, {
      withCredentials: true
    })

    const user = response.data

    fullName.value = user.full_name
    name.value = user.name
    email.value = user.email
    status.value = user.status

  } catch (err) {
    console.error(err)
    error.value = 'Failed to load teller'
  }
})

// 🔹 Update teller
const updateTeller = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  try {
    await api.put(
      `/users/${tellerId}`,
      {
        full_name: fullName.value,
        name: name.value,
        email: email.value,
        status: status.value
      },
      {
        withCredentials: true
      }
    )

    success.value = 'Teller updated successfully'

    setTimeout(() => {
      router.push('/admin/manage-tellers')
    }, 800)

  } catch (err) {
    console.error(err)

    if (err.response?.data?.message) {
      error.value = err.response.data.message
    } else if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors)[0][0]
    } else {
      error.value = 'Update failed'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import '../../style.css';

.disabled {
  background: #1f2933;
  color: #9ca3af;
  cursor: not-allowed;
}
</style>
