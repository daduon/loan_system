<template src="./CustomerType001.html"></template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { RequestService } from '../../../../js/services/request-service';

const requestService = new RequestService();

export default defineComponent({
      name: 'CustomerType001',
      data() {
            const customerTypeList = [] as any[];
            const loading = ref(true);
            return {
                  customerTypeList,
                  confirmDialogVisible: true,
                  confirmMessage: 'Are you sure?',
                  loading,
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
                  const res = await requestService.list('/customer_types');
                  this.loading = false;
                  if (res.status === 200) {
                      this.customerTypeList = this.mapData(res.data);
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              customerTypeName: item.customer_type_name,
                              customerTypeRate: item.customer_type_rate,
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
                              console.log('Customer has deleted!')
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
