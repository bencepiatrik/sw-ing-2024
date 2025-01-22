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

interface Review {
    authorName: string;
    reviewerName: string;
    time: string;
    aktualnost: string;
    zorientovanie: string;
    vhodnostMetod: string;
    rozsahUroven: string;
    analyzaInterpretacia: string;
    prehladnostLogika: string;
    formalnaJazykova: string;
    missingTitle: boolean;
    missingAuthorName: boolean;
    missingEmail: boolean;
    missingAbstract: boolean;
    invalidAbstractLength: boolean;
    missingKeywords: boolean;
    missingSections: boolean;
    missingReferences: boolean;
    missingTextReferences: boolean;
    missingImageReferences: boolean;
    missingImageDescriptions: boolean;
    strongPoints: string;
    weakPoints: string;
}
// Inicializácia routera a authStore
import { storeToRefs } from 'pinia';
import {reactive} from "@vue/runtime-dom";
const router = useRouter();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const dialogVisible = ref(false);
const selectedPublication = ref({
  title: "",
  abstract: "",
  keywords: "",
  file: "",
  file_name: "",
});

const downloadFile = async (publicationId) => {
  try {
    const response = await axiosInstance.get(`/api/publications/${publicationId}/download`, {
      responseType: 'blob',
    });

    const blob = new Blob([response.data], { type: response.headers['content-type'] });
    const contentDisposition = response.headers['content-disposition'];
    let filename = 'test';
    console.log(response);
    if (contentDisposition && contentDisposition.includes('filename=')) {
      filename = contentDisposition
        .split('filename=')[1]
        .trim()
        .replace(/(^["']|["']$)/g, '');
    }

    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename);

    document.body.appendChild(link);
    link.click();

    link.remove();
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Failed to download file:', error);
    alert('Chyba pri sťahovaní súboru.');
  }
};

const viewPublication = async (publicationId) => {
  try {
    const response = await axiosInstance.get(`/api/publication/${publicationId}`);
    selectedPublication.value = response.data;
    dialogVisible.value = true;
  } catch (error) {
    console.error("Chyba pri načítaní publikácie:", error);
    alert("Nepodarilo sa načítať publikáciu.");
  }
};

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
        strongPoints: reviewForm.value.strongPoints,
        weakPoints: reviewForm.value.weakPoints,
      },
      status: reviewForm.value.status,
    };

    const response = await axiosInstance.post('/api/reviews', reviewData);
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
    resetForm();
  } catch (error) {
    console.error('Chyba pri vytváraní publikácie:', error);
  }
}
const isViewReviewModalOpen = ref(false);
//funguje len s reactive deklaraciou
const currentReview = reactive<Review>({
    authorName: '',
    reviewerName: '',
    time: '',
    aktualnost: '',
    zorientovanie: '',
    vhodnostMetod: '',
    rozsahUroven: '',
    analyzaInterpretacia: '',
    prehladnostLogika: '',
    formalnaJazykova: '',
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
});

async function viewReview(publicationId) {
  try {
    if (!publicationId) {
      console.error('Invalid publication ID');
      return;
    }

    const response = await axiosInstance.get(`/api/reviews/${publicationId}`);
    const reviewData = response.data.review_data;

    Object.assign(currentReview, {
      aktualnost: reviewData.aktualnost || null,
      zorientovanie: reviewData.zorientovanie || null,
      vhodnostMetod: reviewData.vhodnostMetod || null,
      rozsahUroven: reviewData.rozsahUroven || null,
      analyzaInterpretacia: reviewData.analyzaInterpretacia || null,
      prehladnostLogika: reviewData.prehladnostLogika || null,
      formalnaJazykova: reviewData.formalnaJazykova || null,
      strongPoints: reviewData.strongPoints || '',
      weakPoints: reviewData.weakPoints || '',
      missingTitle: reviewData.missingTitle || false,
      missingAuthorName: reviewData.missingAuthorName || false,
      missingEmail: reviewData.missingEmail || false,
      missingAbstract: reviewData.missingAbstract || false,
      invalidAbstractLength: reviewData.invalidAbstractLength || false,
      missingKeywords: reviewData.missingKeywords || false,
      missingSections: reviewData.missingSections || false,
      missingReferences: reviewData.missingReferences || false,
      missingTextReferences: reviewData.missingTextReferences || false,
      missingImageReferences: reviewData.missingImageReferences || false,
      missingImageDescriptions: reviewData.missingImageDescriptions || false,
    });

    currentReview.authorName = `${response.data.author.name} ${response.data.author.surname}`;
    currentReview.reviewerName = `${response.data.reviewer.name} ${response.data.reviewer.surname}`;
    currentReview.time = response.data.time;

    isViewReviewModalOpen.value = true;

  } catch (error) {
    console.error('Error fetching review:', error);
  }
}


