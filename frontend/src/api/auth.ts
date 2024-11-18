import axiosInstance from './axiosInstance';

export async function login(email: string, password: string) {
  try {
    const response = await axiosInstance.post('/login', { email, password });
    localStorage.setItem('auth_token', response.data.token); // Store the token
    return response.data;
  } catch (error: any) {
    console.error('Login failed:', error.response?.data || error.message);
    throw error;
  }
}

export async function logout() {
  try {
    await axiosInstance.post('/logout');
    localStorage.removeItem('auth_token'); // Remove token
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
