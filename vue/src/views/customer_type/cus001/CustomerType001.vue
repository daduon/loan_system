<template src="./CustomerType001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import CustomConfirmDialog from '../../../components/modal/CustomConfirmDialog.vue';
import Loading from "../../../components/Loading.vue";

const requestService = new RequestService();

export default defineComponent({
      name: 'CustomerType001',
      components: {
            CustomConfirmDialog,
            Loading
      },
      data() {
            const customerTypeList = [] as any[];
            return {
                  customerTypeList,
                  confirmDialogVisible: true,
                  confirmMessage: 'Are you sure?',
                  isLoading: false,
            }
      },

      created() {
            this.inquiryCustomerType();
      },

      computed: {
            headers() {
                  return [
                        { label: 'No.' },
                        { label: 'Customer Type Name' },
                        { label: 'Customer Type Rate' },
                        { label: 'Description' },
                        { label: 'Action' }
                  ]
            }
      },

      methods: {

            async inquiryCustomerType() {
                this.isLoading = true
                  const res = await requestService.list('/customer_types');
                  if (res.status === 200) {
                      this.customerTypeList = this.mapData(res.data);
                      this.isLoading = false
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              customerTypeName: item.customer_type_name,
                              customerTyperRate: item.customer_type_rate,
                              customerTypeDes: item.customer_type_des,
                              id: item.id
                        }
                  })
            },

            register() {
                  this.$router.push('/customer-type/register')
            },

            handleEdit(id: any) {
                  this.$router.push(`/customer-type/edit/${id}`)
            },

            async handleDelete(id: any) {
                  if (confirm("Do you sur want to delete?")) {
                        const res = await requestService.delete(`customer_types/${id}`);
                        if (res.status === 200) {
                              this.inquiryCustomerType();
                        }
                  }
            },

            showConfirmDialog() {
                  this.confirmDialogVisible = true;
            },
            handleConfirm() {
                  console.log('Confirmed');
                  // Perform your logic for confirmed action
            },
            handleCancel() {
                  console.log('Canceled');
                  // Perform your logic for canceled action
            },
      }
})
</script>

<style scoped>
@media only screen and (max-width: 1400px) {
      .card-body{
            overflow-x: scroll;
      }
}
</style>