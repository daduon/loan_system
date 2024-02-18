<template src="./Loan001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';

const requestService = new RequestService();
export default defineComponent({
      name: 'Loan001',
      data() {
            const customerBorrowList = [] as any;
            return {
                  customerBorrowList,
            }
            
      },
      created() {
            this.inquiryCustomerType();
      },
      methods: {

      async inquiryCustomerType() {
      const res = await requestService.list('/retriveborrower');
      // console.log(res.data);
      if (res.status === 200) {
            this.customerBorrowList= res.data;
            // console.log(res.data);
      }

      },
      getLoanSchedules: function ( _id : string, _loandId:string ) {

            this.$router.push(`/loan/schedules/${_id}/${_loandId}`);
     
      },
      
      register() {
                  this.$router.push('/loan/create')
            },

      }
}
)
</script>