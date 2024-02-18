<template src="./HolidayDate001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import CustomConfirmDialog from '../../../components/modal/CustomConfirmDialog.vue';
import Loading from "../../../components/Loading.vue";
import Swal from 'sweetalert2';
import { ToastService } from '../../../services/toast';

const requestService = new RequestService();
const toastService = new ToastService();

export default defineComponent({
      name: 'HolidayDate001',
      components: {
            CustomConfirmDialog,
            Loading
      },
      data() {
            const holidayDateList = [] as any[];
            return {
                  holidayDateList,
                  confirmDialogVisible: true,
                  confirmMessage: 'Are you sure?',
                  isLoading: false,
            }
      },

      created() {
            this.inquiryHolidayDate();
      },

      computed: {
            headers() {
                  return [
                        { label: 'No.' },
                        { label: 'Country Code' },
                        { label: 'Base Date' },
                        { label: 'Holiday Date' },
                        { label: 'Register Date' },
                        { label: 'Modify Date' },
                        { label: 'Action' }
                  ]
            }
      },

      methods: {

            async inquiryHolidayDate() {
                this.isLoading = true
                  const res = await requestService.list('holiday_dates');
                  if (res.status === 200) {
                      this.holidayDateList = this.mapData(res.data);
                      this.isLoading = false
                  }
            },

            mapData(list: any) {
                  return list.data.map((item: any) => {
                        return {
                              id: item.id,
                              countrycode: item.countrycode,
                              basedate: item.basedate,
                              holidaydate: item.holidaydate == "01" ? "Work Day" : item.holidaydate == "02" ? "Holiday" : item.holidaydate == "03" ? "Saturday" : "Sunday",
                              registerdate: item.registerdate,
                              modifydate: item.modifydate == "" ? "--" : item.modifydate
                        }
                  })
            },

            register() {
                  this.$router.push('/holiday-date/register')
            },

            handleEdit(id: any) {
                  this.$router.push(`/holiday-date/edit/${id}`)
            },

            async handleDelete(id: any) {
                  Swal.fire({
                        title: 'Are you sure?',
                        text:'Are you sure want to delte this holiday date?',
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
                        const res = await requestService.delete(`/holiday_dates/${id}`);
                        if (res.status === 200) {
                              this.isLoading = false;
                              toastService.toastMessage('success','Deleted!','Holiday Date has been deleted')
                              this.inquiryHolidayDate()
                        } else {
                              toastService.toastMessage('error','Something has wrong!')
                        }
                  }
            },

            showConfirmDialog() {
                  this.confirmDialogVisible = true;
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