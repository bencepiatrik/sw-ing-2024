<template>
  <v-app class="main-container">
    <v-app-bar app>
      <v-toolbar-title>Main Page</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text href="/profile">Profile</v-btn>
      <v-btn text href="/admin">Admin Panel</v-btn>
      <v-btn text href="/">Landing</v-btn>
      <v-btn text @click="handleLogout">Logout</v-btn>
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
                      <v-btn color="primary">
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


<script>
import axiosInstance from "@/api/axiosInstance";
import { ref, onMounted } from "vue";
import { useAuthStore } from "../stores/authStore";

export default {
  setup() {
    const authStore = useAuthStore();
    const categories = ref([]);

    // Funkcia na načítanie kategórií z API
    const fetchCategories = async () => {
      try {
        const response = await axiosInstance.get("/api/categories");
        categories.value = response.data;
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    };

    const handleLogout = () => {
      authStore.logout();
      window.location.href = "/";
    };

    onMounted(() => {
      fetchCategories(); // Načítanie dát po načítaní stránky
    });

    return {
      categories,
      handleLogout,
    };
  },
};
</script>

<style scoped>
.main-container {
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
