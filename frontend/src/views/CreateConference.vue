<template>
  <v-app>
    <!-- Navbar -->
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">Admin Panel</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn href="/main">Home</v-btn>
            <v-btn href="/profile">Profile</v-btn>
            <v-btn href="/">Landing</v-btn>
            <!-- <v-btn @click="handleLogout">Logout</v-btn>-->
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>
    <v-main>
      <v-container>
        <v-card class="pa-4 mb-4">
          <v-card-title class="headline text-center">Vytvoriť konferenciu</v-card-title>
          <v-form @submit.prevent="createConference" ref="form">
            <!-- Názov konferencie -->
            <v-text-field v-model="formData.name" label="Názov" required></v-text-field>

            <!-- Typ konferencie -->
            <v-text-field v-model="formData.type" label="Typ" required></v-text-field>

            <!-- Výber univerzity -->
            <v-autocomplete
              v-model="selectedUniversity"
              :items="universities"
              item-title="name"
              item-value="id"
              label="Univerzita"
              placeholder="Vyberte univerzitu"
              @change="fetchFaculties($event)"
              required
            ></v-autocomplete>

            <!-- Výber fakulty -->
            <v-autocomplete
              v-model="selectedFaculty"
              :items="faculties"
              item-title="name"
              item-value="id"
              label="Fakulta"
              placeholder="Vyberte fakultu"
              @change="fetchDepartments($event)"
              required
            ></v-autocomplete>

            <!-- Výber katedry -->
            <v-autocomplete
              v-model="formData.department_id"
              :items="departments"
              item-title="name"
              item-value="id"
              label="Katedra alebo ústav"
              placeholder="Vyberte katedru"
              required
            ></v-autocomplete>

            <!-- Dátum vypršania -->
            <v-date-input v-model="formData.expiration_date" label="Dátum vypršania platnosti" required></v-date-input>

            <v-container class="d-flex justify-end">
              <v-btn color="primary" type="submit">Vytvoriť</v-btn>
            </v-container>
          </v-form>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted,watch } from "vue";
import axiosInstance from "../api/axiosInstance";
import { useRouter } from "vue-router";
import { VDateInput } from "vuetify/labs/VDateInput";

// Formulár
const formData = ref({
  name: "",
  type: "",
  department_id: null,
  expiration_date: null,
});

// Premenné pre výber univerzity, fakulty a katedry
const universities = ref([]);
const faculties = ref([]);
const departments = ref([]);
const selectedUniversity = ref(null);
const selectedFaculty = ref(null);

const router = useRouter();

watch(selectedUniversity, (newVal) => {
  if (newVal) fetchFaculties(newVal);
});

watch(selectedFaculty, (newVal) => {
  if (newVal) fetchDepartments(newVal);
});
// Fetch univerzít
const fetchUniversities = async () => {
  try {
    const response = await axiosInstance.get("/api/universities");
    universities.value = response.data;
  } catch (error) {
    console.error("Error fetching universities:", error);
    alert("Nepodarilo sa načítať zoznam univerzít.");
  }
};

// Fetch fakúlt
const fetchFaculties = async (universityId: number) => {
  try {
    faculties.value = []; // Reset fakúlt pri zmene univerzity
    departments.value = []; // Reset katedier pri zmene univerzity
    selectedFaculty.value = null; // Reset vybranej fakulty
    formData.value.department_id = null; // Reset vybranej katedry

    const response = await axiosInstance.get(`/api/faculties?uni_id=${universityId}`);
    faculties.value = response.data;
  } catch (error) {
    console.error("Error fetching faculties:", error);
    alert("Nepodarilo sa načítať zoznam fakúlt.");
  }
};

// Fetch katedier
const fetchDepartments = async (facultyId: number) => {
  try {
    departments.value = []; // Reset katedier pri zmene fakulty
    formData.value.department_id = null; // Reset vybranej katedry

    const response = await axiosInstance.get(`/api/departments?faculty_id=${facultyId}`);
    departments.value = response.data;
  } catch (error) {
    console.error("Error fetching departments:", error);
    alert("Nepodarilo sa načítať zoznam katedier.");
  }
};

// Vytvorenie konferencie
const createConference = async () => {
  try {
    const payload = new FormData();
    payload.append("name", formData.value.name);
    payload.append("type", formData.value.type);
    payload.append("department_id", String(formData.value.department_id));
    payload.append(
      "expiration_date",
      formData.value.expiration_date
        ? formData.value.expiration_date : ""
    );

    await axiosInstance.post("/api/conferences", payload);
    alert("Konferencia bola úspešne vytvorená!");
    router.push("/main");
  } catch (error) {
    console.error("Error creating conference:", error);
    alert("Nepodarilo sa vytvoriť konferenciu. Skúste znova.");
  }
};

// Načítanie univerzít pri načítaní stránky
onMounted(() => {
  fetchUniversities();
});
</script>

