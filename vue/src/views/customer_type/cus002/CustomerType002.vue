<template src="./CustomerType002.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import { ToastService } from '../../../services/toast';

const toastService = new ToastService();
const requestService = new RequestService();

export default defineComponent({
      name: 'CustomerType002',
      data() {
            const customerTypeForm = {
                  customerTypeName:'',
                  customerTypeRate:'',
                  customerTypeStatus:'1',
                  customerTypeDes:''
            } as any;
            const isLoading = false;
            return {
                  customerTypeForm,
                  isInvalide:false,
                  textBreadcrumb:'',
                  toastService,
                  isLoading,
            }
      },

      created() {
            if(this.$route.params.type === 'edit'){
                  this.handleDetail(this.$route.params.id);
                  this.textBreadcrumb = 'Edit Cutomer Type'
            }else{

                  this.textBreadcrumb = 'Create Cutomer Type'
            }
      },

      methods:{

            handleSave(){
                  if(
                        this.customerTypeForm.customerTypeName === "" ||
                        this.customerTypeForm.customerTypeRate === ""
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
                        customer_type_name: this.customerTypeForm.customerTypeName,
                        customer_type_rate: Number(this.customerTypeForm.customerTypeRate),
                        customer_type_status: Number(this.customerTypeForm.customerTypeStatus),
                        customer_type_des: this.customerTypeForm.customerTypeDes
                  }
                this.isLoading = true;
                await requestService.create('customer_types', body).then(() => {
                    this.isLoading = false;
                    this.handleBack();
                    toastService.toastMessage('success', 'Created Success');
                }).catch((error: any) => {
                    this.isLoading = false;
                    toastService.toastMessage('error', 'Create failed', error.response.data.message);
                })
            },

            async handleDetail(id:any){
                  await requestService.detail(`customer_types/${id}`).then((res) => {
                    this.customerTypeForm = {
                              customerTypeName: res.data.data.customer_type_name,
                              customerTypeRate: res.data.data.customer_type_rate,
                              customerTypeStatus: res.data.data.customer_type_status,
                              customerTypeDes: res.data.data.customer_type_des
                        }
                 }).catch((error: any) => {
                    toastService.toastMessage('error', 'Get Customer Type failed', error.response.data.message);
                })
            },

            async handleUpdate(){
                  const id:any = this.$route.params.id;

                  const body = {
                        id: id,
                        customer_type_name: this.customerTypeForm.customerTypeName,
                        customer_type_rate: Number(this.customerTypeForm.customerTypeRate),
                        customer_type_status: Number(this.customerTypeForm.customerTypeStatus),
                        customer_type_des: this.customerTypeForm.customerTypeDes
                  }
                  this.isLoading = true;
                  await requestService.update(`customer_types/${id}`, body).then(() => {
                    this.isLoading = false;
                    this.handleBack();
                    toastService.toastMessage('success', 'Updated Success');
                  }).catch((error: any) => {
                    this.isLoading = false;
                    toastService.toastMessage('error', 'Updated failed', error.response.data.message);
                })
            },

            handleBack(){
                  this.$router.push('/customer-type')
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
