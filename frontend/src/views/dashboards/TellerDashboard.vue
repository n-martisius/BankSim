<template>
  <div class="dashboard-container teller-theme">
    <h1>Teller Dashboard</h1>

    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h2>Account Management</h2>
        <button class="dashboard-btn" style="background:#34d399">Register Customer</button>
        <button class="dashboard-btn" style="background:#f87171">Close Account</button>
      </div>

      <div class="dashboard-card">
        <h2>Perform Transaction</h2>
        <button class="dashboard-btn" style="background:#60a5fa">New Transaction</button>
      </div>

      <div class="dashboard-card">
        <h2>Recent Cases</h2>
        <ul>
          <li>✔ Closed Account: #3256</li>
          <li>💸 Processed Deposit: $500</li>
          <li>📄 Opened Account: John Doe</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import '../../main.css'
</script>
