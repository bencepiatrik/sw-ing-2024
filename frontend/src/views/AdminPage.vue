<template>
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet" />
  <v-app>
    
    <!-- Navbar -->
    <v-app-bar app color="#2D627F" dark>
  <v-container fluid>
    <v-row align="center" no-gutters>
      <!-- Logo Section -->
      <v-col cols="1" class="d-flex justify-start align-center">
        <v-img 
          :src="'/logo.png'" 
          contain 
          style="height: auto; width: auto;" 
        />
      </v-col>

      <!-- Title Section -->
      <v-col cols="8" class="d-flex justify-center align-center">
        <v-toolbar-title class="text-h6">Admin Panel</v-toolbar-title>
      </v-col>

      <!-- Spacer for Buttons -->
      <v-spacer></v-spacer>

      <!-- Buttons Section -->
      <v-col cols="2" class="d-flex justify-end align-center">
        <v-btn variant="text" href="/main">Home</v-btn>
        <v-btn variant="text" href="/profile">Profile</v-btn>
        <v-btn variant="text" href="/">Landing</v-btn>
        <v-btn variant="text" @click="handleLogout">Logout</v-btn>
      </v-col>
    </v-row>
  </v-container>
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
          <v-select v-model="selectedRole" :items="roles" item-text="name" item-value="name"></v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" variant="text" @click="confirmChangeRole">Save</v-btn>
          <v-btn color="red darken-1" variant="text" @click="closeDialog">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script setup lang="ts">
import { ref } from "vue";
import axiosInstance from "../api/axiosInstance";

// Reactive variables
const showUsersTable = ref(false); // Show user table toggle
const users = ref<
  { id: number; role_id: number; role: string; name: string; email: string }[]
>([]);

const headers = ref([
  { text: "User ID", value: "id" },
  { text: "Role ID", value: "role_id" },
  { text: "Name", value: "name" },
  { text: "Email", value: "email" },
  { text: "Actions", value: "actions", sortable: false },
]);

const showChangeRoleDialog = ref(false); // Dialog visibility toggle
const selectedRole = ref<string>(""); // Currently selected role
const selectedUser = ref<{ id: number; role: string; name: string } | null>(null); // User whose role is being changed
const roles = ref(["user", "reviewer", "admin"]); // Hardcoded role options

// Methods
const handleLogout = () => {
  sessionStorage.removeItem("user");
  window.location.href = "/";
};

const fetchUsers = async () => {
  showUsersTable.value = true;
  try {
    const response = await axiosInstance.get("/api/users");
    users.value = response.data.map((user: { id: number; role_id: number; name: string; email: string }) => ({
      ...user,
      role: mapRoleIdToRole(user.role_id), // Pridaj vlastnosť `role`
    }));
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};

const mapRoleIdToRole = (role_id: number): string => {
  const roles = {
    1: "user",
    2: "reviewer",
    3: "admin",
  };
  return roles[role_id] || "unknown";
};


const openChangeRoleDialog = (user: { id: number; role: string; name: string }) => {
  selectedUser.value = user;
  selectedRole.value = user.role || "user";
  showChangeRoleDialog.value = true;
};

const confirmChangeRole = async () => {
  if (!selectedRole.value || !selectedUser.value) return;
  try {
    await axiosInstance.post(`/api/users/${selectedUser.value.id}/change-role`, {
      role: selectedRole.value,
    });
    alert("Role changed successfully!");
    fetchUsers();
    closeDialog();
  } catch (error) {
    console.error("Error changing role:", error);
    alert("Failed to change role");
  }
};

const deleteUser = (user: { id: number; name: string }) => {
  if (confirm(`Are you sure you want to delete ${user.name}?`)) {
    axiosInstance.delete(`/api/users/${user.id}`).then(() => {
      fetchUsers();
      alert("User deleted successfully!");
    });
  }
};

const closeDialog = () => {
  showChangeRoleDialog.value = false;
  selectedRole.value = "";
  selectedUser.value = null;
};
</script>


<style scoped>
.icon-color {
  color: #555;
}
</style>