const fetchConferences = async () => {
  conferences.value = []; // Resetuj konferencie
  const departmentIds = user.value?.departments?.map((department) => department.id);

  if (departmentIds && departmentIds.length > 0) {
    try {
      const responses = await Promise.all(
  departmentIds.map((id) => {
    return axiosInstance.get(`/api/conferences/${id}`);
  })
);
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

const openedConferences = ref<Record<number, boolean>>({});

const togglePublications = async (conferenceId: number) => {
  if (openedConferences.value[conferenceId]) {
    // Ak je otvorené, zavri publikácie
    delete openedConferences.value[conferenceId];
    delete publications.value[conferenceId];
  } else {
    // Ak je zatvorené, načítaj publikácie
    try {
      const response = await axiosInstance.get(`/api/publications?conference_id=${conferenceId}`);
      publications.value[conferenceId] = response.data;
      openedConferences.value[conferenceId] = true;
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


const editPublication = (publicationId: number) => {
  router.push(`/edit-publication/${publicationId}`);
};

// Funkcia pre odhlásenie
const handleLogout = async () => {
  await authStore.logout();
  router.push('/');
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
                        <v-btn  class="mr-4" color="primary" @click="sendRoleRequest('Poziadanie o role Recenzent', conference.id)">
                          Chcem byť Recenzent
                        </v-btn>
                          <v-btn class="mr-4" color="primary" @click="openForm">
                            Vytvoriť publikáciu
                          </v-btn>
                          <v-btn color="primary" @click="togglePublications(conference.id)">
                            {{ openedConferences[conference.id] ? 'Close' : 'Open' }}
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
                      <div v-else-if="conference.role === 'recenzent'">
                        <v-btn class="mr-4" color="primary" @click="sendRoleRequest('Poziadanie o role Autor', conference.id)">
                          Chcem byť Autor
                        </v-btn>
                        <v-btn color="primary" @click="togglePublications(conference.id)">
                          {{ openedConferences[conference.id] ? 'Close' : 'Open' }}
                        </v-btn>
                      </div>
                      <div v-else>
                        <v-btn class="mr-4" color="primary" @click="sendRoleRequest('Poziadanie o role Autor', conference.id)">
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
                          <v-col cols="2">
                            <strong>{{ publication.title }}</strong>
                          </v-col>
                          <v-col cols="2" class="text-right">
                            <p>{{ publication.status }}</p>
                          </v-col>
                          <v-col
                            v-if="conference.role === 'autor'"
                            class="mt-2"
                            cols="4"
                          >
                            <v-btn
                              color="primary"
                              @click="viewPublication(publication.id)"
                            >
                              Zobraziť Publikáciu
                            </v-btn>
                            <!-- Dialog for Viewing Publication -->
                            <v-dialog v-model="dialogVisible" max-width="600px">
                              <v-card>
                                <v-card-title>
                                  <span class="text-h5">{{ selectedPublication.title }}</span>
                                </v-card-title>
                                <v-card-text>
                                  <p><strong>Abstrakt:</strong> {{ selectedPublication.abstract }}</p>
                                  <p><strong>Kľúčové slová:</strong> {{ selectedPublication.keywords }}</p>
                                  <p v-if="selectedPublication.file">
                                    <strong>Súbor:</strong>
                                    <a
                                      @click="downloadFile(publication.id)"
                                    >Stiahnuť súbor</a>
                                  </p>
                                </v-card-text>
                                <v-card-actions>
                                  <v-btn color="primary" @click="dialogVisible = false">Zavrieť</v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>
                          </v-col>
                          <v-col v-if="publication.status === 'odovzdaná' && conference.role === 'recenzent'" class="mt-2" cols="6">
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
                          <v-col
                            v-if="publication.status === 'prijatá' || publication.status === 'odmietnutá' && conference.role === 'recenzent'"
                            class="mt-2"
                            cols="4"
                          >
                            <v-btn
                              color="primary"
                              @click="viewReview(publication.id)"
                            >
                              Zobraziť Hodnotenie
                            </v-btn>
                            <v-dialog v-model="isViewReviewModalOpen" max-width="800px">
                              <v-card>
                                <v-card-title class="text-h6 font-weight-bold">
                                  Zobraziť Hodnotenie
                                </v-card-title>
                                <v-card-text>
                                  <!-- Author and Reviewer Info -->
                                  <v-row>
                                    <v-col cols="12">
                                      <p><strong>Autor:</strong> {{ currentReview.authorName }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Recenzent:</strong> {{ currentReview.reviewerName }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Čas Hodnotenia:</strong> {{ new Date(currentReview.time).toLocaleString() }}</p>
                                    </v-col>
                                  </v-row>

                                  <v-row>
                                    <!-- Aktualnosť -->
                                    <v-col cols="12">
                                      <p><strong>Aktuálnosť:</strong> {{ currentReview.aktualnost }}</p>
                                    </v-col>

                                    <!-- Zorientovanie -->
                                    <v-col cols="12">
                                      <p><strong>Zorientovanie:</strong> {{ currentReview.zorientovanie }}</p>
                                    </v-col>

                                    <!-- Vhodnosť Metód -->
                                    <v-col cols="12">
                                      <p><strong>Vhodnosť Metód:</strong> {{ currentReview.vhodnostMetod }}</p>
                                    </v-col>

                                    <!-- Rozsah a Úroveň -->
                                    <v-col cols="12">
                                      <p><strong>Rozsah a Úroveň:</strong> {{ currentReview.rozsahUroven }}</p>
                                    </v-col>

                                    <!-- Analýza a Interpretácia -->
                                    <v-col cols="12">
                                      <p><strong>Analýza a Interpretácia:</strong> {{ currentReview.analyzaInterpretacia }}</p>
                                    </v-col>

                                    <!-- Prehľadnosť a Logika -->
                                    <v-col cols="12">
                                      <p><strong>Prehľadnosť a Logika:</strong> {{ currentReview.prehladnostLogika }}</p>
                                    </v-col>

                                    <!-- Formálna a Jazyková Úroveň -->
                                    <v-col cols="12">
                                      <p><strong>Formálna a Jazyková Úroveň:</strong> {{ currentReview.formalnaJazykova }}</p>
                                    </v-col>

                                    <!-- Missing Items -->
                                    <v-col cols="12">
                                      <p><strong>Chýba Názov:</strong> {{ currentReview.missingTitle ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýba Meno Autora:</strong> {{ currentReview.missingAuthorName ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýba Email:</strong> {{ currentReview.missingEmail ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýba Abstrakt:</strong> {{ currentReview.missingAbstract ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Abstrakt Dĺžka Neplatná:</strong> {{ currentReview.invalidAbstractLength ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Kľúčové Slová:</strong> {{ currentReview.missingKeywords ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Časti Textu:</strong> {{ currentReview.missingSections ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Referencie:</strong> {{ currentReview.missingReferences ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Textové Referencie:</strong> {{ currentReview.missingTextReferences ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Referencie k Obrázkom:</strong> {{ currentReview.missingImageReferences ? 'Áno' : 'Nie' }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                      <p><strong>Chýbajú Popisy k Obrázkom:</strong> {{ currentReview.missingImageDescriptions ? 'Áno' : 'Nie' }}</p>
                                    </v-col>

                                    <!-- Strong Points -->
                                    <v-col cols="12">
                                      <p><strong>Silné Stránky:</strong> {{ currentReview.strongPoints }}</p>
                                    </v-col>

                                    <!-- Weak Points -->
                                    <v-col cols="12">
                                      <p><strong>Slabé Stránky:</strong> {{ currentReview.weakPoints }}</p>
                                    </v-col>
                                  </v-row>
                                </v-card-text>
                                <v-card-actions>
                                  <v-btn color="primary" @click="isViewReviewModalOpen = false">Zavrieť</v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>


                          </v-col>
                          <v-col cols="2">
                            <!-- Tlačidlo pre publikácie aktuálneho používateľa -->
                            <v-btn
                              v-if="user && publication.user_id === user.id && publication.status !== 'odovzdaná' && publication.status !== 'odmietnutá'"
                              color="primary"
                              @click="editPublication(publication.id)"
                            >
                              Upraviť
                            </v-btn>
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
