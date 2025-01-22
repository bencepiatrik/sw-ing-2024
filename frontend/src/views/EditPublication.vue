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

              <div>
                <label for="file">Nahrať súbor (PDF alebo TXT):</label>
                <input id="file" type="file" @change="handleFileChange" accept=".pdf,.txt" />
                <p v-if="formData.fileName">
                  Aktuálny súbor: <a :href="getFileUrl(formData.file)" target="_blank">{{ formData.fileName }}</a>
                </p>
              </div>

              <v-btn type="submit" color="primary" class="mt-4 mr-6">Uložiť zmeny</v-btn>
              <v-btn type="button" color="primary" class="mt-4" @click="submitAndSetStatus">Uložiť a Odovzdať</v-btn>

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
  const file = ref<File | null>(null); // To store the selected file

  const formData = ref({
    title: "",
    abstract: "",
    keywords: "",
    conference_id: null,
    file: "",
    fileName: "",
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
        file: response.data.file,           // Stored path
        fileName: response.data.file_name,  // Original filename
      };

    } catch (error) {
      console.error("Error fetching publication details:", error);
      router.push("/main");
    }
  };

  const getFileUrl = (filePath) => {
    if (!filePath) return '#';
    return `/storage/${filePath}`; // Adjust this if your storage URL differs
  };


  const getFileName = (filePath) => {
    return filePath ? filePath.split('/').pop() : '';
  };

  const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
      file.value = target.files[0];
    }
  };

  const submitAndSetStatus = async () => {
    try {
      // Update variables (metadata)
      await axiosInstance.put(`/api/publications/${publicationId}`, {
        title: formData.value.title,
        abstract: formData.value.abstract,
        keywords: formData.value.keywords,
        conference_id: formData.value.conference_id,
        set_status: "odovzdaná", // Update status to "odovzdaná"
      });

      // Upload file if a new file is selected
      if (file.value) {
        const fileData = new FormData();
        fileData.append("file", file.value);

        await axiosInstance.post(`/api/publications/${publicationId}/upload`, fileData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
      }

      alert("Publikácia bola úspešne upravená a odovzdaná!");
      router.push("/main");
    } catch (error) {
      console.error("Chyba pri odovzdávaní publikácie:", error);
      alert("Nepodarilo sa uložiť a odovzdať publikáciu.");
    }
  };



  // Aktualizácia publikácie
  const updatePublication = async () => {
    try {
      // Update metadata
      await axiosInstance.put(`/api/publications/${publicationId}`, {
        title: formData.value.title,
        abstract: formData.value.abstract,
        keywords: formData.value.keywords,
        conference_id: formData.value.conference_id,
      });

      // Upload the file if it exists
      if (file.value) {
        const fileData = new FormData();
        fileData.append("file", file.value);

        await axiosInstance.post(`/api/publications/${publicationId}/upload`, fileData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
      }

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
