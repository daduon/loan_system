<template src="./Login.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from "../../../services/request-service";
import { ToastService } from "../../../services/toast";

const requestService = new RequestService();
const toastService = new ToastService();
const isLoading = false;

export default defineComponent({
    name: "Login",
    data() {
        const loginForm = { name: "", password: "" };
        return {
            loginForm,
            isInvalide: false,
            isLoading,
        };
    },

    methods: {
        async handleLogin() {
            this.isLoading = true;
            await requestService
                .login("login", this.loginForm)
                .then((response) => {
                    this.isLoading = false;
                    localStorage.setItem("token", response.data.token);
                    this.$router.push("/home");
                    toastService.toastMessage("success", "Login success");
                })
                .catch((error) => {
                    this.isLoading = false;
                    toastService.toastMessage(
                        "error",
                        "Username or Password incorrect!",
                        error.response.data.message
                    );
                });
        },
    },
});
</script>

<style scoped>
.btnSave {
    display: flex;
    align-items: center;
    justify-content: center;
}

.loader {
    width: 20px;
    height: 20px;
    border: 5px solid #fff;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    margin-right: 10px;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
