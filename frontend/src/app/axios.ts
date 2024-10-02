import axios, { AxiosInstance } from 'axios';


let token: string | null = null;
if (typeof window !== 'undefined') {
   token = localStorage.getItem('token');
}

const api: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000/api',
  
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

export default api;