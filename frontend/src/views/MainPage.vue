<template>
  <!--
  <div>
    <nav>
      <ul>
        <li><a href="/">Landing</a></li>
        <li><a href="/profile">Profile</a></li>
        <li><a href="#" @click="handleLogout">Logout</a></li>
      </ul>
    </nav>
    <main>
      <h1>Welcome to the Main Page</h1>
      <p>You are now logged in.</p>
    </main>
  </div>
  -->
  <v-app>
    <v-app-bar app>
      <v-toolbar-title>Main Page</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text href="/profile">Profile</v-btn>
      <v-btn text href="/">Landing</v-btn>
      <v-btn text @click="handleLogout">Logout</v-btn>
    </v-app-bar>

    <v-main>
      <v-container class="text-center mt-10">
        <v-row>
          <v-col>
            <v-card elevation="2" class="pa-5">
              <v-card-title class="headline">Welcome to the Main Page</v-card-title>
              <v-card-text>
                <p>You are now logged in.</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { useAuthStore } from "../stores/authStore";

export default {
  setup() {
    const authStore = useAuthStore();

    const handleLogout = () => {
      authStore.logout();
      window.location.href = "/";
    };

    return {
      handleLogout,
    };
  },
};
</script>

<style scoped>

</style>
