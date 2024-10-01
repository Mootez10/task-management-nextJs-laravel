import axios, { AxiosInstance } from 'axios';

interface Token {
  token: string;
}

const token = localStorage.getItem('token');

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