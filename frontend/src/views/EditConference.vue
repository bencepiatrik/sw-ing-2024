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
                <v-toolbar-title class="text-h6">Admin Panel</v-toolbar-title>
              </v-col>

              <!-- Buttons Section -->
              <v-col cols="3" class="d-flex justify-end align-center">
                <v-btn href="/main">Home</v-btn>
                <v-btn href="/profile">Profile</v-btn>
                <v-btn href="/">Landing</v-btn>
                <!-- <v-btn @click="handleLogout">Logout</v-btn>-->
              </v-col>
            </v-row>
          </v-container>
        </v-app-bar>
        
        <v-main fluid class="pa-4" style="background-color: #D9DCD6;">
      <v-container fluid class="d-flex pa-0">
        <!-- Sidebar -->
        <v-navigation-drawer app permanent width="80" class="sidebar">
          <!-- Back -->
          <v-list dense>
            <v-list-item
              link
              height="100"
              :href="`/adminconference/${conferenceId}`"
              class="d-flex justify-center"
            >
              <v-icon large>mdi mdi-backburger</v-icon>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
          <v-card class="pa-4 mb-4" style="width: 60vw;">
            <v-card-title class="headline text-center">Editovať konferenciu</v-card-title>
            <v-form @submit.prevent="updateConference">
              <!-- Názov -->
              <v-text-field
                v-model="formData.name"
                label="Názov"
                required
              ></v-text-field>
  
              <!-- Typ -->
              <v-text-field
                v-model="formData.type"
                label="Typ"
                required
              ></v-text-field>
  
              <!-- Rok -->
              <v-text-field
                v-model="formData.year"
                label="Rok"
                type="number"
                required
              ></v-text-field>
  
              <!-- Expiration date -->
              <v-date-input
                v-model="formData.expiration_date"
                label="Dátum vypršania"
                required
              ></v-date-input>
  
              <v-container class="d-flex justify-end">
                <v-btn color="primary" type="submit">Uložiť zmeny</v-btn>
              </v-container>
            </v-form>
          </v-card>
        </v-container>
      </v-main>
    </v-app>
</template>
  
<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import axiosInstance from '../api/axiosInstance';
    import { VDateInput } from "vuetify/labs/VDateInput";

    const route = useRoute();
    const router = useRouter();

    const conferenceId = Number(route.params.id); // Získanie ID konferencie z URL

    const formData = ref({
      name: '',
      type: '',
      year: null,
      expiration_date: null,
    });

    const fetchConferenceDetails = async () => {
      try {
        const response = await axiosInstance.get(`/api/conferences/${conferenceId}`);
        formData.value = {
          name: response.data.name,
          type: response.data.type,
          year: response.data.year,
          expiration_date: response.data.expiration_date,
        };
      } catch (error) {
        console.error('Error fetching conference details:', error);
      }
    };

    const updateConference = async () => {
      try {
        await axiosInstance.put(`/api/conferences/${conferenceId}`, formData.value);
        alert('Konferencia bola úspešne aktualizovaná!');
        router.push(`/adminconference/${conferenceId}`);
      } catch (error) {
        console.error('Error updating conference:', error);
        alert('Nepodarilo sa uložiť zmeny.');
      }
    };

    onMounted(() => {
      fetchConferenceDetails();
    });
</script>

<style scoped>
.sidebar {
    background-color: #F5F5F5;
}
</style>