import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import {useAuthStore} from "@/stores/authStore";
import { createVuetify } from 'vuetify';
import 'vuetify/styles';

const vuetify = createVuetify();
const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(vuetify);

const authStore = useAuthStore();
authStore.initialize(); // Initialize authentication state

app.mount('#app')
