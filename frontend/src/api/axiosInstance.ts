import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:8083", // Laravel base URL
  withCredentials: true, // Needed for Sanctum
});

// Fetch CSRF token before making requests
axiosInstance.interceptors.request.use(async (config) => {
  if (!document.cookie.includes('XSRF-TOKEN')) {
    await axiosInstance.get('/sanctum/csrf-cookie'); // Retrieve CSRF cookie
  }
  return config;
});

export default axiosInstance;
