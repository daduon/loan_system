<template src="./Header.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../services/request-service';
import { ToastService } from "../../services/toast";

const requestService = new RequestService();
const toastService = new ToastService();

export default defineComponent({
      name: 'Header',
      data() {
        const notifications = [] as any[];
        const user = {
            name: '',
            isSuperUser: '',
            email: '',
        };
        return {
                notifications,
                active: false,
                user,
        }
      },
      mounted() {
            this.inquiryNotifications();
            this.getUser()
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
            async inquiryNotifications() {
                await requestService.list('notifications').then((res) => {
                    this.notifications = this.mapData(res.data);
                }).catch((error) => {
                    toastService.toastMessage('error', 'Nofications Error', error.response.data.message);
                })
            },
            mapData(list: any) {
                return list.data.map((item: any) => {
                    return {
                        borrowingID: item.borrowing_id,
                        customerID: item.customer_id,
                        customerName: item.customer_name,
                        paymentToDate: item.paymenttodate,
                    }
                })
            },
            goToLoanScheduleScreen(_id: string, _loandId: string) {
                localStorage.setItem("_id", _id);
                localStorage.setItem("_loandId", _loandId);

                this.$router.push(`/loan/schedules`);
                if(this.$route.path === "/loan/schedules"){
                    window.location.reload();
                }
            },
            async getUser() {
                await requestService.detail('user').then((res) => {
                    // console.log(res.data);

                    this.user = {
                        name: res.data.name,
                        isSuperUser: res.data.is_super_user,
                        email: res.data.email,
                    }
                }).catch((error) => {
                    // console.log(error)
                    toastService.toastMessage('error', 'User Not Found', error.response.data.message);
                })
            }
      },

})
</script>
