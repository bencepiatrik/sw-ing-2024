<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

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
    } catch (error) {
      console.error('Failed to parse user data from localStorage:', error);
    }
  }
});

// Navigate to the edit profile page
const goToEditProfile = () => {
  router.push('/edit-profile');
};

// Navigate to the main page
const goToMainPage = () => {
  router.push('/main');
};
</script>

<template>
  <div class="profile">
    <div class="profile-editor">
      <div class="profile-card">
        <h1>Profile</h1>
        <div v-if="user.id">
          <p><strong>ID:</strong> {{ user.id }}</p>
          <p><strong>Name:</strong> {{ user.name }}</p>
          <p><strong>Email:</strong> {{ user.email }}</p>
          <p>
            <strong>Email Verified:</strong>
            <span :class="{'verified': user.email_verified_at, 'not-verified': !user.email_verified_at}">
              {{ user.email_verified_at ? 'Yes' : 'No' }}
            </span>
          </p>
          <p><strong>Created At:</strong> {{ new Date(user.created_at).toLocaleString() }}</p>
          <p><strong>Updated At:</strong> {{ new Date(user.updated_at).toLocaleString() }}</p>
        </div>
        <div v-else>
          <p>No user data available.</p>
        </div>
        <div class="button-container">
          <button @click="goToEditProfile">Edit Profile</button>
        </div>
      </div>
    </div>
    <div class="button-mainPage">
      <button @click="goToMainPage">Go to Main Page</button>
    </div>
  </div>
</template>



<style scoped>
.profile {
  min-height: 80vh;
  background-color: #f0f4f8;
  font-family: 'Arial', sans-serif;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-card {
  background: #ffffff;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 400px;
}

.profile-editor {
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

h1 {
  font-size: 24px;
  font-weight: bold;
  color: #333333;
  text-align: center;
  margin-bottom: 20px;
}

p {
  margin: 10px 0;
  font-size: 16px;
  line-height: 1.5;
  color: #555555;
}

strong {
  color: #333333;
}

.verified {
  color: green;
  font-weight: bold;
}

.not-verified {
  color: red;
  font-weight: bold;
}

span {
  margin-left: 5px;
}

.button-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.button-mainPage {
  padding: 10px 20px;
  justify-content: center;
}

button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: white;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}
</style>
