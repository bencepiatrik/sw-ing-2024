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
          <!-- Image Carousel -->
          <v-col cols="12" md="6">
            <v-carousel hide-delimiters height="400px">
              <v-carousel-item v-for="(image, index) in carouselImages" :key="index">
                <v-img :src="image.src" :alt="image.alt" class="carousel-image"></v-img>
              </v-carousel-item>
            </v-carousel>
          </v-col>

          <!-- Welcome Text and Buttons -->
          <v-col cols="12" md="6" class="d-flex justify-center">
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
      carouselImages: [
        { src: new URL('@/assets/image1.jpg', import.meta.url).href, alt: 'Image 1' },
        { src: new URL('@/assets/image2.jpg', import.meta.url).href, alt: 'Image 2' },
        { src: new URL('@/assets/image3.gif', import.meta.url).href, alt: 'Image 3' },
      ],

      showArticle: false, // To toggle the article modal
      currentArticleTitle: "", // To store the current article title
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

.carousel-image {
  border-radius: 10px;
  object-fit: cover;
}
.v-main {
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
