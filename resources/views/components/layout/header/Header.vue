<template src="./Header.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';

const requestService = new RequestService();

export default defineComponent({
      name: 'Header',
      data() {
            return {
                  active: false
            }
      },
      methods: {
            toggleBodyClass() {
                  this.active = !this.active;
                  let sitebody = document.body;
                  if(this.active){
                        sitebody.classList.add("toggle-sidebar");
                        sitebody.classList.remove("toe-sidebar-btnggl");
                  } else {
                        sitebody.classList.add("toe-sidebar-btnggl");
                        sitebody.classList.remove("toggle-sidebar");
                  }   
            },
            async logout(){
                  const res = await requestService.logout('logout');
                  if(res.status === 200){
                        localStorage.removeItem('token')
                        this.$router.push('/login');
                  }
            },
            profile(){
                  this.$router.push('/profile');
            }
      },

})
</script>