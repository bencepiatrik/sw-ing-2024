<template>
  <div>
    <!-- Loading Overlay -->
    <div v-if="isLoading" class="loading-overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </div>

    <!-- Register Form -->
    <v-container
      class="d-flex justify-center align-center"
      style="height: 70vh;"
      :class="{ 'blur-background': isLoading }"
    >
      <v-card elevation="4" class="pa-4" width="350">
        <v-img src="@/assets/logo.png" alt="Econf Logo" class="mb-4" max-height="100"></v-img>
        <v-form @submit.prevent="handleRegister">
          <v-text-field
            v-model="name"
            label="Meno"
            type="text"
            outlined
            required
            class="mb-1"
            :disabled="isLoading"
          ></v-text-field>
          <v-text-field
            v-model="surname"
            label="Priezvisko"
            type="text"
            outlined
            required
            class="mb-1"
            :disabled="isLoading"
          ></v-text-field>
          <v-text-field
            v-model="email"
            label="Email"
            type="email"
            outlined
            required
            class="mb-1"
            :disabled="isLoading"
          ></v-text-field>
          <v-text-field
            v-model="password"
            label="Heslo"
            type="password"
            outlined
            required
            class="mb-1"
            :disabled="isLoading"
          ></v-text-field>
          <v-text-field
            v-model="passwordConfirmation"
            label="Heslo ešte raz"
            type="password"
            outlined
            required
            class="mb-1"
            :disabled="isLoading"
          ></v-text-field>
          <v-btn
            color="primary"
            block
            large
            type="submit"
            class="mb-1"
            :disabled="isLoading"
          >
            Registrácia
          </v-btn>
        </v-form>
        <RouterLink to="/login">
          <v-btn
            color="secondary"
            block
            outlined
            :disabled="isLoading"
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
  </div>
</template>

<script>
import { ref } from "vue";
import axios from "../api/axiosInstance";
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
    const isLoading = ref(false); // Loading state

    const handleRegister = async () => {
      if (!name.value || !surname.value || !email.value || !password.value || !passwordConfirmation.value) {
        errorMessage.value = "All fields are required.";
        return;
      }

      isLoading.value = true; // Show loading spinner
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
      } finally {
        isLoading.value = false; // Hide loading spinner
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
      isLoading,
      handleRegister,
    };
  },
};
</script>

<style scoped>

  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  
  .blur-background {
    filter: blur(5px);
    pointer-events: none;
  }
  
  .v-progress-circular {
    color: #007bff;
  }
</style>

