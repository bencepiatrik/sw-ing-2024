<template>
  <v-app style="background-color: #D9DCD6;">
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
            <v-toolbar-title class="text-h6">MÔJ PROFIL</v-toolbar-title>
          </v-col>

          <!-- Spacer for Buttons -->
          <v-spacer></v-spacer>

          <!-- Buttons Section -->
          <v-col cols="3" class="d-flex justify-end align-center">
            <!--<v-btn variant="text" href="/">Landing</v-btn>-->
            <v-btn variant="text" href="/main">Domov</v-btn>
            <v-btn variant="text" href="/admin" v-if="user && user.role_id === 5">Admin Panel</v-btn>
            <v-btn variant="text" href="/ziadosti">Žiadosti</v-btn>
            <v-btn variant="text" @click="handleLogout">Odhlásit sa</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <div class="profile">
      <div class="profile-editor">
        <div class="profile-card">
          <h1>Profil</h1>
          <div v-if="user && user.id">
            <p><strong>Id: </strong> {{ user.id }}</p>
            <p><strong>Meno: </strong> {{ user.name }}</p>
            <p><strong>Priezvisko: </strong> {{ user.surname }}</p>
            <p><strong>Rola: </strong> {{ user.role ? user.role.name : 'N/A' }}</p>
            <p><strong>E-mail: </strong> {{ user.email }}</p>
            <p>
              <strong>E-mail verifikovaný: </strong>
              <span :class="{ 'verified': user.email_verified_at, 'not-verified': !user.email_verified_at }">
                {{ user.email_verified_at ? 'Ano' : 'Nie' }}
              </span>
            </p>
            <p><strong>Dátum vytvorenie:</strong> {{ new Date(user.created_at).toLocaleString() }}</p>
            <p><strong>Naposledy zmenené:</strong> {{ new Date(user.updated_at).toLocaleString() }}</p>
          </div>
          <div v-else>
            <p>Načítavanie údajov používateľa, počkajte, prosím..</p>
          </div>
          <div class="btn-container">
            <div class="button-container">
              <button @click="goToEditProfile">Zmeniť Profil</button>
            </div>
            <!-- <div class="button-container">
              <button :disabled="needsWorkplace" @click="goToEditWorkplace">Zmeniť Pracovisko</button>
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <!-- Pracovisko Form -->
    <div class="pracovisko-form">
      <h2>Pracovisko</h2>
      <form @submit.prevent="submitWorkplace">
        <div class="form-group">
          <label for="univerzita">Univerzita</label>
          <select id="univerzita" v-model="selectedUniverzita" @change="fetchFakulty">
            <option value="" disabled selected>Vyberte univerzitu</option>
            <option v-for="univerzita in univerzity" :key="univerzita.id" :value="univerzita.id">
              {{ univerzita.name }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="fakulta">Fakulta</label>
          <select id="fakulta" v-model="selectedFakulta" :disabled="!selectedUniverzita" @change="fetchKatedry">
            <option value="" disabled selected>Vyberte fakultu</option>
            <option v-for="fakulta in fakulty" :key="fakulta.id" :value="fakulta.id">
              {{ fakulta.name }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="katedra">Katedra/Ústav</label>
          <select id="katedra" v-model="selectedKatedra" :disabled="!selectedFakulta">
            <option value="" disabled selected>Vyberte katedru/ústav</option>
            <option v-for="katedra in katedry" :key="katedra.id" :value="katedra.id">
              {{ katedra.name }}
            </option>
          </select>
        </div>
        <div class="button-container">
        <button type="submit" :disabled="!selectedKatedra">Požiadat zmenu pracoviska</button>
      </div>
      </form>
    </div>
  </v-app>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';
import { computed, onMounted, ref } from 'vue';
import axiosInstance from '../api/axiosInstance';


// Router and store setup
const router = useRouter();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const needsWorkplace = computed(() => {
  return !user.value?.departments || user.value.departments.length === 0;
});

// Navigation methods
const goToEditProfile = () => router.push('/editprofile');
const goToEditWorkplace = () => router.push('/editworkplace');
const goToMainPage = () => router.push('/main');

// Alert for role checking
const checkRole = () => {
  if (user.value?.role_id === 1) {
    //  alert("Upozornenie: Vaša rola je 'Neschválený používateľ'. Prosím, vyberte si pracovisko.");
  }
};
const univerzity = ref([]);
const fakulty = ref([]);
const katedry = ref([]);

const selectedUniverzita = ref(null);
const selectedFakulta = ref(null);
const selectedKatedra = ref(null);

const fetchUniverzity = async () => {
  try {
    const response = await axiosInstance.get('/api/universities');
    univerzity.value = response.data;
  } catch (error) {
    console.error('Error fetching universities:', error);
  }
};

const fetchFakulty = async () => {
  if (selectedUniverzita.value) {
    try {
      const response = await axiosInstance.get(`/api/faculties?uni_id=${selectedUniverzita.value}`);
      fakulty.value = response.data;
      selectedFakulta.value = null; // Reset selected faculty
      katedry.value = []; // Clear departments
      selectedKatedra.value = null; // Reset selected department
    } catch (error) {
      console.error('Error fetching faculties:', error);
    }
  } else {
    fakulty.value = [];
    selectedFakulta.value = null;
    katedry.value = [];
    selectedKatedra.value = null;
  }
};

const fetchKatedry = async () => {
  if (selectedFakulta.value) {
    try {
      const response = await axiosInstance.get(`/api/departments?faculty_id=${selectedFakulta.value}`);
      katedry.value = response.data;
      selectedKatedra.value = null; // Reset selected department
    } catch (error) {
      console.error('Error fetching departments:', error);
    }
  } else {
    katedry.value = [];
    selectedKatedra.value = null;
  }
};

const filteredFakulty = computed(() => {
  return fakulty.value.filter(fakulta => fakulta.uni_id === selectedUniverzita.value);
});

const submitWorkplace = async () => {
  try {
    const payload = {
      type: 'Poziadanie o pracoviska',
      data: {
        university_id: selectedUniverzita.value,
        faculty_id: selectedFakulta.value,
        department_id: selectedKatedra.value,
      },
    };

    const response = await axiosInstance.post('/api/submit-workplace', payload);

    if (response.status === 200 || response.status === 201) {
      alert('Poziadanie o zmenu pracoviska bolo odoslane.');
    } else {
      alert('Failed to submit your workplace preferences. Please try again.');
    }
  } catch (error) {
    console.error('Error submitting workplace preferences:', error);
    alert('An error occurred. Please try again.');
  }
};



// Run role check on mount
onMounted(() => {
  // checkRole();
  fetchUniverzity();
});

const handleLogout = async () => {
  await authStore.logout(); // Počká na dokončenie logout
  router.push('/'); // Presmeruje na landing page
};
</script>


<style scoped>
.pracovisko-form {
  background-color: #f8f9fa;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  max-width: 50vw;
  margin: 20px auto;
  width: 80%;
}

h2 {
  font-size: 26px;
  color: #343a40;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  font-size: 16px;
  color: #495057;
  font-weight: 600;
  display: block;
  margin-bottom: 8px;
}

select {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  color: #495057;
  background-color: #ffffff;
  border: 2px solid #ced4da;
  border-radius: 8px;
  outline: none;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

select:focus {
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

select:disabled {
  background-color: #e9ecef;
  cursor: not-allowed;
}

option {
  padding: 10px;
  font-size: 16px;
}

button:disabled {
  background-color: #d3d3d3;
  cursor: not-allowed;
}

button:active {
  transform: scale(0.98);
}

/* Profile Section */
.profile {
  background-color: #f8f9fa;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  max-width: 50vw;
  margin: 20px auto;
  width: 80%;
}

.profile-card h1 {
  font-size: 26px;
  color: #343a40;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.profile-card p {
  font-size: 16px;
  color: #495057;
  margin-bottom: 10px;
}

.profile-card strong {
  font-weight: 600;
}

.verified {
  color: green;
}

.not-verified {
  color: red;
}

.btn-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.button-container {
  width: 100%;
}

.button-container button {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.button-container button:hover {
  background-color: #0056b3;
}

.button-container button:disabled {
  background-color: #d3d3d3;
  cursor: not-allowed;
}

.button-container button:active {
  transform: scale(0.98);
}

</style>
