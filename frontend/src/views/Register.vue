<template>
  <div class="register-container">
    <div class="register-box">
      <img class="logo" src="@/assets/logo.png" alt="Econf Logo" />
      <form @submit.prevent="handleRegister">
        <input v-model="name" placeholder="Meno" type="text" class="input" />
        <input v-model="surname" placeholder="Priezvisko" type="text" class="input" />
        <input v-model="email" placeholder="Email" type="email" class="input" />
        <input v-model="password" placeholder="Heslo" type="password" class="input" />
        <input v-model="passwordConfirmation" placeholder="Heslo este raz" type="password" class="input" />
        <RouterLink to="/">
          <button type="submit" class="btn">Registracia</button>
        </RouterLink>
        <RouterLink to="/login">
          <button @click="goToLogin" class="btn-login">Spät na prihlasovanie</button>
        </RouterLink>
      </form>
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </div>
  </div>
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
        // Send registration data to the backend
        await axios.post("/register", {
          name: name.value,
          surname: surname.value,
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value,
        });

        // Show success message
        successMessage.value = "Registration successful! You can now log in.";
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