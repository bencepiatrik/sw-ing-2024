<template>
  <v-app style="background-color: #D9DCD6; ">
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
            <v-btn href="/main">Domov</v-btn>
            <v-btn href="/profile">Profil</v-btn>
            <v-btn href="/">Landing</v-btn>
            <v-btn @click="handleLogout">Odhlasit sa</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <!-- Sidebar + Content Layout -->
    <v-main fluid class="pa-4" style="background-color: #D9DCD6;" >
      <v-container fluid class="d-flex pa-0">
        <!-- Main Content -->
        <v-row justify="space-between" v-if="!showNotifications && !showConferences && !showUsersTable">
          <!-- Notification Card -->
          <v-col cols="4">
            <v-card elevation="3" class="pa-4 text-center">
              <v-icon size="50" class="mb-3">mdi-bell</v-icon>
              <v-card-title>Notifikácie</v-card-title>
              <v-btn color="primary" @click="fetchNotifications">Zobraziť</v-btn>
            </v-card>
          </v-col>

          <!-- Conferences Card -->
          <v-col cols="4">
            <v-card elevation="3" class="pa-4 text-center">
              <v-icon size="50" class="mb-3">mdi-shape</v-icon>
              <v-card-title>Konferencie</v-card-title>
              <v-btn color="primary" @click="fetchConferences">Zobraziť</v-btn>
            </v-card>
          </v-col>

          <!-- Users Card -->
          <v-col cols="4">
            <v-card elevation="3" class="pa-4 text-center">
              <v-icon size="50" class="mb-3">mdi-account</v-icon>
              <v-card-title>Používatelia</v-card-title>
              <v-btn color="primary" @click="fetchUsers">Zobraziť</v-btn>
            </v-card>
          </v-col>
        </v-row>

        <!-- Sidebar -->
        <v-navigation-drawer app permanent width="80" class="sidebar">
          <!-- Notifications -->
          <v-list dense>
            <v-list-item link height="100" @click="fetchNotifications" class="d-flex justify-center">
              <v-icon large>mdi-bell</v-icon>
            </v-list-item>
          </v-list>
          <!-- Conferencies -->
          <v-list dense>
            <v-list-item link height="100" @click="fetchConferences" class="d-flex justify-center">
              <v-icon large>mdi-shape</v-icon>
            </v-list-item>
          </v-list>
          <!-- Users -->
          <v-list dense>
            <v-list-item link height="100" @click="fetchUsers" class="d-flex justify-center">
              <v-icon large>mdi-account</v-icon>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-container v-if="showNotifications" style="width: 80vw;">
          <v-card elevation="2" class="pa-4" style="border-radius: 10px; width: 60vw;">
            <v-card-title class="headline text-center">Notifikácie</v-card-title>
            <v-data-table :headers="notificationHeaders" :items="notifications" class="elevation-1">
              <template v-slot:item="{ item }">
                <tr>
                  <td>{{ item.type }}</td>
                  <td>{{ item.user_email }}</td>
                  <td>{{ item.university }}</td>
                  <td>{{ item.faculty }}</td>
                  <td>{{ item.department }}</td>
                  <td>{{ item.state }}</td>
                  <td>{{ new Date(item.created_at).toLocaleString() }}</td>
                  <td class="text-center">
                    <div class="d-flex justify-center">
                      <v-btn color="green" dark class="mx-2" v-if="item.state === 'sent'"
                        @click="updateNotificationState(item, 'accepted')">
                        Akceptovať
                      </v-btn>
                      <v-btn color="red" dark class="mx-2" v-if="item.state === 'sent'"
                        @click="updateNotificationState(item, 'declined')">
                        Zrušiť
                      </v-btn>
                    </div>
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-card>
        </v-container>

        <!-- Conference Table -->
        <v-card v-if="showConferences" elevation="2" class="pa-4" style="border-radius: 10px; width: 60vw;">
          <v-card-title class="headline text-center">Konferencie</v-card-title>
          <v-btn color="primary" class="mb-4" @click="CreateConference">
            Pridať konferenciu
          </v-btn>
          <v-data-table :headers="headers" :items="conferences" class="elevation-1">
            <template v-slot:item="{ item }">
              <tr @click="goToConference(item.id)" style="cursor: pointer;">
                <td>{{ item.name }}</td>
                <td>{{ item.year }}</td>
                <td>{{ item.type }}</td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
        <!-- User Table -->
        <v-card v-if="showUsersTable" elevation="2" class="pa-4" style="border-radius: 10px; width: 60vw;">
          <v-card-title class="headline text-center">Správa používateľov</v-card-title>
          <v-data-table :headers="headers" :items="users" class="elevation-1">
            <template v-slot:item="{ item }">
              <tr>
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.role }}</td>
                <td class="d-flex justify-end">
                  <v-btn color="primary" class="mx-2" @click="openChangeRoleDialog(item)">
                    Zmena roly
                  </v-btn>
                  <v-btn color="red" dark class="mx-2" @click="deleteUser(item)">
                    Vymazať
                  </v-btn>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
      </v-container>
    </v-main>

    <!-- Change Role Dialog -->
    <v-dialog v-model="showChangeRoleDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Zmena roly</v-card-title>
        <v-card-text>
          <v-select v-model="selectedRole" :items="roles" label="Select Role" item-text="name" item-value="name">
          </v-select>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" @click="confirmChangeRole">Uložiť</v-btn>
          <v-btn color="red darken-1" @click="closeDialog">Zrušiť</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";
