<template>
  <div class="teller-table-container">
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
          <td>{{ teller.name }}</td>
          <td>{{ teller.email }}</td>
          <td>
            <button class="dashboard-btn edit-btn" @click="$emit('edit', teller)">Edit</button>
            <button class="dashboard-btn delete-btn" @click="$emit('delete', teller)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  tellers: Array
})
</script>

<style scoped>
@import '../../../style.css';
</style>
