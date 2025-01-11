import axiosInstance from './axiosInstance';


export const register = async (data: {
  name: string;
  surname: string; // Add optional surname if supported by the backend
  email: string;
  password: string;
  password_confirmation: string;
}) => {
  const response = await axiosInstance.post("/register", data);
  return response.data;
};

export const login = async (email: string, password: string) => {
  // Fetch CSRF token first
  await axiosInstance.get('/sanctum/csrf-cookie');

  // Send login request
  const response = await axiosInstance.post('/login', { email, password });
  console.log(response.data)
  return response.data;
};


export async function logout() {
  try {
    await axiosInstance.post('/logout');
    //localStorage.removeItem('auth_token'); // Remove token
  } catch (error: any) {
    console.error('Logout failed:', error.response?.data || error.message);
    throw error;
  }
}

export async function getUser() {
  try {
    const response = await axiosInstance.get('/user');
    return response.data;
  } catch (error: any) {
    console.error('Failed to fetch user data:', error.response?.data || error.message);
    throw error;
  }
}
