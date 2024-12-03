<template>
   <v-img
    :width="314"
    aspect-ratio="16/9"
    cover
    src="@/assets/logo.png"
  ></v-img>
  <v-container class="d-flex justify-center align-center" style="height: 70vh;">
    <v-card elevation="5" class="pa-5" width="400">
      <v-form @submit.prevent="handleLogin">
        <v-text-field
          v-model="email"
          label="Email"
          type="email"
          outlined
          required
          class="mb-3"
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="Heslo"
          type="password"
          outlined
          required
          class="mb-4"
        ></v-text-field>
        <v-btn
          color="primary"
          block
          large
          type="submit"
          class="mb-3"
        >
          Prihlasovanie
        </v-btn>
        <v-btn 
          color="secondary"
          block
          outlined
          @click="goToRegister"
        >
          Registrácia
        </v-btn>
      </v-form>
      <v-alert v-if="errorMessage" type="error" class="mt-3">
        {{ errorMessage }}
      </v-alert>
    </v-card>
  </v-container>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "../stores/authStore";
import "@/styles/LoginPage.css";
import { useRouter } from "vue-router";

export default {
  setup() {
    const email = ref("");
    const password = ref("");
    const errorMessage = ref("");
    const isLoading = ref(false);
    const authStore = useAuthStore();
    const router = useRouter(); // useRouter for navigation

    const goToRegister = () => {
      router.push('/register'); // using router.push from useRouter
    };

    const handleLogin = async () => {
      if (!email.value || !password.value) {
        errorMessage.value = "Both email and password are required.";
        return;
      }

      isLoading.value = true;
      try {
        await authStore.login(email.value, password.value);
        errorMessage.value = "";
        router.push("/main"); // redirect to main page on successful login
      } catch (error) {
        console.log(error);
        errorMessage.value = error.response?.data?.message || "Login failed. Please try again.";
      } finally {
        isLoading.value = false;
      }
    };

    return {
      email,
      password,
      errorMessage,
      isLoading,
      handleLogin,
      goToRegister, // expose the method to the template
    };
  },
};
</script>
