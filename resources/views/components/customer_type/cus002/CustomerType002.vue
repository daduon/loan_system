<template src="./CustomerType002.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';

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

            return {
                  customerTypeForm,
                  isInvalide:false
            }
      },

      created() {
            if(this.$route.params.type === 'edit'){
                  this.handleDetail(this.$route.params.id);
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

                  const res = await requestService.create('customer_types', body);

                  if(res.status === 200){
                      alert('Customer type created!')
                        this.handleBack();
                  }
            },

            async handleDetail(id:any){
                  const res = await requestService.detail(`customer_types/${id}`);

                  if(res.status === 200){
                        this.customerTypeForm = {
                              customerTypeName: res.data.data.customer_type_name,
                              customerTypeRate: res.data.data.customer_type_rate,
                              customerTypeStatus: res.data.data.customer_type_status,
                              customerTypeDes: res.data.data.customer_type_des
                        }
                  }
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

                  const res = await requestService.update(`customer_types/${id}`, body);

                  if(res.status === 200){
                        alert('Customer type updated!')
                        this.handleBack();
                  }
            },

            handleBack(){
                  this.$router.push('/customer-type')
            }
      }
})
</script>
