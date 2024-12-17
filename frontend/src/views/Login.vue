<template>
  <div>
    <!-- Loading Overlay -->
    <div v-if="isLoading" class="loading-overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </div>

    <!-- Login Form -->
    <v-container
  class="d-flex justify-center align-center"
  style="height: 100vh; margin-top: -200px;"
  :class="{ 'blur-background': isLoading }">
  <v-card elevation="5" class="pa-5" width="400">
    <v-img :width="300" aspect-ratio="16/9" cover src="/logo.jpg"class="mx-auto"/> <br>
        <v-form @submit.prevent="handleLogin">
          <v-text-field
            v-model="email"
            label="Email"
            type="email"
            outlined
            required
            class="mb-3"
            :disabled="isLoading"
          ></v-text-field>
          <v-text-field
            v-model="password"
            label="Heslo"
            type="password"
            outlined
            required
            class="mb-4"
            :disabled="isLoading"
          ></v-text-field>
          <v-btn
            color="primary"
            block
            large
            type="submit"
            class="mb-3"
            :disabled="isLoading"
          > <v-icon icon="mdi-login" />
            Prihlasovanie
          </v-btn>
          <v-btn
            color="secondary"
            block
            outlined
            @click="goToRegister"
            :disabled="isLoading"
          > <v-icon icon="mdi-account-plus" />
            Registrácia
          </v-btn>
        </v-form>
        <v-alert v-if="errorMessage" type="error" class="mt-3">
          {{ errorMessage }}
        </v-alert>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "../stores/authStore";
import { useRouter } from "vue-router";

export default {
  setup() {
    const email = ref("");
    const password = ref("");
    const errorMessage = ref("");
    const isLoading = ref(false);
    const authStore = useAuthStore();
    const router = useRouter();

    const goToRegister = () => {
      router.push("/register");
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
        router.push("/main");
      } catch (error) {
        console.log(error);
        errorMessage.value =
          error.response?.data?.message || "Login failed. Please try again.";
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
      goToRegister,
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

.body {
  background-color: #007bff;
}

.blur-background {
  filter: blur(5px);
  pointer-events: none;
}

.v-progress-circular {
  color: #007bff;
}
</style>

