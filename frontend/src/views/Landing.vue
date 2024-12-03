<template>
  <v-app>
    <!-- App Bar -->
    <v-app-bar app color="blue" dark>
      <v-toolbar-title>E-Conf</v-toolbar-title>
    </v-app-bar>

    <!-- Hero Section -->
    <v-main>
      <v-container>
        <v-row>
          <v-col cols="12" md="6">
            <v-img src="./assets/logo.png" alt="Welcome Image" class="mb-4" height="400px"></v-img>
          </v-col>
          <v-col cols="12" md="6" class="d-flex justify-center" style="width: 600px; height: 300px;">
            <v-card class="pa-8" outlined>
              <v-card-title class="justify-center">Vitajte na našej úvodnej stránke!</v-card-title>
              <v-card-subtitle class="text-center">Sme radi, že ste tu.</v-card-subtitle>
              <v-btn color="primary" class="mx-10" @click="goToLoginPage">Login</v-btn>
              <v-btn color="secondary" class="mx-10" @click="goToRegisterPage">Register</v-btn>
            </v-card>
          </v-col>
        </v-row>

        <!-- Features Section -->
        <v-row>
          <v-col v-for="feature in features" :key="feature.title" cols="12" md="4">
            <v-card class="pa-8" style="height: 250px;">
              <v-card-title>{{ feature.title }}</v-card-title>
              <v-card-subtitle>{{ feature.description }}</v-card-subtitle>
              <v-btn color="primary" @click="openArticlePage(feature.title)">Read Article</v-btn>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <!-- Footer -->
    <v-footer color="blue" dark app>
      <v-col class="text-center">
        <span>© 2024 E-Conf. All Rights Reserved.</span>
      </v-col>
    </v-footer>

    <!-- Article Modal / Overlay -->
    <v-dialog v-model="showArticle" max-width="800px">
      <v-card>
        <v-card-title>{{ currentArticleTitle }}</v-card-title>
        <v-card-text>
          <!-- Article content can be dynamically loaded here -->
          <p>Article content for {{ currentArticleTitle }} goes here.</p>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue" @click="showArticle = false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import { useAuthStore } from "../stores/authStore";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  data() {
    return {
      features: [
        { title: "Tutorial", description: "Tutorial to the webpage" },
        { title: "Welcome", description: "Welcome to our page" },
        { title: "To do..", description: "To do" },
      ],
      showArticle: false, // To toggle the article modal
      currentArticleTitle: '', // To store the current article title
    };
  },
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();

    // Check if the user is authenticated on mounted
    onMounted(() => {
      if (authStore.isAuthenticated) {
        router.push({ name: "MainPage" });
      }
    });

    return {};
  },
  methods: {
    goToLoginPage() {
      // Navigate to login page
      this.$router.push("/login");
    },
    goToRegisterPage() {
      // Navigate to register page
      this.$router.push("/register");
    },
    openArticlePage(title) {
      this.currentArticleTitle = title; // Set the article title dynamically
      this.showArticle = true; // Show the article modal
    },
  },
};
</script>

<style scoped>
.v-main {
  background-color: #f5f5f5;
}
.v-card {
  transition: all 0.3s ease;
}
.v-card:hover {
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.v-btn {
  margin: 0 auto;
  margin-left: 25%;
  margin-top: 25%;
}
</style>