<template>
  <div>
    <nav>
      <ul>
        <li><a href="/">Landing</a></li>
        <li><a href="/profile">Profile</a></li>
        <li><a href="#" @click="handleLogout">Logout</a></li>
      </ul>
    </nav>
    <main>
      <h1>Welcome to the Main Page</h1>
      <p>You are now logged in.</p>
    </main>
  </div>
</template>

<script>
import { useAuthStore } from "../stores/authStore";

export default {
  setup() {
    const authStore = useAuthStore();

    const handleLogout = () => {
      authStore.logout();
      window.location.href = "/";
    };

    return {
      handleLogout,
    };
  },
};
</script>

<style scoped>

</style>
