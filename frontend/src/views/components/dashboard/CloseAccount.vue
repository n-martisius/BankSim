<template>
  <div class="card">
    <h2>Close {{ type === 'teller' ? 'Teller' : 'Customer' }} Account</h2>
    <form @submit.prevent="closeAcc">
      <input type="text" v-model="id" placeholder="Account ID" required />
      <button type="submit">Close Account</button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  props: ['type'],
  setup() {
    const id = ref('')
    const closeAcc = () => {
      alert(`Closed account ${id.value}! (Demo only)`)
      id.value = ''
    }
    return { id, closeAcc }
  }
}
</script>
