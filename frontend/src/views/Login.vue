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
    const authStore = useAuthStore();

    const handleLogin = async () => {
      try {
        await authStore.login(email.value, password.value);
      } catch (error) {
        errorMessage.value = "Login failed. Check your credentials.";
      }
    };

    return {
      email,
      password,
      errorMessage,
      handleLogin,
    };
  },
};
</script>
