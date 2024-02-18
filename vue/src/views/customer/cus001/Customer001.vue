<template src="./Customer001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import DataformatService from '../../../services/data-format.service';
import Loading from "../../../components/Loading.vue";
import ModalVue from "../../../components/modal/Modal.vue";
import { ToastService } from '../../../services/toast';
import Swal from 'sweetalert2';

const requestService = new RequestService();
const dataformatService = new DataformatService();
const toastService = new ToastService();

export default defineComponent({
      name: 'Customer001',
      components: {
            Loading,
            ModalVue
      },
      data() {
            const customerList = [] as any[];
            const dataTable = [] as any[];
            const dataDialog = {
                  show: false,
                  title: '',
                  message: '',
                  typeBtn: ''
            } as any;
            return {
                  customerList,
                  dataTable,
                  dataDialog,
                  getCustoemrId: '',
                  dialogStatus: false,
                  isLoading: false,
                  search: '',
                  countOfPage: 10,
                  currPage: 1,
                  searchBy: ['customerName']
            }
      },

      created() {
            this.inquiryCustomer();
      },

      computed: {
            headers() {
                  return [
                        { label: 'No.' },
                        { label: 'Name' },
                        { label: 'Phone' },
                        { label: 'Genger' },
                        { label: 'Date Of Birth' },
                        { label: 'Address' },
                        { label: 'Email' },
                        { label: 'Identity' },
                        { label: 'Action' },
                  ]
            },
            pageStart() {
                  return (this.currPage - 1) * this.countOfPage;
            },
            totalPage() {
                  return Math.ceil(this.customerList.length / this.countOfPage);
            },
            dataGrid(){
                  
                  if (this.search.length == 0) {
                        this.dataTable = this.customerList
                  }
                  this.dataTable = this.customerList.filter((data:any) => !this.search || this.searchBy.some((item:any) => data[item].toString().toLowerCase().includes(this.search.toLowerCase())))
                  return this.dataTable;
            }
      },

      methods: {

            async inquiryCustomer() {
                  this.isLoading = true;
                  const res = await requestService.list('customers');
                  if (res.status === 200) {
                        this.customerList = this.mapData(res.data);
                        this.dataTable = this.customerList;
                        this.isLoading = false;
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              id: item.id,
                              customerPicture: item.customer_picture,
                              customerName: item.customer_name,
                              customerPhone: dataformatService.formatPhoneNumber(item.customer_phone),
                              customerGender: item.customer_gender == 1 ? "Male" : "Female",
                              customerDod: dataformatService.formatDate(item.customer_dob),
                              customerAddress: item.customer_address,
                              customerEmail: item.customer_email,
                              customerIdentity: item.customer_identity,
                        }
                  })
            },

            handleEdit(id: any) {
                  this.$router.push(`/customer/edit/${id}`);
            },

            async handleDelete(id: any) {
                  Swal.fire({
                        title: 'Are you sure?',
                        text:'Are you sure want to delte this customer?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirm'
                        }).then((result) => {
                              if (result.isConfirmed) {
                                    this.handleConfirm(id);
                              }
                        })
            },

            async handleConfirm(id:any) {
                  if (id) {
                        this.isLoading = true;
                        const res = await requestService.delete(`customers/${id}`);
                        if (res.status === 200) {
                              this.isLoading = false;
                              toastService.toastMessage('success','Deleted!','Customer has been deleted')
                              this.inquiryCustomer()
                        } else {
                              toastService.toastMessage('error','Something has wrong!')
                        }
                  }
            },

            register() {
                  this.$router.push('/customer/register')
            },

            setPage(data: any) {
                  if (data <= 0 || data > this.totalPage) {
                        return;
                  }
                  this.currPage = data;
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