import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from "../views/Register.vue"; // Import the Register component

const routes = [
  { path: '/', component: () => import('../views/HomeView.vue') },
  { path: '/login', component: Login },
  { path: "/register", name: "Register", component: Register },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
