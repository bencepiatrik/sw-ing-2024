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

        <v-row>
          <v-col
            v-for="(newsItem, index) in news"
            :key="newsItem.id"
            cols="12"
            sm="4"
          >
            <v-card class="pa-3 text-center" outlined>
              <v-card-title>{{ newsItem.title }}</v-card-title>
              <v-card-text>{{ newsItem.text }}</v-card-text>
              <v-btn color="primary" @click="openArticlePage(newsItem.title)">
                READ ARTICLE
              </v-btn>
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

 <!-- Article -->
 <v-dialog v-model="showArticle" max-width="800px">
      <v-card>
        <v-card-title>{{ currentArticleTitle }}</v-card-title>
        <v-card-text>
          <!-- Article content can be dynamically loaded here -->
          <p>Article content for {{ currentArticleTitle }} goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
        </v-card-text>
        <v-card-actions class="d-flex justify-center">
          <v-btn color="blue" @click="showArticle = false" class="ma-4">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axiosInstance from "@/api/axiosInstance";
import { useAuthStore } from "../stores/authStore";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  data() {
    return {
      currentArticleTitle: '', // Added to hold the title of the current article
      showArticle: false, // Added to control modal visibility
      news: [],
      features: [
        { title: "Tutorial", description: "Tutorial to the webpage" },
        { title: "Welcome", description: "Welcome to our page" },
        { title: "To do..", description: "To do" },
      ],
      carouselImages: [
        { src: new URL('@/assets/tiger.png', import.meta.url).href, alt: 'Image 1' },
        { src: new URL('@/assets/image2.jpg', import.meta.url).href, alt: 'Image 2' },
        { src: new URL('@/assets/image3.gif', import.meta.url).href, alt: 'Image 3' },
      ],
    };
  },
  mounted() {
    this.fetchNews();
  },
  methods: {
    async fetchNews() {
      try {
        const response = await axiosInstance.get("/api/news");
        this.news = response.data;
      } catch (error) {
        console.error("Error fetching news:", error);
      }
    },
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

.v-dialog .v-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.v-card-actions {
  margin-top: auto; 
  justify-content: center;
}
</style>
