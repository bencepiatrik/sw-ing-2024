<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const authStore = useAuthStore();

// Získame reaktívne referencie zo store
const { user } = storeToRefs(authStore);

// Navigate to the edit profile page
const goToEditProfile = () => {
  router.push('/editprofile');
};

// Navigate to the main page
const goToMainPage = () => {
  router.push('/main');
};
</script>

<template>
<v-app>
        <!-- Navbar -->
        <v-app-bar app color="#2D627F" dark>
  <v-container fluid>
    <v-row align="center" no-gutters>
      <!-- Logo Section -->
      <v-col cols="1" class="d-flex justify-start align-center">
        <v-img
          :src="'/logo.png'"
          contain
          style="height: auto; width: auto;"
        />
      </v-col>

      <!-- Title Section -->
      <v-col cols="8" class="d-flex justify-center align-center">
        <v-toolbar-title class="text-h6">My profile</v-toolbar-title>
      </v-col>

      <!-- Spacer for Buttons -->
      <v-spacer></v-spacer>

      <!-- Buttons Section -->
      <v-col cols="3" class="d-flex justify-end align-center">
        <v-btn variant="text" href="/main">Home</v-btn>
        <v-btn variant="text" href="/profile">Profile</v-btn>
        <v-btn variant="text" href="/admin">Admin Panel</v-btn>
        <v-btn variant="text" href="/">Landing</v-btn>
      </v-col>
    </v-row>
  </v-container>
</v-app-bar>
  <div class="profile">
    <div class="profile-editor">
      <div class="profile-card">
        <h1>Profile</h1>
        <div v-if="user && user.id">
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
          <p>Loading user data, please wait..</p>
        </div>
        <div class="button-container">
          <button @click="goToEditProfile">Edit Profile</button>
        </div>
      </div>
    </div>
    <!--  <div class="button-mainPage">
      <button @click="goToMainPage">Go to Main Page</button>
    </div>  -->
  </div>
</v-app>
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
