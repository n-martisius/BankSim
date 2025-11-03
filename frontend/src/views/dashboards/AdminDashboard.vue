<template>
  <div class="dashboard-container admin-theme">
    <h1>Admin Dashboard</h1>

    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h2>Total Tellers</h2>
        <p class="text-accent-green text-3xl">14</p>
      </div>
      <div class="dashboard-card">
        <h2>Customer Accounts</h2>
        <p class="text-accent-blue text-3xl">254</p>
      </div>
      <div class="dashboard-card">
        <h2>Open Cases</h2>
        <p class="text-accent-red text-3xl">3</p>
      </div>
    </div>

    <div class="dashboard-card" style="margin-top:2rem;">
      <h2>Activity Overview</h2>
      <canvas id="adminChart" height="100"></canvas>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'
import '../../main.css'

onMounted(() => {
  const ctx = document.getElementById('adminChart')
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Tellers', 'Customers', 'Cases'],
      datasets: [{
        label: 'Total Count',
        data: [12, 45, 3],
        backgroundColor: ['#34d399', '#60a5fa', '#f87171']
      }]
    },
    options: {
      plugins: {
        legend: {
          display: false // hides the legend
        }
      }
    }
  })
})

</script>
