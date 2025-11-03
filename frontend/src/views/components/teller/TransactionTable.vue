<template>
  <table class="teller-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(t, index) in transactions" :key="index">
        <td>{{ t.date }}</td>
        <td>{{ t.type }}</td>
        <td :class="{'text-accent-green': t.type==='Deposit', 'text-accent-red': t.type==='Withdrawal'}">
          {{ t.type === 'Deposit' ? '+' : '-' }} ${{ t.amount.toFixed(2) }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
defineProps({
  transactions: Array
})
</script>

<style scoped>
@import '../../../style.css';
</style>
