<template src="./Login.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../js/services/request-service';

const requestService = new RequestService();

export default defineComponent({
      name: 'Login',
      data() {
            const loginForm = {name:"",password:""}
            return {
                  loginForm,
                  isInvalide:false
            }
      },

      methods:{
            async handleLogin(){
                  const res = await requestService.login('login',this.loginForm);
                  if(res.data.token !== ''){
                        localStorage.setItem('token',res.data.token)
                        this.$router.push('/home');
                  }else{
                        this.isInvalide = true;
                  }
            }
      }
})
</script>