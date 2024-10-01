import axios, { AxiosInstance } from 'axios';


let token: string | null = null;
if (typeof window !== 'undefined') {
  // You are in the browser environment
   token = localStorage.getItem('token');
}

const api: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: getAuthHeader()
});

function getAuthHeader(): {[key: string]: string} {
  const header = {} as {[key: string]: string};

  if (token) {
    header['Authorization'] = `Bearer ${token}`;
  }

  return header;
}

export default api;