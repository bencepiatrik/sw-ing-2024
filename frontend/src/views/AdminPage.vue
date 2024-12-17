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
            <v-list-item link @click="fetchUsers">
              <v-icon large class="icon-color">mdi-account</v-icon>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-container class="content-area">
          <!-- User Table -->
          <v-card v-if="showUsersTable" elevation="2" class="pa-4 ma-4">
            <v-card-title class="headline text-center">User Management</v-card-title>
            <v-data-table :headers="headers" :items="users" class="elevation-1">
              <template v-slot:item.actions="{ item }">
                <v-btn color="primary" class="mx-2" @click="openChangeRoleDialog(item)">
                  Change Role
                </v-btn>
                <v-btn color="red" dark class="mx-2" @click="deleteUser(item)">
                  Delete
                </v-btn>
              </template>
            </v-data-table>
          </v-card>
        </v-container>
      </v-container>
    </v-main>

    <!-- Change Role Dialog -->
    <v-dialog v-model="showChangeRoleDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Change Role</v-card-title>
        <v-card-text>
          <v-select
            v-model="selectedRole"
            :items="roles"
            label="Select a role"
            item-text="name"
            item-value="name"
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="confirmChangeRole">Save</v-btn>
          <v-btn color="red darken-1" text @click="closeDialog">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axiosInstance from "@/api/axiosInstance";

export default {
  data() {
    return {
      showUsersTable: false, // Show user table toggle
      users: [],
      headers: [
        { text: "User ID", value: "id" },
        { text: "Role ID", value: "role_id" },
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      showChangeRoleDialog: false, // Dialog visibility toggle
      selectedRole: "", // Currently selected role
      selectedUser: null, // User whose role is being changed
      roles: ["user", "reviewer", "admin"], // Hardcoded role options
    };
  },
  methods: {
    handleLogout() {
      sessionStorage.removeItem("user");
      window.location.href = "/";
    },
    async fetchUsers() {
      this.showUsersTable = true;
      try {
        const response = await axiosInstance.get("/api/users");
        this.users = response.data;
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    openChangeRoleDialog(user) {
      this.selectedUser = user;
      this.selectedRole = user.role || "user";
      this.showChangeRoleDialog = true;
    },
    async confirmChangeRole() {
      if (!this.selectedRole || !this.selectedUser) return;
      try {
        await axiosInstance.post(`/api/users/${this.selectedUser.id}/change-role`, {
          role: this.selectedRole,
        });
        alert("Role changed successfully!");
        this.fetchUsers();
        this.closeDialog();
      } catch (error) {
        console.error("Error changing role:", error);
        alert("Failed to change role");
      }
    },
    deleteUser(user) {
      if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        axiosInstance.delete(`/api/users/${user.id}`).then(() => {
          this.fetchUsers();
          alert("User deleted successfully!");
        });
      }
    },
    closeDialog() {
      this.showChangeRoleDialog = false;
      this.selectedRole = "";
      this.selectedUser = null;
    },
  },
};
</script>

<style scoped>
.icon-color {
  color: #555;
}
</style>
