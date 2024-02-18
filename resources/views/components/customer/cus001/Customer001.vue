<template src="./Customer001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';
import Table from '../../../../share/Table.vue';

const requestService = new RequestService();

export default defineComponent({
      name: 'Customer001',
      components: {
            Table
      },
      data() {
            const tableData = [] as any[];
            const columns = [
                  { label: 'Name', prop: 'customerName', sortable: true },
                  { label: 'Phone', prop: 'customerPhone', sortable: true },
                  { label: 'Genger', prop: 'customerGender', sortable: true },
                  { label: 'Date Of Birth', prop: 'customerDod', sortable: false },
                  { label: 'Address', prop: 'customerAddress', sortable: false },
                  { label: 'Email', prop: 'customerEmail', sortable: false },
                  { label: 'Identity', prop: 'customerIdentity', sortable: false },
                  { label: 'Status', prop: 'customerStatus', sortable: false },
                  { label: 'Discount', prop: 'isdiscount', sortable: false }
            ];
            return {
                  columns,
                  tableData,
                  isLoading: false,
            }
      },

      created() {
            this.inquiryCustomer();
      },

      methods: {

            async inquiryCustomer() {
                  this.isLoading = true;
                  const res = await requestService.list('customers');
                  if (res.status === 200) {
                        this.tableData = this.mapData(res.data);
                        this.isLoading = false;
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              id: item.id,
                              customerPicture: item.customer_picture,
                              customerName: item.customer_name,
                              customerPhone: item.customer_phone,
                              customerGender: item.customer_gender,
                              customerDod: item.customer_dob,
                              customerAddress: item.customer_address,
                              customerEmail: item.customer_email,
                              customerIdentity: item.customer_identity,
                              customerStatus: item.customer_status === 1 ? 'Active' : 'Delete',
                              isdiscount: item.isdiscount,
                        }
                  })
            },

            handleEdit(row: any) {
                  this.$router.push(`/customer/edit/${row.id}`);
            },

            async handleDelete(row: any) {
                  if (confirm("Do you sur want to delete?")) {
                        const res = await requestService.delete(`customers/${row.id}`);
                        if (res.status === 200) {
                              console.log('Customer has deleted!')
                        }
                  }
            },

            register() {
                  this.$router.push('/customer/register')
            },
      }
})
</script>