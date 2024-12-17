<template>
  <v-app class="main-container">
    <v-app-bar app>
      <v-toolbar-title>Main Page</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn variant="text" href="/profile">Profile</v-btn>
      <v-btn variant="text" href="/admin">Admin Panel</v-btn>
      <v-btn variant="text" href="/">Landing</v-btn>
      <v-btn variant="text" @click="handleLogout">Logout</v-btn>
    </v-app-bar>
    <v-main>
      <v-container class="text-center">
        <v-card elevation="2" class="ma-4">
          <v-card-title class="headline">Welcome to the Main Page</v-card-title>
          <v-card-text>
            <p>You are now logged in.</p>
          </v-card-text>
        </v-card>
        <v-row>
          <v-col class="ma-4">
            <v-expansion-panels>
              <v-expansion-panel
                v-for="category in categories"
                :key="category.id"
                class="my-2"
              >
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      {{ category.name }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="6">
                      {{ category.description }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3" style="color: green; font-weight: bold">
                      Active
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters>
                    <v-col class="d-flex justify-center align-center" cols="10">
                      <p>{{ category.description }}</p>
                    </v-col>
                    <v-col class="d-flex justify-center align-center" cols="2">
                      <v-btn color="primary" @click="openCategoryPage(category.id)">
                        Open
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosInstance from "../api/axiosInstance";
import { useAuthStore } from "../stores/authStore";

// Inicializácia routera a authStore
const router = useRouter();
const authStore = useAuthStore();

// Reaktívne dáta pre kategórie
const categories = ref<Array<{ id: number; name: string; description: string }>>([]);

// Funkcia na načítanie kategórií z API
const fetchCategories = async () => {
  try {
    const response = await axiosInstance.get("/api/categories");
    categories.value = response.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

// Funkcia pre otvorenie detailu kategórie
const openCategoryPage = (categoryId: number) => {
  router.push(`/categories/${categoryId}`);
};

// Funkcia pre odhlásenie
const handleLogout = () => {
  authStore.logout();
  router.push("/"); // Namiesto window.location.href
};

// Načítanie kategórií po načítaní komponenty
onMounted(() => {
  fetchCategories();
});
</script>

<style scoped>
.main-container {
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
