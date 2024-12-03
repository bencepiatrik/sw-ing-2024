import { defineStore } from 'pinia';
import { login, logout, getUser } from '../api/auth';
import axiosInstance from "@/api/axiosInstance";

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
  }),
  actions: {
    async login(email: string, password: string) {
      const userData = await login(email, password);
      this.user = userData;
      this.isAuthenticated = true;

      localStorage.setItem('user', JSON.stringify(this.user));

      console.log(this.user);
      console.log(this.isAuthenticated);
    },
    initialize() {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        // If user exists in localStorage, restore state
        this.user = JSON.parse(storedUser);
        this.isAuthenticated = true;

        // Optional: Validate session with backend
        axiosInstance.get('/api/user', { withCredentials: true })
          .then((response) => {
            this.user = response; // Update user data with latest info
            localStorage.setItem('user', JSON.stringify(response));
          })
          .catch(() => {
            // If session is invalid, log out
             this.logout();
          });
      } else {
        // If no user, reset state
        this.user = null;
        this.isAuthenticated = false;
      }
    },

    async logout() {
      localStorage.removeItem('user'); // Clear localStorage

      await logout();
      this.user = null;
      this.isAuthenticated = false;
      console.log(this.user);
      console.log(this.isAuthenticated);
    },
  },
});
