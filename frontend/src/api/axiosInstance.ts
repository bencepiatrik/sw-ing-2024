import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: 'http://localhost:8080/api', // Laravel API URL
  withCredentials: true, // Needed for Sanctum authentication
});

export default axiosInstance;
