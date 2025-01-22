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
  role?: string | null;
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

const showReviewForm = ref(false);
let currentPublicationId = null;
const showForm = ref(false);
const formValid = ref(false);
const form = ref({
  nazov: '',
  abstrakt: '',
  klucoveSlova: '',
});
const reviewForm = ref({
  aktualnost: null,
  zorientovanie: null,
  vhodnostMetod: null,
  rozsahUroven: null,
  analyzaInterpretacia: null,
  prehladnostLogika: null,
  formalnaJazykova: null,
  missingTitle: false,
  missingAuthorName: false,
  missingEmail: false,
  missingAbstract: false,
  invalidAbstractLength: false,
  missingKeywords: false,
  missingSections: false,
  missingReferences: false,
  missingTextReferences: false,
  missingImageReferences: false,
  missingImageDescriptions: false,
  strongPoints: '',
  weakPoints: '',
  status: '',
});

function openReviewForm(publicationId) {
  currentPublicationId = publicationId;
  showReviewForm.value = true;
}

// Closes the review modal
function closeReviewForm() {
  showReviewForm.value = false;
  resetReviewForm();
}

function resetReviewForm() {
  reviewForm.value.aktualnost = null;
  reviewForm.value.zorientovanie = null;
  reviewForm.value.vhodnostMetod = null;
  reviewForm.value.rozsahUroven = null;
  reviewForm.value.analyzaInterpretacia = null;
  reviewForm.value.prehladnostLogika = null;
  reviewForm.value.formalnaJazykova = null;
  // Resetting new true/false fields
  reviewForm.value.missingTitle = false;
  reviewForm.value.missingAuthorName = false;
  reviewForm.value.missingEmail = false;
  reviewForm.value.missingAbstract = false;
  reviewForm.value.invalidAbstractLength = false;
  reviewForm.value.missingKeywords = false;
  reviewForm.value.missingSections = false;
  reviewForm.value.missingReferences = false;
  reviewForm.value.missingTextReferences = false;
  reviewForm.value.missingImageReferences = false;
  reviewForm.value.missingImageDescriptions = false;
  // Reset new fields
  reviewForm.value.strongPoints = '';
  reviewForm.value.weakPoints = '';
  reviewForm.value.status = '';
}

function resetForm() {
  form.value.nazov = '';
  form.value.abstrakt = '';
  form.value.klucoveSlova = '';
  showForm.value = false;
}

async function submitReview() {
  try {
    const reviewData = {
      publicationId: currentPublicationId,
      reviewData: {
        aktualnost: reviewForm.value.aktualnost,
        zorientovanie: reviewForm.value.zorientovanie,
        vhodnostMetod: reviewForm.value.vhodnostMetod,
        rozsahUroven: reviewForm.value.rozsahUroven,
        analyzaInterpretacia: reviewForm.value.analyzaInterpretacia,
        prehladnostLogika: reviewForm.value.prehladnostLogika,
        formalnaJazykova: reviewForm.value.formalnaJazykova,
        // Adding new true/false fields
        missingTitle: reviewForm.value.missingTitle,
        missingAuthorName: reviewForm.value.missingAuthorName,
        missingEmail: reviewForm.value.missingEmail,
        missingAbstract: reviewForm.value.missingAbstract,
        invalidAbstractLength: reviewForm.value.invalidAbstractLength,
        missingKeywords: reviewForm.value.missingKeywords,
        missingSections: reviewForm.value.missingSections,
        missingReferences: reviewForm.value.missingReferences,
        missingTextReferences: reviewForm.value.missingTextReferences,
        missingImageReferences: reviewForm.value.missingImageReferences,
        missingImageDescriptions: reviewForm.value.missingImageDescriptions,
        // Add new fields
        strongPoints: reviewForm.value.strongPoints,
        weakPoints: reviewForm.value.weakPoints,
      },
      status: reviewForm.value.status,
    };

    const response = await axiosInstance.post('/api/reviews', reviewData);
    console.log('Review submitted successfully:', response.data);

    resetReviewForm();
    closeReviewForm();
  } catch (error) {
    console.error('Error submitting review:', error);
  }
}

const rules = {
  required: (value) => !!value || 'Toto pole je povinné.',
};

function openForm() {
  showForm.value = true;
}

async function submitForm(conferenceId) {
  try {
    const response = await axiosInstance.post('/api/publications', {
      title: form.value.nazov,
      abstract: form.value.abstrakt,
      keywords: form.value.klucoveSlova,
      conference_id: conferenceId,
    });
    console.log('Publikácia vytvorená:', response.data);

    // Reset the form after submission
    resetForm();
  } catch (error) {
    console.error('Chyba pri vytváraní publikácie:', error);
  }
}

