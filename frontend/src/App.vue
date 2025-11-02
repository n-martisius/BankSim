<template>
  <div id="app">
    <Header />
    <router-view /> <!-- optional if using Vue Router -->
  </div>
</template>

<script>
import Header from './views/Header.vue'
import Home from './views/Home.vue'

export default {
  components: {
    Header,
    Home
  }
}
</script>
