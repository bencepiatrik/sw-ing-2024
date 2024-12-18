<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <div id="app">
    <router-view />
  </div>
</template>

<style>
body{
  background-color: #D9DCD6;
}
</style>
