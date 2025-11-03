<template>
  <div class="dashboard-container admin-theme">
    <h1>Manage Tellers</h1>

    <TellerList :tellers="tellers" @edit="editTeller" @delete="deleteTeller"/>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import TellerList from '../components/admin/TellerList.vue' 

// example dummy data
const tellers = ref([
  { id: 1, name: 'Alice Johnson', email: 'alice@bank.com' },
  { id: 2, name: 'Bob Smith', email: 'bob@bank.com' },
  { id: 3, name: 'Carol White', email: 'carol@bank.com' },
])

const router = useRouter()

const editTeller = (teller) => {
  router.push({ name: 'EditTeller', params: { id: teller.id } })
}

const deleteTeller = (teller) => {
  if (confirm(`Are you sure you want to delete ${teller.name}?`)) {
    tellers.value = tellers.value.filter(t => t.id !== teller.id)
  }
}
</script>


<style scoped>
@import '../../style.css';
</style>
