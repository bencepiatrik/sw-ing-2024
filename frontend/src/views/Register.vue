<template>
  <v-container class="d-flex justify-center align-center" style="height: 70vh;">
    <v-card elevation="4" class="pa-4" width="350"> <!-- Reduced width and padding -->
      <v-img src="@/assets/logo.png" alt="Econf Logo" class="mb-4" max-height="100"></v-img>
      <v-form @submit.prevent="handleRegister">
        <v-text-field
          v-model="name"
          label="Meno"
          type="text"
          outlined
          required
          class="mb-1"
        ></v-text-field>
        <v-text-field
          v-model="surname"
          label="Priezvisko"
          type="text"
          outlined
          required
          class="mb-1"
        ></v-text-field>
        <v-text-field
          v-model="email"
          label="Email"
          type="email"
          outlined
          required
          class="mb-1"
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="Heslo"
          type="password"
          outlined
          required
          class="mb-1"
        ></v-text-field>
        <v-text-field
          v-model="passwordConfirmation"
          label="Heslo ešte raz"
          type="password"
          outlined
          required
          class="mb-1"
        ></v-text-field>
        <v-btn
          color="primary"
          block
          large
          type="submit"
          class="mb-1"
        >
          Registrácia
        </v-btn>
      </v-form>
      <RouterLink to="/login">
        <v-btn
          color="secondary"
          block
          outlined
        >
          Späť na prihlasovanie
        </v-btn>
      </RouterLink>
      <v-alert v-if="successMessage" type="success" class="mt-2">
        {{ successMessage }}
      </v-alert>
      <v-alert v-if="errorMessage" type="error" class="mt-2">
        {{ errorMessage }}
      </v-alert>
    </v-card>
  </v-container>
</template>

<script>
import { ref } from "vue";
import axios from "../api/axiosInstance";
import "@/styles/RegisterPage.css";
import { RouterLink } from "vue-router";

export default {
  setup() {
    const name = ref("");
    const surname = ref("");
    const email = ref("");
    const password = ref("");
    const passwordConfirmation = ref("");
    const errorMessage = ref("");
    const successMessage = ref("");

    const handleRegister = async () => {
      try {
        // Fetch CSRF token first
        await axios.get('/sanctum/csrf-cookie', { withCredentials: true });

        // Send registration data to the backend
        const response = await axios.post('/register', {
          name: name.value,
          surname: surname.value,
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value,
        });

        // Show success message
        successMessage.value = response.data.message || "Registration successful! You can now log in.";
        errorMessage.value = ""; // Clear any previous error messages

        // Reset the form fields
        name.value = "";
        surname.value = "";
        email.value = "";
        password.value = "";
        passwordConfirmation.value = "";
      } catch (error) {
        // Handle errors
        console.error(error);
        errorMessage.value =
          error.response?.data?.message || "Registration failed. Please try again.";
        successMessage.value = ""; // Clear any previous success messages
      }
    };

    return {
      name,
      surname,
      email,
      password,
      passwordConfirmation,
      errorMessage,
      successMessage,
      handleRegister,
    };
  },
};
</script>
