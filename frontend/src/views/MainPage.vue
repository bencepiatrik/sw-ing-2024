
<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import axiosInstance from '../api/axiosInstance';
import { useAuthStore } from '../stores/authStore';

export interface Conference {
  id: number;
  name: string;
  year: number;
  type: string;
  department_id: number;
  expiration_date: string;
  created_at: string;
  updated_at: string;
  department?: {
    id: number;
    name: string;
  };
  publications?: Array<{
    id: number;
    title: string;
    content: string;
    conference_id: number;
    created_at: string;
    updated_at: string;
  }>;
}


// Inicializácia routera a authStore
import { storeToRefs } from 'pinia';
const router = useRouter();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const needsWorkplace = computed(() => {
  return !user.value?.departments || user.value.departments.length === 0;
});

const conferences = ref<Conference[]>([]);

const fetchConferences = async () => {
    const departmentId = user.value?.departments[0].pivot.department_id;
    try {
      const response = await axiosInstance.get(`/api/conferences/${departmentId}`);
      conferences.value = response.data;
    } catch (error) {
      console.error('Error fetching conferences:', error);
    }
};


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

if (!needsWorkplace.value) {
  // Fetch data from the database
  //console.log("ma pracovisko")
  fetchConferences();

} else {
  //console.log("nema pracovisko")
}

// Načítanie kategórií po načítaní komponenty
onMounted(() => {
  //fetchCategories();

});
</script>


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
            <v-toolbar-title class="text-h6">Main Page</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
          <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
          <v-btn variant="text" href="/">Landing</v-btn>
          <v-btn variant="text" href="/profile">Profil</v-btn>
          <v-btn variant="text" href="/ziadosti">Ziadosti</v-btn>
          <v-btn variant="text" @click="handleLogout">Odhlasit sa</v-btn>
          </v-col>
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
          <div v-if="!needsWorkplace">
            <v-expansion-panels>
              <v-expansion-panel
                v-for="conference in conferences"
                :key="conference.id"
                class="my-2"
              >
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      {{ conference.name }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="6">
                      {{ conference.type }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3" style="color: green; font-weight: bold">
                      {{ new Date(conference.expiration_date) > new Date() ? 'Active' : 'Expired' }}
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters>
                    <v-col class="d-flex justify-center align-center" cols="10">
                      <p>Year: {{ conference.year }}</p>
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
          </div>
          <div v-else>
            <div class="alert alert-warning">
              <h2>Pozor!</h2>
              <p>Nemáte vybraté pracovisko.</p>
              <a href="/profile" class="btn btn-primary">Nastaviť pracovisko</a>
            </div>
          </div>


        </v-col>
      </v-row>
    </v-main>
  </v-app>



</template>
