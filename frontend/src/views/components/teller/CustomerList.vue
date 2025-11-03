<template>
  <table class="teller-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Balance</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="customer in customers" :key="customer.id">
        <td>{{ customer.id }}</td>
        <td>{{ customer.name }}</td>
        <td>{{ customer.email }}</td>
        <td>${{ customer.balance.toFixed(2) }}</td>
        <td>
          <button class="dashboard-btn" style="background:#34d399" @click="$emit('open', customer)">Open</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
defineProps({ customers: Array })
</script>

<style scoped>
@import '../../../style.css';
</style>
