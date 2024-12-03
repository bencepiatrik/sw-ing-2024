<script setup lang="ts">
import { ref, onMounted } from 'vue';

// Create a reactive object to store user data
const user = ref({
  id: null,
  name: '',
  email: '',
  email_verified_at: null,
  created_at: '',
  updated_at: '',
});

// Fetch user data from localStorage on component mount
onMounted(() => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    try {
      const parsedUser = JSON.parse(storedUser);
      user.value = parsedUser.data;
      //console.log(user.value);
    } catch (error) {
      console.error('Failed to parse user data from localStorage:', error);
    }
  }
});
</script>

<template>
  <div class="profile">
    <h1>Profile Page</h1>
    <div v-if="user.id">
      <p><strong>ID:</strong> {{ user.id }}</p>
      <p><strong>Name:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Email Verified:</strong> {{ user.email_verified_at ? 'Yes' : 'No' }}</p>
      <p><strong>Created At:</strong> {{ new Date(user.created_at).toLocaleString() }}</p>
      <p><strong>Updated At:</strong> {{ new Date(user.updated_at).toLocaleString() }}</p>
    </div>
    <div v-else>
      <p>No user data available.</p>
    </div>
  </div>
</template>

<style scoped>
.profile {
  padding: 20px;
  font-family: Arial, sans-serif;
}

h1 {
  color: #333;
}

p {
  margin: 5px 0;
}

strong {
  color: #555;
}
</style>
