<template>
  <div class="login-container">
    <div class="login-box">
      <img class="logo" src="@/assets/logo.png" alt="Econf Logo" />
      <form @submit.prevent="handleLogin">
        <input v-model="email" placeholder="Email" type="email" class="input" />
        <input v-model="password" placeholder="Heslo" type="password" class="input" />
        <RouterLink to="/">
          <button type="submit" class="btn">Prihlasovanie</button>
        </RouterLink>
        <RouterLink to="/register">
        <button @click="goToRegister" class="btn-register">Registrácia</button>
        </RouterLink>
      </form>
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "../stores/authStore";
import "@/styles/LoginPage.css";

export default {
  setup() {
    const email = ref("");
    const password = ref("");
    const errorMessage = ref("");
    const isLoading = ref(false);
    const authStore = useAuthStore();

    const handleLogin = async () => {
      if (!email.value || !password.value) {
        errorMessage.value = "Both email and password are required.";
        return;
      }

      isLoading.value = true;
      try {
        await authStore.login(email.value, password.value);
        errorMessage.value = "";
        window.location.href = "/dashboard"; // Redirect after login
      } catch (error) {
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
    };
  },
};
</script>
