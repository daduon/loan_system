import axios from 'axios';
import router from '../router';

const Client = axios.create({
      baseURL: `http://loan-system/api`,
});


Client.interceptors.request.use((config) => {
    const AUTH_TOKEN = localStorage.getItem('token');
    if(AUTH_TOKEN) {
        config.headers.Authorization = `Bearer ${AUTH_TOKEN}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

Client.interceptors.response.use(
    response => response,
    error => {
        if (error.response.status === 401) {
            router.push('/login');

            localStorage.removeItem('token');
        }

        return Promise.reject(error);
    }
)

export default Client;
