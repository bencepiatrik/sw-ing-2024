<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';

// State variables for the form
const selectedUniversity = ref('');
const selectedFaculty = ref('');
const selectedDepartment = ref('');
const universities = ref([]);
const faculties = ref([]);
const departments = ref([]);

// Router for navigation
const router = useRouter();

// Fetch data from the backend
const fetchUniversities = async () => {
  const response = await fetch('/api/universities');
  universities.value = await response.json();
};

const fetchFaculties = async (universityId: string) => {
  const response = await fetch(`/api/universities/${universityId}/faculties`);
  faculties.value = await response.json();
};

const fetchDepartments = async (facultyId: string) => {
  const response = await fetch(`/api/faculties/${facultyId}/departments`);
  departments.value = await response.json();
};

// Watch for university selection change
watch(selectedUniversity, (newUniversity) => {
  if (newUniversity) {
    selectedFaculty.value = '';
    selectedDepartment.value = '';
    fetchFaculties(newUniversity);
  }
});

// Watch for faculty selection change
watch(selectedFaculty, (newFaculty) => {
  if (newFaculty) {
    selectedDepartment.value = '';
    fetchDepartments(newFaculty);
  }
});

// Save workplace
const saveWorkplace = async () => {
  try {
    const payload = {
      university_id: selectedUniversity.value,
      faculty_id: selectedFaculty.value,
      department_id: selectedDepartment.value,
    };
    const response = await fetch('/api/user/workplace', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) throw new Error('Failed to save workplace.');

    alert('Workplace updated successfully.');
    router.push('/profile');
  } catch (error) {
    alert('Failed to update workplace.');
    console.error(error);
  }
};

// On component mount, fetch the universities
onMounted(() => {
  fetchUniversities();
});
</script>

<template>
  <v-app>
    <v-app-bar app color="#2D627F" dark>
      <v-container fluid>
        <v-row align="center" no-gutters>
          <v-col cols="1" class="d-flex justify-start align-center">
            <v-img :src="'/logo.png'" contain style="height: auto; width: auto;" />
          </v-col>

          <v-col cols="8" class="d-flex justify-center align-center">
            <v-toolbar-title class="text-h6">Edit Workplace</v-toolbar-title>
          </v-col>

          <v-spacer></v-spacer>
          <v-btn variant="text" href="/profile">Profile</v-btn>
        </v-row>
      </v-container>
    </v-app-bar>

    <div class="workplace-editor">
      <div class="workplace-card">
        <h1>Edit Workplace</h1>
        <form @submit.prevent="saveWorkplace">
          <div>
            <label for="university">University</label>
            <select id="university" v-model="selectedUniversity">
              <option value="" disabled>Select a university</option>
              <option v-for="uni in universities" :key="uni.id" :value="uni.id">{{ uni.name }}</option>
            </select>
          </div>

          <div v-if="faculties.length > 0">
            <label for="faculty">Faculty</label>
            <select id="faculty" v-model="selectedFaculty">
              <option value="" disabled>Select a faculty</option>
              <option v-for="fac in faculties" :key="fac.id" :value="fac.id">{{ fac.name }}</option>
            </select>
          </div>

          <div v-if="departments.length > 0">
            <label for="department">Department</label>
            <select id="department" v-model="selectedDepartment">
              <option value="" disabled>Select a department</option>
              <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
            </select>
          </div>

          <div class="button-container">
            <button type="submit">Save</button>
            <button type="button" @click="router.push('/profile')" class="discard-button">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </v-app>
</template>

<style scoped>
.workplace-editor {
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f0f4f8;
}

.workplace-card {
  background: #ffffff;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 400px;
}

h1 {
  font-size: 24px;
  font-weight: bold;
  color: #333333;
  text-align: center;
  margin-bottom: 20px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

label {
  font-weight: bold;
  color: #333333;
}

select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 100%;
}

.button-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: white;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}

.discard-button {
  background-color: #6c757d;
}

.discard-button:hover {
  background-color: #5a6268;
}
</style>
