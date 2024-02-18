<template src="./COEmployee001.html"></template>

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
      name: 'COEmployee001',
      components: {
            Loading,
            ModalVue
      },
      data() {
            const coEmployeeList = [] as any[];
            const dataTable = [] as any[];
            const dataDialog = {
                  show: false,
                  title: '',
                  message: '',
                  typeBtn: ''
            } as any;
            return {
                  coEmployeeList,
                  dataTable,
                  dataDialog,
                  getCustoemrId: '',
                  dialogStatus: false,
                  isLoading: false,
                  search: '',
                  countOfPage: 10,
                  currPage: 1,
                  searchBy: ['name']
            }
      },

      created() {
            this.inquiryCOEmployee();
      },

      computed: {
            headers() {
                  return [
                        { label: 'No.' },
                        { label: 'Name' },
                        { label: 'Role' },
                        { label: 'Phone Number' },
                        { label: 'Action' },
                  ]
            },
            pageStart() {
                  return (this.currPage - 1) * this.countOfPage;
            },
            totalPage() {
                  return Math.ceil(this.coEmployeeList.length / this.countOfPage);
            },
            dataGrid(){

                  if (this.search.length == 0) {
                        this.dataTable = this.coEmployeeList
                  }
                  this.dataTable = this.coEmployeeList.filter((data:any) => !this.search || this.searchBy.some((item:any) => data[item].toString().toLowerCase().includes(this.search.toLowerCase())))
                  return this.dataTable;
            }
      },

      methods: {

            async inquiryCOEmployee() {
                  this.isLoading = true;
                  const res = await requestService.list('coemployees');
                  if (res.status === 200) {
                        this.coEmployeeList = this.mapData(res.data);
                        this.dataTable = this.coEmployeeList;
                        this.isLoading = false;
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              id: item.id,
                              name: item.name,
                              role: item.role,
                              phone: dataformatService.formatPhoneNumber(item.phone),
                        }
                  })
            },

            handleEdit(id: any) {
                  this.$router.push(`/coemployee/edit/${id}`);
            },

            async handleDelete(id: any) {
                  Swal.fire({
                        title: 'Are you sure?',
                        text:'Are you sure want to delte this CO employee?',
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
                        const res = await requestService.delete(`coemployees/${id}`);
                        if (res.status === 200) {
                              this.isLoading = false;
                              toastService.toastMessage('success','Deleted!','CO Employee has been deleted')
                              this.inquiryCOEmployee()
                        } else {
                              toastService.toastMessage('error','Something has wrong!')
                        }
                  }
            },

            register() {
                  this.$router.push('/coemployee/register')
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
