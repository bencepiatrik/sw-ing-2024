import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:8080", // Laravel base URL
  withCredentials: true, // Needed for Sanctum
});

// Fetch CSRF token before making requests
/*
axiosInstance.interceptors.request.use(async (config) => {
  if (!document.cookie.includes("XSRF-TOKEN")) {
    await axios.get("http://localhost:8080/sanctum/csrf-cookie");
  }
  return config;
});*/

export default axiosInstance;
