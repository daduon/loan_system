<template src="./Loan003.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';


const requestService = new RequestService();
export default defineComponent({
      name: 'loan-schedules',
      data() {
     
            const borrowerScheduleList = [] as any;
            return {
                borrowerScheduleList,
            }
            
      },
      created() {

                this.borrowerSchedule(this.$route.params.id,this.$route.params.lid);
            
      },
      updated() {
        this.borrowerSchedule(this.$route.params.id,this.$route.params.lid);
      },
      methods: {
        async borrowerSchedule(id:any, lid:any){
                  const res = await requestService.detail(`retriveborrower/${id}/${lid}`);
                  if(res.status === 200){       
                    this.borrowerScheduleList = res.data;
            }
        },
        back() {
            this.$router.push('/loan');
        },
      
        async update(id:any){
                  const res = await requestService.updatebyId(`payloanbyid/${id}`);
                  if(res.status === 200){
            }
        },
        
      },
    }
)
</script>