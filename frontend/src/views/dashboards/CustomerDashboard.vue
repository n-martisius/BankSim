<template>
  <div class="dashboard-container customer-theme">
    <h1>Welcome, {{ store.userName }}</h1>

    <div class="dashboard-grid mb-10">
      <div class="dashboard-card">
        <h2>Account Number</h2>
        <p>#ACC-584920</p>
      </div>
      <div class="dashboard-card">
        <h2>Current Balance</h2>
        <p class="text-accent-green text-3xl">$2,430.50</p>
      </div>
      <div class="dashboard-card">
        <h2>Last Transaction</h2>
        <p class="text-accent-yellow">- $60.00</p>
      </div>
    </div>

    <div class="dashboard-card">
      <h2>Transaction History</h2>
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>2025-11-01</td><td>Deposit</td><td class="text-accent-green">+$250</td></tr>
          <tr><td>2025-10-30</td><td>Withdrawal</td><td class="text-accent-red">-$60</td></tr>
          <tr><td>2025-10-29</td><td>Transfer</td><td class="text-accent-red">-$150</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { store } from '../../store.js'
import '../../main.css'
</script>
