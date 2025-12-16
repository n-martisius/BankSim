<template>
  <div class="customer-table-container">
    <h2>Manage Customers</h2>

    <table class="customer-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="customer in customers" :key="customer.id">
          <td>{{ customer.id }}</td>
          <td>{{ customer.full_name }}</td>
          <td>{{ customer.email }}</td>
          <td>
            <button
              class="dashboard-btn edit-btn"
              @click="editCustomer(customer)"
            >
              Edit
            </button>

            <button
              class="dashboard-btn delete-btn"
              @click="deleteCustomer(customer)"
            >
              Delete
            </button>
          </td>
        </tr>

        <tr v-if="customers.length === 0">
          <td colspan="4" style="text-align:center; padding:1rem;">
            No customers found
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../plugins/axios' // adjust path if needed

const users = ref([])

const customers = computed(() =>
  users.value.filter(user => user.role === 'customer')
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

const editCustomer = (customer) => {
  console.log('Edit customer:', customer)
  // router.push(`/admin/customers/${customer.id}/edit`)
}

const deleteCustomer = async (customer) => {
  if (!confirm(`Delete customer ${customer.full_name}?`)) return

  try {
    await api.delete(`/users/${customer.id}`, {
      withCredentials: true
    })

    users.value = users.value.filter(u => u.id !== customer.id)
  } catch (err) {
    console.error('Failed to delete customer', err)
  }
}
</script>

<style scoped>
@import '../../../style.css';
</style>
