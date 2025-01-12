<template>
  <v-app>
    <!-- Navbar -->
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <!-- Logo Section -->
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <!-- Title Section -->
          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">Admin Panel</v-toolbar-title>
          </v-col>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <v-btn href="/main">Home</v-btn>
            <v-btn href="/profile">Profile</v-btn>
            <v-btn href="/">Landing</v-btn>
<!--            <v-btn @click="handleLogout">Logout</v-btn>-->
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>
  </v-app>
  <v-main>
    <v-container>
      <!-- Kategória Detail -->
      <v-card class="pa-4 mb-4">
        <v-card-title>asd</v-card-title>
        <v-card-text>asd</v-card-text>
      </v-card>
<!--      <v-card class="pa-4 mb-4">-->
<!--        <v-card-title>{{ conference.name }}</v-card-title>-->
<!--        <v-card-text>{{ conference.description }}</v-card-text>-->
<!--      </v-card>-->
<!--      &lt;!&ndash; Formulár na pridanie práce &ndash;&gt;-->
<!--      <v-card class="pa-4 mb-4">-->
<!--        <v-card-title>Add Work</v-card-title>-->
<!--        <v-form @submit.prevent="submitWork">-->
<!--          <v-text-field v-model="work.title" label="Title" required></v-text-field>-->
<!--          <v-textarea v-model="work.description" label="Description" required></v-textarea>-->
<!--          <v-file-input v-model="files" label="Attach Files (Max 3)" multiple :rules="fileRules" counter show-size-->
<!--                        accept="image/*,application/pdf"></v-file-input>-->
<!--          <v-btn color="primary" type="submit" :loading="isLoading">Submit</v-btn>-->
<!--        </v-form>-->
<!--      </v-card>-->
    </v-container>
  </v-main>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";
const router = useRouter();
import { ref } from "vue";
import axiosInstance from "../api/axiosInstance";
</script>
