<template>
  <link
    href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css"
    rel="stylesheet"
  />
  <v-app>
    <!-- Navbar -->
    <v-app-bar app>
      <v-toolbar-title>Admin Panel</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text href="/main">Home</v-btn>
      <v-btn text href="/profile">Profile</v-btn>
      <v-btn text href="/">Landing</v-btn>
      <v-btn text @click="handleLogout">Logout</v-btn>
    </v-app-bar>

    <!-- Sidebar + Content Layout -->
    <v-main>
      <v-container fluid class="d-flex pa-0">
        <!-- Sidebar -->
        <v-navigation-drawer app permanent width="80" class="sidebar" color="#f5f5f5">
          <v-list dense>
            <!-- Toggle User Table -->
            <v-list-item link @click="showUsersTable = true">
              <v-icon large class="icon-color">mdi-account</v-icon>
            </v-list-item>
            <!-- Placeholder for News -->
            <v-list-item link>
              <v-icon large class="icon-color">mdi-newspaper</v-icon>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-container class="content-area">
          <!-- User Table -->
          <v-card v-if="showUsersTable" elevation="2" class="pa-4 ma-4">
            <v-card-title>User Management</v-card-title>
            <v-data-table
              :headers="headers"
              :items="users"
              class="elevation-1"
            >
              <!-- Action Buttons -->
              <template v-slot:item.actions="{ item }">
                <v-btn color="primary" @click="changeRole(item)">Change Role</v-btn>
                <v-btn color="red" dark @click="deleteUser(item)">Delete</v-btn>
              </template>
            </v-data-table>
          </v-card>

          <!-- Default Welcome Content -->
          <v-card v-else elevation="2" class="pa-5">
            <v-card-title class="headline text-center">
              Welcome to Admin Panel
            </v-card-title>
          </v-card>
        </v-container>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      showUsersTable: false, // Toggle for showing users table
      users: [
        { id: 1, role_id: 3, name: "John Doe", email: "john@example.com" },
        { id: 2, role_id: 2, name: "Jane Smith", email: "jane@example.com" },
        { id: 3, role_id: 1, name: "Alice Johnson", email: "alice@example.com" },
        { id: 4, role_id: 2, name: "Bob Brown", email: "bob@example.com" },
        { id: 5, role_id: 3, name: "Charlie Black", email: "charlie@example.com" },
      ],
      headers: [
        { text: "ID", value: "id" },
        { text: "Role ID", value: "role_id" },
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "Actions", value: "actions", sortable: false },
      ],
    };
  },
  methods: {
    handleLogout() {
      sessionStorage.removeItem("user");
      window.location.href = "/";
    },
    changeRole(user) {
      console.log(`Change role for user: ${user.name}`);
      alert(`Change role for user: ${user.name}`);
    },
    deleteUser(user) {
      console.log(`Delete user: ${user.name}`);
      alert(`Delete user: ${user.name}`);
    },
  },
};
</script>

<style scoped>
.sidebar {
  border-right: 1px solid #ddd;
}

.content-area {
  background-color: #f9f9f9;
  height: 100vh;
}

.v-card {
  width: auto;
  text-align: center;
}

.v-icon.icon-color {
  color: #424242; /* Dark gray color for visibility */
  margin: 15px auto;
  display: block;
}

.v-data-table {
  margin-top: 20px;
}
</style>
