import { defineStore } from 'pinia';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: null as any,
  }),
  actions: {
    setUser(data: any) {
      this.user = data;
      localStorage.setItem('user', JSON.stringify({ data }));
    },
    loadUserFromStorage() {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          this.user = JSON.parse(storedUser).data;
        } catch (error) {
          console.error('Failed to load user from storage:', error);
        }
      }
    },
  },
});
