<template>
    <v-app class="main-container">
      <v-app-bar app>
        <v-toolbar-title>Category Detail</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn variant="text" @click="goBack">Back</v-btn>
      </v-app-bar>
      <v-main>
        <v-container>
          <!-- Kategória Detail -->
          <v-card v-if="category" class="pa-4 mb-4">
            <v-card-title>{{ category.name }}</v-card-title>
            <v-card-text>{{ category.description }}</v-card-text>
          </v-card>
          <v-card v-else class="pa-4 mb-4">
            <v-card-title>Loading...</v-card-title>
            <v-card-text>Please wait while we fetch the category details.</v-card-text>
          </v-card>
  
          <!-- Formulár na pridanie práce -->
          <v-card class="pa-4 mb-4">
            <v-card-title>Add Work</v-card-title>
            <v-form @submit.prevent="submitWork">
              <v-text-field v-model="work.title" label="Title" required></v-text-field>
              <v-textarea v-model="work.description" label="Description" required></v-textarea>
              <v-file-input
                v-model="files"
                label="Attach Files (Max 3)"
                multiple
                :rules="fileRules"
                counter
                show-size
                accept="image/*,application/pdf"
              ></v-file-input>
              <v-btn color="primary" type="submit" :loading="isLoading">Submit</v-btn>
            </v-form>
          </v-card>
  
          <!-- Zobrazenie prác -->
          <v-card class="pa-4">
            <v-card-title>Your Works</v-card-title>
            <v-expansion-panels>
              <v-expansion-panel
                v-for="work in works"
                :key="work.id"
                class="my-2"
              >
                <v-expansion-panel-title>{{ work.title }}</v-expansion-panel-title>
                <v-expansion-panel-text>
                    <p><strong>Description:</strong> {{ work.description }}</p>
                    <p><strong>Status:</strong> {{ work.status }}</p>
                    <p><strong>Created At:</strong> {{ new Date(work.created_at).toLocaleString() }}</p>
                    <div v-if="work.files && decodedFiles(work.files).length">
                        <div v-if="work.files && decodedFiles(work.files).length">
                        <p><strong>Files:</strong></p>
                        <ul>
                            <li v-for="file in decodedFiles(work.files)" :key="file">
                                <a 
                                :href="`${getDownloadURL(file)}`"
                                @click.prevent="downloadFile(getDownloadURL(file))"
                                target="_blank"
                                >
                                    {{ getFileName(file) }}
                                </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card>
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script setup lang="ts">
    import axiosInstance from "../api/axiosInstance";
    import { ref, onMounted } from "vue";
    import { useRoute, useRouter } from "vue-router";

    // Typy
    interface Category {
      id: number;
      name: string;
      description: string;
    }

    interface Work {
      id: number;
      title: string;
      description: string;
      status: string;
      files?: string[] | string; // Môže byť reťazec alebo pole
      created_at: string;
    }

    // Premenné
    const route = useRoute();
    const router = useRouter();

    const category = ref<Category | null>(null);
    const works = ref<Work[]>([]);
    const work = ref({ title: "", description: "" });
    const files = ref<File[]>([]);
    const isLoading = ref(false);

    // Computed property na dekódovanie files
    const decodedFiles = (files: string[] | string | undefined) => {
      if (!files) return [];
      if (typeof files === "string") {
        try {
          return JSON.parse(files);
        } catch (error) {
          console.error("Error parsing files:", error);
          return [];
        }
      }
      return files; // Ak je už pole
    };

    // Získa názov súboru z cesty
    const getFileName = (filePath: string): string => {
      return filePath.split('/').pop() || 'file';
    };

    const getDownloadURL = (filePath: string): string => {
      const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8083';
      return `${baseURL}/api/files/download/${getFileName(filePath)}`;
    }; 

    const downloadFile = (url: string) => {
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', ''); // Zabezpečí stiahnutie
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };


    // Funkcia na načítanie kategórie
    const fetchCategory = async () => {
      try {
        const response = await axiosInstance.get(`/api/categories/${route.params.id}`);
        category.value = response.data;
      } catch (error) {
        console.error("Error fetching category:", error);
      }
    };

    // Funkcia na načítanie prác
    const fetchWorks = async () => {
      try {
        const response = await axiosInstance.get(`/api/categories/${route.params.id}/works`);
        works.value = response.data;
      } catch (error) {
        console.error("Error fetching works:", error);
      }
    };

    // Odoslanie práce
    const submitWork = async () => {
      try {
        isLoading.value = true;

        const formData = new FormData();
        formData.append("title", work.value.title);
        formData.append("description", work.value.description);
        formData.append("category_id", String(route.params.id));

        files.value.forEach((file, index) => {
          formData.append(`files[${index}]`, file);
        });

        await axiosInstance.post("/api/works", formData);
        alert("Work added successfully!");
        fetchWorks(); // Obnoví zoznam prác
      } catch (error) {
        console.error("Error submitting work:", error);
      } finally {
        isLoading.value = false;
      }
    };

    // Validácia súborov
    const fileRules = [(value: File[] | null) => (value && value.length <= 3) || "You can upload up to 3 files only."];

    // Navigácia späť
    const goBack = () => {
      router.push("/main");
    };

    // Načítanie dát pri načítaní komponentu
    onMounted(() => {
      fetchCategory();
      fetchWorks();
    });

  </script>
  
  <style scoped>
  .main-container {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  </style>
  