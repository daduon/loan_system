<template src="./DefaulLayout.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
// import BreadcrumbVue from "./breadcrumb/Breadcrumb.vue";
import HeaderVue from "./header/Header.vue";
import SideBarVue from "./sidebar/SideBar.vue";


export default defineComponent({
      name: 'DefaultLayout',
      components:{
            SideBarVue,
            HeaderVue,
            // BreadcrumbVue
      },
      data() {
            return {

            }
      },

})
</script>