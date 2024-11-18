import { defineStore } from 'pinia';
import { login, logout, getUser } from '../api/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
  }),
  actions: {
    async login(email: string, password: string) {
      const userData = await login(email, password);
      this.user = userData.user;
      this.isAuthenticated = true;
    },
    async logout() {
      await logout();
      this.user = null;
      this.isAuthenticated = false;
    },
    async fetchUser() {
      try {
        const userData = await getUser();
        this.user = userData;
        this.isAuthenticated = true;
      } catch {
        this.user = null;
        this.isAuthenticated = false;
      }
    },
  },
});
