<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axiosInstance from '../api/axiosInstance';

const notifications = ref([]);
const headers = [
  { text: 'Type', value: 'type' },
  { text: 'State', value: 'state' },
  { text: 'Created At', value: 'created_at' },
];

const fetchNotifications = async () => {
  try {
    const response = await axiosInstance.get('/api/user-notifications');
    notifications.value = response.data.map((notification: any) => ({
      type: notification.type,
      state: notification.state,
      created_at: notification.created_at,
    }));

    console.log(notifications);
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};

onMounted(fetchNotifications);
</script>


<template>
  <v-app style="background-color: #D9DCD6;">
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">Moj profil</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn variant="text" href="/">Landing</v-btn>
            <v-btn variant="text" href="/main">Domov</v-btn>
            <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
            <v-btn variant="text" href="/profile">Profil</v-btn>
            <v-btn variant="text" @click="handleLogout">Odhlasit sa</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>
    <v-container fluid class="pa-4" style="background-color: #D9DCD6;">
      <v-card elevation="2" class="pa-4" style="border-radius: 10px;">
        <v-card-title class="headline text-center">Your Notifications</v-card-title>
        <v-data-table :headers="headers" :items="notifications" class="elevation-1" dense>
          <template v-slot:body="{ items }">
            <tr v-for="item in items" :key="item.id">
              <td>{{ item.type }}</td>
              <td>{{ item.state }}</td>
              <td>{{ new Date(item.created_at).toLocaleString() }}</td>
            </tr>
          </template>
        </v-data-table>
      </v-card>
    </v-container>
  </v-app>
</template>
