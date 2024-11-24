<template>
  <div>
    <nav>
      <ul>
        <li><a href="/login">Login</a></li>
        <li><a href="/register">Register</a></li>
      </ul>
    </nav>
    <main>
      <h1>Welcome to the Landing Page</h1>
      <p>This is the main entry point of your app.</p>
    </main>
  </div>
</template>


<script>
import { useAuthStore } from "../stores/authStore";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();

    onMounted(() => {
      if (authStore.isAuthenticated) {
        router.push({ name: "MainPage" });
      }
    });

    return {};
  },
};
</script>

<style scoped>

</style>
