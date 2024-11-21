<template>
    <div>
      <h1>Register</h1>
      <form @submit.prevent="handleRegister">
        <input v-model="name" placeholder="Name" type="text" required />
        <input v-model="email" placeholder="Email" type="email" required />
        <input v-model="password" placeholder="Password" type="password" required />
        <input v-model="passwordConfirmation" placeholder="Confirm Password" type="password" required />
        <button type="submit">Register</button>
      </form>
      <p v-if="errorMessage">{{ errorMessage }}</p>
      <p v-if="successMessage">{{ successMessage }}</p>
    </div>
</template>
  
<script>
import { ref } from "vue";
import axios from "../api/axiosInstance"; // Adjust the path to your Axios instance

export default {
  setup() {
    const name = ref("");
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
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value,
        });

        // Show success message
        successMessage.value = "Registration successful! You can now log in.";
        errorMessage.value = ""; // Clear any previous error messages

        // Reset the form fields
        name.value = "";
        email.value = "";
        password.value = "";
        passwordConfirmation.value = "";
      } catch (error) {
        // Handle errors
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
  