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
        <!-- Formulár na pridanie práce -->
        <v-card class="pa-4 mb-4">

          <v-card-title class="headline text-center">Vytvoriť konferenciu</v-card-title>
          <v-form @submit.prevent="createConference" ref="form">
            <v-text-field
              v-model="formData.name"
              label="Názov"
              required
            ></v-text-field>
            <v-text-field
              v-model="formData.type"
              label="Typ"
              required
            ></v-text-field>
            <v-autocomplete
              v-model="formData.department_id"
              :items="departments"
              item-text="name"
              item-value="id"
              label="Katedra alebo ústav"
              placeholder="Vyberte..."
              required
            ></v-autocomplete>
            <v-date-input

              label="Dátum vypršania platnosti"
              required
            ></v-date-input>
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
import { ref } from "vue";
import axiosInstance from "../api/axiosInstance";
import { useRouter } from "vue-router";
import { VDateInput } from "vuetify/labs/VDateInput";

// Form data model
const formData = ref({
  name: "",
  type: "",
  department_id: null,
  expiration_date: "",
});

// Mock departments (replace with API fetch if necessary)
const departments = ref([
  { id: 1, name: "Department of Physics" },
  { id: 2, name: "Department of Mathematics" },
  { id: 3, name: "Department of Chemistry" },
]);

const router = useRouter();

const createConference = async () => {
  try {
    const payload = new FormData();
    payload.append("name", formData.value.name);
    payload.append("type", formData.value.type);
    payload.append("department_id", String(formData.value.department_id));
    payload.append("expiration_date", formData.value.expiration_date);

    // Post data to the API
    await axiosInstance.post("/api/conferences", payload);
    alert("Konferencia bola úspešne vytvorená!");
    router.push("/main"); // Redirect to main page or another route
  } catch (error) {
    console.error("Error creating conference:", error);
    alert("Nepodarilo sa vytvoriť konferenciu. Skúste znova.");
  }
};
</script>
