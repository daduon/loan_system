<template src="./HolidayDate002.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import { ToastService } from '../../../services/toast';
import moment from 'moment';

const toastService = new ToastService();
const requestService = new RequestService();

export default defineComponent({
      name: 'HolidayDate002',
      data() {
            const holidayDateForm = {
                  countrycode:'116',
                  basedate:'',
                  holidaydate:'01'
            } as any;
            const holidayDateList = [
                  {key: '01', value: 'Work day'},
                  {key: '02', value: 'Holiday'},
                  {key: '03', value: 'Saturday'},
                  {key: '04', value: 'Sunday'}
            ] as any;
            const isLoading = false;
            return {
                  holidayDateForm,
                  holidayDateList,
                  isInvalide:false,
                  textBreadcrumb:'',
                  toastService,
                  isLoading,
                  test:new Date().toISOString().slice(0, 10)
            }
      },

      created() {
            if(this.$route.params.type === 'edit'){
                  this.handleDetail(this.$route.params.id);
                  this.textBreadcrumb = 'Edit Holiday Date'
            }else{
                  this.textBreadcrumb = 'Create Holiday Date'
            }

            // console.log('date',new Date().toISOString().slice(0, 10),)
      },
      
      methods:{

            handleSave(){
                  if(
                        this.holidayDateForm.countrycode === "" ||
                        this.holidayDateForm.basedate === "" ||
                        this.holidayDateForm.holidaydate === ""
                  ){
                        this.isInvalide = true;
                        return;
                  }

                  if(this.$route.params.type === 'edit'){
                        this.handleUpdate();
                  }else{
                        this.handleCreate();
                  }

            },

            async handleCreate(){
                  const body = {
                        countrycode: this.holidayDateForm.countrycode,
                        basedate: this.holidayDateForm.basedate.toString().replace(/-/g, ""),
                        holidaydate: this.holidayDateForm.holidaydate
                  }

                this.isLoading = true;

                await requestService.create('/holiday_dates', body).then(() => {
                    this.isLoading = false;
                    this.handleBack();
                    toastService.toastMessage('success', 'Created Success');
                }).catch((error: any) => {
                    this.isLoading = false;
                    toastService.toastMessage('error', 'Create failed', error.response.data.message);
                })
            },

            async handleDetail(id:any){
                  await requestService.detail(`/holiday_dates/${id}`).then((res) => {
                        const baseDateFormat = moment(res.data.data.basedate, 'YYYYMMDD').format('YYYY-MM-DD');

                        this.holidayDateForm = {
                              countrycode: res.data.data.countrycode,
                              basedate: baseDateFormat,
                              holidaydate: res.data.data.holidaydate,
                        }

                 }).catch((error: any) => {
                    toastService.toastMessage('error', 'Get holiday date failed', error.response.data.message);
                })
            },

            async handleUpdate(){
                  const id:any = this.$route.params.id;

                  const body = {
                        countrycode: this.holidayDateForm.countrycode,
                        basedate: this.holidayDateForm.basedate.toString().replace(/-/g, ""),
                        holidaydate: this.holidayDateForm.holidaydate
                  }

                  this.isLoading = true;

                  await requestService.update(`/holiday_dates/${id}`, body).then(() => {
                    this.isLoading = false;
                    this.handleBack();
                    toastService.toastMessage('success', 'Updated Success');
                  }).catch((error: any) => {
                    this.isLoading = false;
                    toastService.toastMessage('error', 'Updated failed', error.response.data.message);
                  })
            },

            handleBack(){
                  this.$router.push('/holiday-date')
            }
      }
})
</script>
<style scoped>
.btnSave {
    display: flex;
    align-items: center;
    justify-content: center;
}
.loader {
    width: 20px;
    height: 20px;
    border: 5px solid #FFF;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    margin-right: 10px;
    }

    @keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