const router = useRouter();
import { ref } from "vue";
import axiosInstance from "../api/axiosInstance";

type NotificationItem = {
  type: string;
  user_email: string;
  university: string;
  faculty: string;
  department: string;
  state: string;
  created_at: string;
};

const notifications = ref<NotificationItem[]>([]);

// Reactive variables
const showNotifications = ref(false); // Show conference table toggle
const showConferences = ref(false); // Show conference table toggle
const showUsersTable = ref(false); // Show user table toggle

const notification = ref<
  { id: number; name: string }[]
>([]);

const conferences = ref<
  { id: number; name: string; year: number; type: string }[]
>([]);

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
//const notifications = ref([]);
const notificationHeaders = [
  { text: "Type", value: "type" },
  { text: "User Email", value: "user_email" },
  { text: "University", value: "university" },
  { text: "Faculty", value: "faculty" },
  { text: "Department", value: "department" },
  { text: "State", value: "state" },
  { text: "Created At", value: "created_at" },
  { text: "Actions", value: "actions", sortable: false },
]



const fetchNotifications = async () => {
  showUsersTable.value = false;
  showConferences.value = false;
  showNotifications.value = true;
  try {
    const response = await axiosInstance.get("/api/notifications");
    notifications.value = response.data; // Assign all fields directly

    console.log(response)
  } catch (error) {
    console.error("Error fetching notifications:", error);
  }
};

const fetchConferences = async () => {
  showNotifications.value = false;
  showUsersTable.value = false;
  showConferences.value = true;
  try {
    const response = await axiosInstance.get("/api/conferences");
    conferences.value = response.data.map((conference: { id: number; name: string; year: number; type: string }) => ({
      ...conference,
    }));
  } catch (error) {
    console.error("Error fetching conferences:", error);
  }
};

const goToConference = (conferenceId: number) => {
  router.push(`/adminconference/${conferenceId}`);
};

const fetchUsers = async () => {
  showNotifications.value = false;
  showConferences.value = false;
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
    1: "neschvaleny pouzivatel",
    2: "schvaleny pouzivatel",
    3: "autor",
    4: "recenzent",
    5: "admin",
  };
  return roles[role_id] || "unknown";
};

const CreateConference = () => {
  router.push('/createconference');
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

const updateNotificationState = async (notification, newState) => {
  try {
    await axiosInstance.put(`/api/notifications/${notification.id}/state`, { state: newState });
    notification.state = newState;
    alert(`Notification has been ${newState}.`);
  } catch (error) {
    console.error('Error updating notification state:', error);
    alert('Failed to update notification state. Please try again.');
  }
};

</script>


<style scoped>
.sidebar {
  background-color: #F5F5F5;
  overflow: hidden;
}

.icon-color {
  color: #2D627F;
}

.content-area {
  flex: 1;
}

.v-data-table {
  border-radius: 10px;
  overflow: hidden;
}

content-section {
  width: 70vw;
  margin: 0 auto;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.content-card {
  width: 100%;
  max-width: 60vw;
  margin: 0 auto;
  border-radius: 10px;
}

/* Zabraňuje globálnemu skrolovaniu na celej stránke */
html, body {
  height: 100%; /* Nastaví výšku na 100% */
  margin: 0;
  padding: 0;
  overflow: hidden; /* Zabraňuje skrolovaniu */
}

/* Nastavuje aplikáciu, aby zaberala celú výšku stránky */
v-app {
  height: 100%; /* Nastaví výšku aplikácie na celú stránku */
  display: flex;
  flex-direction: column;
  overflow: hidden; /* Zakazuje skrolovanie */
}

/* Nastaví hlavný obsah na maximálnu výšku okna s možnosťou skrolovania len vnútri hlavného obsahu */
.v-main {
  flex: 1;
  overflow-y: auto; /* Skrolovanie je povolené len v hlavnej oblasti */
  padding: 16px;
}

</style>
