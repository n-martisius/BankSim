<template>
  <div class="dashboard-container teller-theme">
    <h1>Transfer Funds</h1>

    <div class="dashboard-card">
      <form @submit.prevent="submitTransfer" class="transfer-form">
        <label>From Customer ID:</label>
        <input v-model="fromId" type="number" required class="input-field" placeholder="Enter sender ID"/>

        <label>To Customer ID:</label>
        <input v-model="toId" type="number" required class="input-field" placeholder="Enter receiver ID"/>

        <label>Amount:</label>
        <input v-model.number="amount" type="number" required class="input-field" placeholder="Enter amount"/>

        <label>Notes (optional):</label>
        <textarea v-model="notes" class="input-field" placeholder="Enter notes"></textarea>

        <button type="submit" class="dashboard-btn" style="background:#34d399">Transfer</button>
      </form>
    </div>

    <div v-if="successMessage" class="dashboard-card success-message">
      {{ successMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const fromId = ref('')
const toId = ref('')
const amount = ref(0)
const notes = ref('')
const successMessage = ref('')

const submitTransfer = () => {
  if (fromId.value === toId.value) {
    alert('Sender and receiver cannot be the same!')
    return
  }
  if (amount.value <= 0) {
    alert('Amount must be greater than zero')
    return
  }

  // Demo success
  successMessage.value = `Successfully transferred $${amount.value.toFixed(2)} from customer ${fromId.value} to ${toId.value}.`
  
  // Reset form fields
  fromId.value = ''
  toId.value = ''
  amount.value = 0
  notes.value = ''
}
</script>

<style scoped>
@import '../../style.css';
</style>
