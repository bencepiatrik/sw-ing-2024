<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="handleLogin">
      <input v-model="email" placeholder="Enter your email" type="email" />
      <input v-model="password" placeholder="Enter your password" type="password" />
      <button type="submit" :disabled="isLoading">Login</button>
    </form>
    <p v-if="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "../stores/authStore";

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
        window.location.href = "/main"; // Redirect after login
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
