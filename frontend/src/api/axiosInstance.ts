import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: 'http://localhost:8083', // Replace with your backend URL
  withCredentials: true, // Ensure cookies are sent
});

// Automatically include CSRF token in headers
axiosInstance.interceptors.request.use((config) => {
  const csrfToken = document.cookie
    .split('; ')
    .find((row) => row.startsWith('XSRF-TOKEN'))
    ?.split('=')[1];

  if (csrfToken) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(csrfToken);
  }

  return config;
});

export default axiosInstance;
