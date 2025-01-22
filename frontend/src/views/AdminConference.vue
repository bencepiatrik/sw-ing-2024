<template>
  <v-app style="background-color: #D9DCD6;">
    <!-- Navbar -->
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">ADMIN PANEL</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn @click="handleLogout">ODHLÁSIŤ SA</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <!-- Sidebar + Content Layout -->
    <v-main fluid class="pa-4" style="background-color: #D9DCD6;">
      <v-container fluid class="d-flex pa-0">
        <!-- Sidebar -->
        <v-navigation-drawer app permanent width="80" class="sidebar">
          <!-- Back -->
          <v-list dense>
            <v-list-item link height="100" href="/admin" class="d-flex justify-center">
              <v-icon large>mdi mdi-backburger</v-icon>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-card elevation="2" class="pa-4" style="border-radius: 10px; width: 60vw;">
          <v-card-title class="headline text-center">{{ conference?.name }}</v-card-title>
          <v-card-text>
            <strong>Dátum expirácie: {{ formatDate(conference?.expiration_date) || 'N/A' }}</strong><br>
            <strong>Typ: {{ conference?.type || 'N/A' }}</strong><br>
            <strong>Rok: {{ conference?.year || 'N/A' }}</strong>
          </v-card-text>
          <v-btn color="primary" class="mb-4" @click="EditConference">
          Editovať konferenciu
        </v-btn>
          <v-card-title class="headline text-center">Publikácie</v-card-title>
          <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
              <thead style="background-color: #f5f5f5;">
              <tr>
                <th style="padding: 10px; border: 1px solid #ddd;">ID</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Názov</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Stav</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Dátum vytvorenia</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Dátum aktualizácie</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="item in publications" :key="item.id" @click="goToPublication(item.id)" style="cursor: pointer; border: 1px solid #ddd;">
                <td style="padding: 10px; border: 1px solid #ddd;">{{ item.id }}</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ item.title }}</td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ item.state }}</td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                  {{ formatDateTime(item.created_at).date+" "+formatDateTime(item.created_at).time }}
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                  {{ formatDateTime(item.updated_at).date+" "+formatDateTime(item.updated_at).time }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';
import axiosInstance from '../api/axiosInstance';

const router = useRouter();
const route = useRoute();

const conferenceId = Number(route.params.id); // Získanie ID z URL

const publications = ref<
  { id: number; title: string; state: string; created_at: string; updated_at: string }[]
>([]);

const conference = ref<{
  id: number;
  name: string;
  type: string;
  year: number;
  expiration_date: string;
} | null>(null);

onMounted(() => {
  if (conferenceId) {
    fetchConferenceDetails(conferenceId);
    fetchPublications(conferenceId);
  } else {
    console.error('Conference ID is missing');
  }
});

// Metódy
const fetchPublications = async (conferenceId: number) => {
  try {
    const response = await axiosInstance.get(`/api/publications?conference_id=${conferenceId}`);
    publications.value = response.data;
  } catch (error) {
    console.error('Error fetching publications:', error);
  }
};

const fetchConferenceDetails = async (conferenceId: number) => {
  try {
    const response = await axiosInstance.get(`/api/conferences/${conferenceId}`);
    conference.value = response.data;
  } catch (error) {
    console.error('Error fetching conference details:', error);
  }
};

const goToPublication = (publicationId: number) => {
  router.push(`/publication/${publicationId}`);
};

const EditConference = () => {
  router.push(`/editconference/${conferenceId}`);
};

const handleLogout = () => {
  sessionStorage.removeItem('user');
  window.location.href = '/';
};

// Formátovanie dátumu a času
const formatDateTime = (datetime: string) => {
  const dateObj = new Date(datetime);
  const date = dateObj.toLocaleDateString('sk-SK', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
  const time = dateObj.toLocaleTimeString('sk-SK', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
  return { date, time };
};

const formatDate = (dateString: string | undefined) => {
  if (!dateString) return null;
  const dateObj = new Date(dateString);
  return dateObj.toLocaleDateString('sk-SK', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

</script>




<style scoped>
.sidebar {
  background-color: #F5F5F5;
}

content-section {
  width: 70vw;
  margin: 0 auto;
  padding: 0;
  display: flex;
  flex-direction: column;
}
</style>
