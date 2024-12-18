import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import Landing from "../views/Landing.vue";
import Login from '../views/Login.vue';
import Register from "../views/Register.vue"; // Import the Register component
import MainPage from "../views/MainPage.vue";
import ProfilePage from "../views/Profile.vue";
import AdminPage from "../views/AdminPage.vue";
import CategoryDetail from "../views/CategoryDetail.vue";

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
    meta: { requiresAuth: true },
  },
  {
    path: "/profile",
    name: "Profile",
    component: ProfilePage,
    meta: { requiresAuth: true },
  },
  {
    path: "/categories/:id",
    name: "CategoryDetail",
    component: CategoryDetail,
    meta: { requiresAuth: true },
    props: true, // Odovzdáva ID ako prop do komponenty
  },
  {
    path: "/admin",
    name: "Admin",
    component: AdminPage,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // If user is authenticated and trying to access guest-only pages
  if (authStore.isAuthenticated && ['/login', '/register', '/'].includes(to.path)) {
    next('/main'); // Redirect to main page
  }

  // If the route requires authentication and the user is not logged in
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/'); // Redirect to login page
  }

  next(); // Allow navigation otherwise
});


/*
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: "Login" }); // Redirect to login if not authenticated
  } else {
    next(); // Proceed to the route
  }
});*/
export default router;
