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



export default router;
