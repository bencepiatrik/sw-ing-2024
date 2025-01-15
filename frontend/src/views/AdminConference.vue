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
            <v-toolbar-title class="text-h6">Admin Panel</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn href="/main">Domov</v-btn>
            <v-btn href="/profile">Profil</v-btn>
            <v-btn href="/">Landing</v-btn>
            <v-btn @click="handleLogout">Odhlasit sa</v-btn>
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
          <v-card-title class="headline text-center">@Title</v-card-title>

          <v-card-text >
            <strong>Expiration Date: @date </strong><br>
            @text
          </v-card-text>
          <v-btn color="primary" class="mb-4" @click="EditConference">
          Editovať konferenciu
        </v-btn>
          <h2>Publifikácie:</h2>
          <v-data-table :items="publications" class="elevation-1">
            <template v-slot:item="{ item }">
              <tr @click="goToPublication(item.id)" style="cursor: pointer;">
                <td>{{ item.title }}</td>
                <td>{{ item.state }}</td>
                <td>user id?</td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()
import { onMounted, ref } from 'vue'
import axiosInstance from '../api/axiosInstance'


const publications = ref<
  { id: number; title: string; state: string }[]
>([])

onMounted(() => {
  fetchPublications();
});

// Methods
const handleLogout = () => {
  sessionStorage.removeItem('user')
  window.location.href = '/'
}
const fetchPublications = async () => {
try {
  const response = await axiosInstance.get('/api/publications')
  publications.value = response.data.map((publication: { id: number; title: string; state: string }) => ({
    ...publication
  }))
} catch (error) {
  console.error('Error fetching publications:', error)
}
};


 const goToPublication = (publicationId: number) => {
   router.push(`/publication/${publicationId}`)
 }

const EditConference = () => {
  router.push('/editconference')
}

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
