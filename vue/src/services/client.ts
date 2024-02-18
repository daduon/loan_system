import axios from "axios";
import router from "../router";

const Client = axios.create({
    // baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
    // baseURL: `http://loan-system/api`,
    baseURL: `http://127.0.0.1:8000/api`,
});

Client.interceptors.request.use((config) => {
    const AUTH_TOKEN = localStorage.getItem("token");
    if (AUTH_TOKEN) {
        config.headers.Authorization = `Bearer ${AUTH_TOKEN}`;
    }
    return config;
});

Client.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response.status === 401) {
            router.push("/login");

            localStorage.removeItem("token");
        }

        return Promise.reject(error);
    }
);

export default Client;