const fetchConferences = async () => {
  conferences.value = []; // Resetuj konferencie
  const departmentIds = user.value?.departments?.map((department) => department.id);

  if (departmentIds && departmentIds.length > 0) {
    try {
      const responses = await Promise.all(
  departmentIds.map((id) => {
    //console.log(`Fetching conferences for department ID: ${id}`);
    return axiosInstance.get(`/api/conferences/${id}`);
  })
);
//console.log('Conference responses:', responses);
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

const checkTask = async (conference: any) => {
  try {
    const response = await axiosInstance.get(`/api/conference-tasks/${conference.id}`);
    conference.role = response.data.role;
    //return role;

  } catch (error) {
    console.error('Error checking tasks:', error);
    return null;

  }
}

const sendRoleRequest = async (type:string, conferenceId:number) => {
  try {
    const response = await axiosInstance.post('/api/send-role-request', {
      type: type,
      conference_id: conferenceId,
    });
    alert(response.data.message);
  } catch (error) {
    console.error('Error sending notification:', error);
    alert('Failed to send notification.');
  }
}


const publications = ref({});

const openedConferences = ref<Record<number, boolean>>({}); // Record to track opened/closed state
  //console.log('Conferences:', conferences.value);
  //console.log('User departments:', user.value?.departments);


const togglePublications = async (conferenceId: number) => {
  if (openedConferences.value[conferenceId]) {
    // Ak je otvorené, zavri publikácie
    delete openedConferences.value[conferenceId]; // Remove from opened conferences
    delete publications.value[conferenceId]; // Clear publications if needed
  } else {
    // Ak je zatvorené, načítaj publikácie
    try {
      const response = await axiosInstance.get(`/api/publications?conference_id=${conferenceId}`);
      publications.value[conferenceId] = response.data; // Store publications by conference ID
      openedConferences.value[conferenceId] = true; // Mark as opened
    } catch (error) {
      console.error('Error fetching publications:', error);
    }
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
      return selectedState.value.includes(isActive ? 'Aktívny' : 'Expirovaný');
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
            <v-toolbar-title class="text-h6">NÁSTENKA</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
          <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
          <!--<v-btn variant="text" href="/">Landing</v-btn>-->
          <v-btn variant="text" href="/profile">Profil</v-btn>
          <v-btn variant="text" href="/ziadosti">Žiadosti</v-btn>
          <v-btn variant="text" @click="handleLogout">Odhlásiť sa</v-btn>
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
                label="Vyhľadávanie"
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
                <v-expansion-panel-title style="background-color: #e9efff"     @click="checkTask(conference)">
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
                    <v-col class="d-flex justify-center align-center" cols="20">
                      <!-- Check role and render buttons accordingly -->
                      <div v-if="conference.role === 'autor'">
                        <v-btn color="primary" @click="sendRoleRequest('Poziadanie o role Recenzent', conference.id)">
                          Chcem byť Recenzent
                        </v-btn>
                        <v-btn color="primary" @click="togglePublications(conference.id)">
                          {{ openedConferences[conference.id] ? 'Close' : 'Open' }}
                        </v-btn>
                        <div>
                          <v-btn color="primary" @click="openForm">
                            Vytvoriť publikáciu
                          </v-btn>

                          <!-- Dialog pre formulár -->
                          <v-dialog v-model="showForm" persistent max-width="50vw">
                            <v-card>
                              <v-card-title class="text-h5">Vytvorenie Publikácie</v-card-title>
                              <v-card-text>
                                <v-form ref="publicationForm" v-model="formValid">
                                  <!-- Názov -->
                                  <v-text-field
                                    label="Názov"
                                    v-model="form.nazov"
                                    :rules="[rules.required]"
                                    required
                                  ></v-text-field>

                                  <!-- Abstrakt -->
                                  <v-textarea
                                    label="Abstrakt"
                                    v-model="form.abstrakt"
                                    :rules="[rules.required]"
                                    required
                                  ></v-textarea>

                                  <!-- Kľúčové slová -->
                                  <v-textarea
                                    label="Kľúčové slová"
                                    v-model="form.klucoveSlova"
                                    :rules="[rules.required]"
                                    required
                                  ></v-textarea>
                                </v-form>
                              </v-card-text>
                              <v-card-actions>
                                <!-- Confirm and Cancel Buttons -->
                                <v-btn color="primary" @click="submitForm(conference.id)" :disabled="!formValid">
                                  Potvrdiť
                                </v-btn>
                                <v-btn color="secondary" @click="resetForm">
                                  Zrušiť
                                </v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                        </div>
                      </div>
                      <div v-else-if="conference.role === 'recenzent'">
                        <v-btn color="primary" @click="sendRoleRequest('Poziadanie o role Autor', conference.id)">
                          Chcem byť Autor
                        </v-btn>
                        <v-btn color="primary" @click="togglePublications(conference.id)">
                          {{ openedConferences[conference.id] ? 'Close' : 'Open' }}
                        </v-btn>
                      </div>
                      <div v-else>
                        <v-btn color="primary" @click="sendRoleRequest('Poziadanie o role Autor', conference.id)">
                          Chcem byť Autor
                        </v-btn>
                        <v-btn color="primary" @click="sendRoleRequest('Poziadanie o role Recenzent', conference.id)">
                          Chcem byť Recenzent
                        </v-btn>
                      </div>

                    </v-col>
                  </v-row>
                  <v-row v-if="publications[conference.id]" class="mt-4" no-gutters>
                    <v-col
                      v-for="publication in publications[conference.id]"
                      :key="publication.id"
                      cols="12"
                    >
                      <v-card class="pa-3" elevation="1">
                        <v-row align="center" no-gutters>
                          <v-col cols="4">
                            <strong>{{ publication.title }}</strong>
                          </v-col>
                          <v-col cols="2" class="text-right">
                            <p>{{ publication.status }}</p>
                          </v-col>
                          <v-col v-if="publication.status === 'odovzdaná'" class="mt-2" cols="6">
                            <v-btn color="primary" @click="openReviewForm(publication.id)">
                              Hodnotiť
                            </v-btn>

                            <!-- Review Form Modal -->
                            <v-dialog v-model="showReviewForm" persistent max-width="50vw">
                              <v-card>
                                <v-card-title class="headline">Hodnotiť Publikáciu</v-card-title>
                                <v-card-text>
                                  <!-- Aktualnosť -->
                                  <v-select
                                    v-model="reviewForm.aktualnost"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Aktuálnosť a náročnosť práce"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.zorientovanie"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Zorientovanie sa študenta v danej problematike predovšetkým analýzou domácej a zahraničnej literatúry"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.vhodnostMetod"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Vhodnosť zvolených metód spracovania riešenej problematiky"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.rozsahUroven"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Rozsah a úroveň dosiahnutých výsledkov"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.analyzaInterpretacia"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Analýza a interpretácia výsledkov a formulácia záverov práce"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.prehladnostLogika"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Prehľadnosť a logická štruktúra práce"
                                    required
                                  ></v-select>

                                  <v-select
                                    v-model="reviewForm.formalnaJazykova"
                                    :items="['A', 'B', 'C', 'D', 'E', 'Fx']"
                                    label="Formálna, jazyková a stylistická úroveň práce"
                                    required
                                  ></v-select>

                                  <v-switch
                                    v-model="reviewForm.missingTitle"
                                    label="Chýba názov práce v slovenskom alebo anglickom jazyku"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingTitle" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingAuthorName"
                                    label="Chýba meno autora alebo školiteľa"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingAuthorName" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingEmail"
                                    label="Chýba pracovná emailová adresa autora alebo školiteľa"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingEmail" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingAbstract"
                                    label="Chýba abstrakt v slovenskom alebo anglickom jazyku"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingAbstract" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.invalidAbstractLength"
                                    label="Abstrakt nespĺňa rozsah 100 - 150 slov"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.invalidAbstractLength" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingKeywords"
                                    label="Chýbajú kľúčové slová v slovenskom alebo anglickom jazyku"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingKeywords" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingSections"
                                    label="Chýba 'Úvod', 'Výsledky a diskusia' alebo 'Záver'"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingSections" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingReferences"
                                    label="Nie sú uvedené zdroje a použitá literatúra"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingReferences" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingTextReferences"
                                    label="V texte chýbajú referencie na zoznam bibliografie"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingTextReferences" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingImageReferences"
                                    label="V texte chýbajú referencie na použité obrázky a/alebo tabuľky"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingImageReferences" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-switch
                                    v-model="reviewForm.missingImageDescriptions"
                                    label="Obrázkom a/alebo tabuľkám chýba popis"
                                    :false-value="false"
                                    :true-value="true"
                                    class="d-flex align-center"
                                  >
                                    <template #thumb>
                                      <span v-if="reviewForm.missingImageDescriptions" style="color: green;">✓</span>
                                      <span v-else style="color: red;">✗</span>
                                    </template>
                                  </v-switch>

                                  <v-textarea
                                    v-model="reviewForm.strongPoints"
                                    label="Prínos (silné stránky) práce"
                                    rows="4"
                                    outlined
                                    :rules="[v => !!v || 'Napíšte sem silné stránky']"
                                  ></v-textarea>

                                  <v-textarea
                                    v-model="reviewForm.weakPoints"
                                    label="Nedostatky (slabé stránky) práce"
                                    rows="4"
                                    outlined
                                    :rules="[v => !!v || 'Napíšte sem slabé stránky']"
                                  ></v-textarea>

                                  <v-select
                                    v-model="reviewForm.status"
                                    :items="['prijatá', 'odmietnutá']"
                                    label="Stav Publikácie"
                                    required
                                  ></v-select>


                                </v-card-text>
                                <v-card-actions>
                                  <v-btn color="primary" @click="submitReview">
                                    Uložiť
                                  </v-btn>
                                  <v-btn color="secondary" @click="closeReviewForm">
                                    Zrušiť
                                  </v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>

                          </v-col>
                        </v-row>
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
