<template>
 <v-app class="main-container">
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">   Main Page</v-toolbar-title>
          </v-col>

          <!-- Spacer for Buttons -->
          <v-spacer></v-spacer>

          <!-- Buttons Section -->
          <v-btn variant="text" href="/profile">Profile</v-btn>
          <v-btn variant="text" href="/admin">Admin Panel</v-btn>
          <v-btn variant="text" href="/">Landing</v-btn>
          <v-btn variant="text" @click="handleLogout">Logout</v-btn>
        </v-row>
      </v-container>
    </v-app-bar>
    <v-main class="text-center pa-8">
      <v-row>
        <!-- Sidebar na filtrovanie -->
        <v-col cols="2">
          <v-card>
            <v-card-title>Filtrovať</v-card-title>
            <v-checkbox
              v-for="filter in ['Category1', 'Skola1', 'Rok1', 'Checkbox']"
              :key="filter"
              :label="filter"
              :value="filter"
              v-model="selectedFilters"
            ></v-checkbox>
          </v-card>
        </v-col>

        <!-- Hlavný obsah -->
        <v-col cols="10">
          <v-row>
            <!-- Vyhľadávanie -->
            <v-col cols="8">
              <v-text-field
                v-model="search"
                label="Search"
                prepend-inner-icon="mdi-magnify"
              ></v-text-field>
            </v-col>

            <!-- Zoradenie -->
            <v-col cols="4">
              <v-select
                v-model="sortOrder"
                label="Select"
                :items="['A -> Z (Title)', 'Z -> A (Title)', 'Najnovší', 'Najstarší']"
              ></v-select>
            </v-col>
          </v-row>

          <!-- Zoznam kategórií -->
          <v-expansion-panels>
            <v-expansion-panel
              v-for="category in filteredCategories"
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
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import axiosInstance from '../api/axiosInstance';
import { useAuthStore } from '../stores/authStore';

// Inicializácia routera a authStore
const router = useRouter();
const authStore = useAuthStore();

// Reaktívne dáta pre kategórie
const categories = ref<Array<{ id: number; name: string; description: string; type: string }>>([]);

// Reaktívne premenné pre vyhľadávanie, filtrovanie a zoradenie
const search = ref<string>('');
const selectedFilters = ref<string[]>([]); // Vybrané hodnoty checkboxov
const sortOrder = ref<string>('A -> Z (Title)');

// Funkcia na načítanie kategórií z API
const fetchCategories = async () => {
  try {
    const response = await axiosInstance.get('/api/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

// Odvodené dáta pre filtrované a zoradené kategórie
const filteredCategories = computed(() => {
  let filtered = categories.value;

  // Filtrovanie podľa checkboxov
  if (selectedFilters.value.length > 0) {
    filtered = filtered.filter(category =>
      selectedFilters.value.includes(category.name)
    );
  }

  // Vyhľadávanie podľa textu
  filtered = filtered.filter(category =>
    category.name.toLowerCase().includes(search.value.toLowerCase())
  );

  // Zoradenie
  switch (sortOrder.value) {
    case 'A -> Z (Title)':
      return filtered.sort((a, b) => a.name.localeCompare(b.name));
    case 'Z -> A (Title)':
      return filtered.sort((a, b) => b.name.localeCompare(a.name));
    case 'Najnovší':
      return filtered.sort((a, b) => b.id - a.id); // Predpokladáme, že `id` reprezentuje čas
    case 'Najstarší':
      return filtered.sort((a, b) => a.id - b.id);
    default:
      return filtered;
  }
});

// Funkcia pre otvorenie detailu kategórie
const openCategoryPage = (categoryId: number) => {
  router.push(`/categories/${categoryId}`);
};

// Funkcia pre odhlásenie
const handleLogout = async () => {
  await authStore.logout(); // Počká na dokončenie logout
  router.push('/'); // Presmeruje na landing page
};

// Sleduj, či sa stav autentifikácie zmenil
watch(
  () => authStore.isAuthenticated,
  isAuthenticated => {
    if (!isAuthenticated) {
      router.push('/'); // Ak používateľ nie je autentifikovaný, presmeruj
    }
  }
);

// Načítanie kategórií po načítaní komponenty
onMounted(() => {
  fetchCategories();
});
</script>
