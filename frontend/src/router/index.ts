import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from "../stores/authStore";
import Landing from "../views/Landing.vue";
import Login from '../views/Login.vue';
import Register from "../views/Register.vue"; // Import the Register component
import MainPage from "../views/MainPage.vue";


const routes = [
  {
    path: "/",
    name: "Landing",
    component: Landing,
  },
  {
    path: '/login',
    name: "Login",
    component: Login
  },
  {
    path: "/register",
    name: "Register",
    component: Register
  },
  {
    path: "/main",
    name: "MainPage",
    component: MainPage,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: "Login" });
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: "MainPage" });
  } else {
    next();
  }
});

export default router;
