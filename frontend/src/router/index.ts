import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import Register from '@/views/Register.vue' // Import the Register component
import MainPage from '@/views/MainPage.vue'
import ProfilePage from '@/views/Profile.vue'
import EditProfilePage from '@/views/EditProfile.vue'
import EditWorkplace from '@/views/EditWorkplace.vue'
import CategoryDetail from '@/views/CategoryDetail.vue'
import Landing from '@/views/Landing.vue'
import Login from '@/views/Login.vue'
import AdminPage from '@/views/AdminPage.vue'
import Notifications from '@/views/Notifications.vue'
import CreateConference from '@/views/CreateConference.vue'
import AdminConference from '@/views/AdminConference.vue'

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: Landing
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/main',
    name: 'MainPage',
    component: MainPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: ProfilePage,
    meta: { requiresAuth: true }
  },
  {
    path: '/editprofile',
    name: 'EditProfile',
    component: EditProfilePage,
    meta: { requiresAuth: true }
  },
  {
    path: '/editworkplace',
    name: 'EditWorkplace',
    component: EditWorkplace,
    meta: { requiresAuth: true }
  },
  {
    path: '/categories/:id',
    name: 'CategoryDetail',
    component: CategoryDetail,
    meta: { requiresAuth: true },
    props: true
  },
  {
    path: '/admin',
    name: 'Admin',
    component: AdminPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/createconference',
    name: 'CreateConference',
    component: CreateConference,
    meta: { requiresAuth: true }
  },
  {
    path: '/adminconference',
    name: 'AdminConference',
    component: AdminConference,
    meta: { requiresAuth: true }
  },

  {
    path: '/ziadosti',
    name: 'Notifications',
    component: Notifications,
    meta: { requiresAuth: true }
  }
]


const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // If user is authenticated and trying to access guest-only pages
  if (authStore.isAuthenticated && ['/login', '/register', '/'].includes(to.path)) {
    return next('/main') // Redirect to main page and stop further execution
  }

  // If the route requires authentication and the user is not logged in
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next('/') // Redirect to landing page and stop further execution
  }

  // Allow navigation otherwise
  next()
})


router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' }) // Redirect to login if not authenticated
  } else {
    next() // Proceed to the route
  }
})
export default router
