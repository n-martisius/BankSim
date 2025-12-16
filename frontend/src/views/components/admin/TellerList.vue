<template>
  <div class="teller-table-container">
    <h2>Manage Tellers</h2>

    <table class="teller-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="teller in tellers" :key="teller.id">
          <td>{{ teller.id }}</td>
          <td>{{ teller.full_name }}</td>
          <td>{{ teller.email }}</td>
          <td>
            <button
              class="dashboard-btn edit-btn"
              @click="editTeller(teller)"
            >
              Edit
            </button>

            <button
              class="dashboard-btn delete-btn"
              @click="deleteTeller(teller)"
            >
              Delete
            </button>
          </td>
        </tr>

        <tr v-if="tellers.length === 0">
          <td colspan="4" style="text-align:center; padding:1rem;">
            No tellers found
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../../plugins/axios' 

const users = ref([])

const tellers = computed(() =>
  users.value.filter(user => user.role === 'teller')
)

onMounted(async () => {
  try {
    const response = await api.get('/users', {
      withCredentials: true
    })

    users.value = response.data
  } catch (err) {
    console.error('Failed to fetch users', err)
  }
})

const editTeller = (teller) => {
  console.log('Edit teller:', teller)
  router.push(`/admin/edit-teller/${teller.id}`)
}

const deleteTeller = async (teller) => {
  if (!confirm(`Delete teller ${teller.full_name}?`)) return

  try {
    await api.delete(`/users/${teller.id}`, {
      withCredentials: true
    })

    users.value = users.value.filter(u => u.id !== teller.id)
  } catch (err) {
    console.error('Failed to delete teller', err)
  }
}
</script>

<style scoped>
@import '../../../style.css';
</style>
