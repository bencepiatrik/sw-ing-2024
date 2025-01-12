<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useAuthStore } from '../stores/authStore'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const saveProfile = async () => {
  try {
    await authStore.updateUser(user)
    alert('Profile updated successfully.')
  } catch (error) {
    alert('Failed to update profile.')
    console.error(error)
  }
}

const deleteUser = async () => {
  if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
    try {
      await authStore.deleteUser()
      router.push('/')
    } catch (error) {
      alert('Failed to delete account.')
      console.error(error)
    }
  }
}

const goToProfile = () => {
  router.push('/profile')
}
</script>

<template>
  <v-app>
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">Zmeniť Profil</v-toolbar-title>
          </v-col>

          <!-- Spacer for Buttons -->
          <v-spacer></v-spacer>

          <!-- Buttons Section -->
          <v-btn variant="text" href="/profile">Profil</v-btn>
        </v-row>
      </v-container>
    </v-app-bar>

    <div class="profile">
      <div class="profile-editor">
        <div class="profile-card">
          <h1>Zmeniť Profil</h1>
          <form @submit.prevent="saveProfile"> <!-- Bence ez nem megy -->
            <div>
              <label for="name">Meno</label>
              <input id="name" v-model="user.name" type="text" placeholder="meno..." />
            </div>
            <div>
              <label for="name">Priezvisko</label>
              <input id="name" v-model="user.surname" type="text" placeholder="priezvisko..." />
            </div>
            <div>
              <label for="email">Email</label>
              <input id="email" v-model="user.email" type="email" placeholder="email..." />
            </div>
            <div class="button-container">
              <button type="submit">Save</button>
              <button type="button" @click="deleteUser" class="delete-button">Delete Account</button>
            </div>
          </form><div class="discard-button-container">
          <button @click="goToProfile" class="discard-button">Discard changes</button>
        </div>
        </div>
      </div>

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

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

label {
  font-weight: bold;
  color: #333333;
}

input {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 100%;
}

.button-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
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

.delete-button {
  background-color: #dc3545;
}

.delete-button:hover {
  background-color: #a71d2a;
}

.discard-button-container {
  display: flex;
  justify-content: center; /* Középre igazítja a gombot */
  width: 100%;
  margin-top: 20px;
}

.discard-button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #6c757d;
  color: white;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.discard-button:hover {
  background-color: #5a6268;
}
</style>
