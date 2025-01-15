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
const selectedDepartments = ref<string[]>([]);
  const selectedState = ref<string[]>([]);
const selectedYears = ref<number[]>([]);
const categories = ref<Array<{ id: number; name: string; description: string; type: string }>>([]);

const fetchConferences = async () => {
  conferences.value = []; // Reset conferences array
  const departmentIds = user.value?.departments.map((department) => department.id);

  if (departmentIds && departmentIds.length > 0) {
    try {
      // Loop through department IDs and fetch conferences
      const conferencePromises = departmentIds.map((id) =>
        axiosInstance.get(`/api/conferences/${id}`)
      );

      const responses = await Promise.all(conferencePromises);
      // Combine all conferences into one array
      conferences.value = responses.flatMap((response) => response.data);
    } catch (error) {
      console.error('Error fetching conferences:', error);
    }
  } else {
    console.warn('No departments found for this user.');
  }
};

// Reaktívne premenné pre vyhľadávanie, filtrovanie a zoradenie
const search = ref<string>('');
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

const publications = ref({});

const fetchPublications = async (conferenceId: number) => {
  try {
    const response = await axiosInstance.get(`/api/publications?conference_id=${conferenceId}`);
    publications.value[conferenceId] = response.data; // Store publications by conference ID
  } catch (error) {
    console.error('Error fetching publications:', error);
  }
};


// Odvodené dáta pre filtrované a zoradené kategórie
const processedConferences = computed(() => {
  // Krok 1: Filtrovanie
  let filtered = [...conferences.value];

  if (selectedDepartments.value.length > 0) {
    filtered = filtered.filter((conf) => {
      const departmentName = conf.department?.name || ''; // Zabezpečíme, že hodnota nie je undefined
      return selectedDepartments.value.includes(departmentName);
    });
  }

  if (selectedYears.value.length > 0) {
    filtered = filtered.filter((conf) => selectedYears.value.includes(conf.year));
  }

  if (selectedState.value.length > 0) {
    filtered = filtered.filter((conf) => {
      const isActive = new Date(conf.expiration_date) > new Date();
      return selectedState.value.includes(isActive ? 'Active' : 'Expired');
    });
  }

  // Filtrovanie podľa vyhľadávania
  if (search.value.trim() !== '') {
    filtered = filtered.filter((conf) =>
      conf.name.toLowerCase().includes(search.value.toLowerCase())
    );
  }
  
  // Krok 2: Zoradenie
  switch (sortOrder.value) {
    case 'A -> Z (Title)':
      return filtered.sort((a, b) => a.name.localeCompare(b.name));
    case 'Z -> A (Title)':
      return filtered.sort((a, b) => b.name.localeCompare(a.name));
    case 'Najnovší':
      return filtered.sort((a, b) => b.year - a.year);
    case 'Najstarší':
      return filtered.sort((a, b) => a.year - b.year);
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

watch(
  () => user.value,
  (newUser) => {
    if (newUser && newUser.departments && newUser.departments.length) {
      fetchConferences();
    }
  },
  { immediate: true }
);


onMounted(async () => {
  if (!user.value) {
    await authStore.initialize();
  }
  fetchCategories();
  fetchConferences();
});
</script>


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
            <!-- Dropdown pre Katedry -->
            <v-select
              v-model="selectedDepartments"
              :items="Array.from(new Set(conferences.map(c => c.department?.name).filter(Boolean)))"
              label="Katedra"
              multiple
            ></v-select>

            <v-select
              v-model="selectedState"
              :items="['Aktívny', 'Expirovaný']"
              label="Stav"
              multiple
            ></v-select>

            <v-select
              v-model="selectedYears"
              :items="Array.from(new Set(conferences.map(c => c.year)))"
              label="Rok"
              multiple
            ></v-select>

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
                v-for="conference in processedConferences"
                :key="conference.id"
                class="my-2"
              >
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      {{ conference.department?.name }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3">
                      {{ conference.name }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="2">
                      {{ conference.type }}
                    </v-col>
                    <v-col class="d-flex justify-center" cols="1" style="color: green; font-weight: bold">
                      {{ new Date(conference.expiration_date) > new Date() ? 'Active' : 'Expired' }}
                    </v-col>
                    <v-col class="d-flex justify-center align-center" cols="1">
                      <p>{{ conference.year }}</p>
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters>

                    <v-col class="d-flex justify-center align-center" cols="2">
                      <v-btn color="primary" @click="fetchPublications(conference.id)">
                        Open
                      </v-btn>
                    </v-col>
                  </v-row>
                  <v-row v-if="publications[conference.id]" no-gutters class="mt-4">
                    <v-col
                      v-for="publication in publications[conference.id]"
                      :key="publication.id"
                      class="mb-2"
                      cols="12"
                    >
                      <v-card class="pa-3" elevation="1">
                        <strong>{{ publication.title }}</strong>
                        <p>{{ publication.state }}</p>
                        <p>{{ publication.content }}</p>
                      </v-card>
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

<style scoped>


</style>
