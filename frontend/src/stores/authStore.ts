import { defineStore } from 'pinia';
import { login, logout } from '../api/auth';
import axiosInstance from "@/api/axiosInstance";
import axios from 'axios';

// Definícia rozhrania pre údaje používateľa
interface User {
  id: number;
  name: string;
  email: string;
  role_id: number;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
  departments: Department[];
}

interface Department {
  [x: string]: any;
  pivot: {
    department_id: number;
  };
}


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null, // Používateľ môže byť objekt User alebo null
    isAuthenticated: false,
  }),
  actions: {
    async login(email: string, password: string) {
      const userData = await login(email, password); // Získame údaje z API
      this.user = userData.data; // Očakávame, že API vracia `data`
      this.isAuthenticated = true;

      // Uloženie používateľa do localStorage
      localStorage.setItem('user', JSON.stringify({ data: this.user }));
    },

    initialize() {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          const parsedUser = JSON.parse(storedUser);
          this.user = parsedUser.data; // Pristúpime k `data`
          this.isAuthenticated = true;

          // Voliteľne: Validácia relácie na backend-e
          axiosInstance
            .get<User>('/api/user', { withCredentials: true })
            .then(response => {
              this.user = response.data; // Aktualizujeme údaje používateľa
              localStorage.setItem('user', JSON.stringify({ data: response.data }));
            })
            .catch(() => {
              this.logout(); // Ak relácia nie je platná, odhlásime používateľa
            });
        } catch (error) {
          console.error('Failed to parse user data from localStorage:', error);
          this.user = null;
          this.isAuthenticated = false;
        }
      } else {
        this.user = null;
        this.isAuthenticated = false;
      }
    },

    async logout() {
      try {
        await logout(); // Volanie API
        localStorage.removeItem('user');
        this.user = null;
        this.isAuthenticated = false;
      } catch (error) {
        console.error('Error during logout:', error);
      }
    },
    async updateUser(updatedData) {
      try {
        const response = await axiosInstance.put('/api/user', updatedData);
        this.user = response.data;
        return response.data;
      } catch (error) {
        console.error('Failed to update user:', error);
        throw error;
      }
    },
    async deleteUser() {
      try {
        await axios.delete('/api/user');
        this.user = null;
      } catch (error) {
        console.error('Failed to delete user:', error);
        throw error;
      }
    },
  },
});
