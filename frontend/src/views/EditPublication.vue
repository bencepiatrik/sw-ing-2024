<template>
    <v-app class="main-container" style="border-radius: 10px;">
      <v-app-bar app color="#2D627F" dark>
        <v-container fluid>
          <v-row align="center" no-gutters>
            <!-- Logo Section -->
            <v-col cols="1" class="d-flex justify-start align-center">
              <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
            </v-col>
            <!-- Title Section -->
            <v-col cols="8" class="d-flex justify-center align-center">
              <v-toolbar-title class="text-h6">NÁSTENKA</v-toolbar-title>
            </v-col>
            <!-- Buttons Section -->
            <v-col cols="3" class="d-flex justify-end align-center">
              <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
              <v-btn variant="text" href="/main">Nástenka</v-btn>
              <v-btn variant="text" href="/profile">Profil</v-btn>
              <v-btn variant="text" href="/ziadosti">Žiadosti</v-btn>
              <v-btn variant="text" @click="handleLogout">Odhlásiť sa</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-app-bar>
      <v-container>
        <v-card elevation="2" class="pa-5">
          <v-card-title class="headline">Editácia Publikácie</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="updatePublication">
              <v-text-field
                label="Názov publikácie"
                v-model="formData.title"
                required
              ></v-text-field>
              <v-textarea
                label="Abstrakt"
                v-model="formData.abstract"
                required
              ></v-textarea>
              <v-textarea
                label="Kľúčové slová"
                v-model="formData.keywords"
                required
              ></v-textarea>
              <v-btn type="submit" color="primary" class="mt-4">Uložiť zmeny</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-container>
    </v-app>
  </template>
  
  
  <script setup lang="ts">
  import { ref, onMounted } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axiosInstance from "../api/axiosInstance";
  import { useAuthStore } from "../stores/authStore";
  import { storeToRefs } from "pinia";
  
  const authStore = useAuthStore();
  const { user } = storeToRefs(authStore);
  const route = useRoute();
  const router = useRouter();
  
  const publicationId = Number(route.params.id); // Získanie ID publikácie z URL
  
  const formData = ref({
    title: "",
    abstract: "",
    keywords: "",
    conference_id: null,
  });
  
  // Načítanie detailov publikácie
  const fetchPublicationDetails = async () => {
    try {
      const response = await axiosInstance.get(`/api/publication/${publicationId}`);
      formData.value = {
        title: response.data.title,
        abstract: response.data.abstract,
        keywords: response.data.keywords,
        conference_id: response.data.conference_id,
      };
    } catch (error) {
      console.error("Chyba pri načítaní detailov publikácie:", error);
      router.push("/main");
    }
  };
  
  // Aktualizácia publikácie
  const updatePublication = async () => {
    try {
      await axiosInstance.put(`/api/publications/${publicationId}`, formData.value);
      alert("Publikácia bola úspešne upravená!");
      router.push("/main");
    } catch (error) {
      console.error("Chyba pri aktualizácii publikácie:", error);
      alert("Nepodarilo sa uložiť zmeny.");
    }
  };

  const handleLogout = async (): Promise<void> => {
    await authStore.logout();
    router.push("/");
  };
  // Načítanie detailov publikácie pri inicializácii komponentu
  onMounted(() => {
    fetchPublicationDetails();
  });
  </script>
  
  
  <style scoped></style>
  