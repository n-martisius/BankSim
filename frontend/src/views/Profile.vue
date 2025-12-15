<template>
  <div class="profile-page">
    <h1>My Profile</h1>

    <form @submit.prevent="updateProfile" class="profile-form">
      <div class="form-group">
        <label>Email</label>
        <input type="email" v-model="email" required />
      </div>

      <div class="form-group">
        <label>Phone</label>
        <input type="text" v-model="phone" required />
      </div>

      <div class="form-group">
        <label>New Password</label>
        <input type="password" v-model="password" placeholder="Leave blank to keep current password" />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Saving...' : 'Update Profile' }}
      </button>
    </form>

    <p v-if="success" class="success">{{ success }}</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../plugins/axios'
import { store } from '../store'
import { useRouter } from 'vue-router'

const email = ref(store.state.user.email)
const phone = ref(store.state.user.phone || '')
const password = ref('')

const loading = ref(false)
const success = ref('')
const error = ref('')
const router = useRouter()

const updateProfile = async () => {
  loading.value = true
  success.value = ''
  error.value = ''

  try {
    const payload = {
      email: email.value,
      phone: phone.value
    }

    // Only include password if user entered something
    if (password.value.trim()) {
      payload.password = password.value
    }

    const userId = store.state.user.id
    const response = await api.put(`/users/self`, payload)

    // Update store with new user info
    store.state.user = response.data

    success.value = 'Profile updated successfully!'
    password.value = '' // clear password field

    store.logout()
    router.push('/login')

  } catch (err) {
    console.error(err)
    if (err.response && err.response.data) {
      error.value = err.response.data.message || 'Failed to update profile'
    } else {
      error.value = 'Network error'
    }
  } finally {

    loading.value = false
  }
}
</script>
<style scoped> @import '../style.css'; </style>