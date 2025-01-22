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
            <v-toolbar-title class="text-h6">MOJE ŽIADOSTI</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn variant="text" href="/main" v-if="user && user.role_id !== 5">Domov</v-btn>
            <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
            <v-btn variant="text" href="/profile">Profil</v-btn>
            <v-btn variant="text" @click="handleLogout">Odhlasit sa</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>
    <v-container fluid class="pa-4" style="background-color: #D9DCD6;">
      <v-card elevation="2" class="pa-4" style="border-radius: 10px; width: 60vw;">
        <v-card-title class="headline text-center">Moje žiadosti</v-card-title>
        <div class="table-responsive">
          <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #f5f5f5;">
            <tr>
              <th style="padding: 10px; border: 1px solid #ddd;">Typ</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Stav</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Vytvorené</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in notifications" :key="item.id" style="border: 1px solid #ddd;">
              <td style="padding: 10px; border: 1px solid #ddd;">{{ item.type }}</td>
              <td style="padding: 10px; border: 1px solid #ddd;">{{ item.state }}</td>
              <td style="padding: 10px; border: 1px solid #ddd;">{{ new Date(item.created_at).toLocaleString() }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </v-card>

    </v-container>
  </v-app>
</template>

<script setup lang="ts">
import axiosInstance from '../api/axiosInstance';
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import { storeToRefs } from 'pinia';

const router = useRouter();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

interface Notification {
  id: number;
  type: string;
  state: string;
  created_at: string;
}

const notifications = ref<Notification[]>([]);

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

const handleLogout = async () => {
  await authStore.logout(); // Počká na dokončenie logout
  router.push('/'); // Presmeruje na landing page
};

onMounted(fetchNotifications);
</script>
