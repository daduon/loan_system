<template src="./Customer002.html"></template>

<script lang="ts">

import { defineComponent } from "vue";
import { RequestService } from '../../../../js/services/request-service';
import { FileSize } from "../../../../js/services/file-size";


const requestService = new RequestService();
const fileSizeService = new FileSize();

export default defineComponent({
      name: 'Customer002',
      data() {
            const customerInfo = {
                  customerPicture:'',
                  customerName:'',
                  customerPhone:'',
                  customerGender:'1',
                  customerDod:'',
                  customerAddress:'',
                  customerEmail:'',
                  customerIdentity:'',
                  customerStatus:'',
                  isdiscount:'',
                  customerId:'',
                  id:''
            };

            const fileInfo = {
                  image: '',
                  file: '',
                  fileSize: '',
                  imgSize: '',
                  fileName: '',
                  imageName: ''
            } as any;

            const customerTypeList = [] as any;

            return {
                  fileInfo,
                  customerInfo,
                  customerTypeList,
                  isInvalide:false,
                  getImage:null,
                  viewType:''
            }
      },

      created() {
            this.getCustomerType();
          if(this.$route.params.type === 'edit'){
            this.viewType = 'edit';
            this.customerDetail(this.$route.params.id);
          }else{
            this.viewType = 'register'
          }
      },

      methods:{

            handleSave(){
                  if(
                        this.customerInfo.customerName === "" ||
                        this.customerInfo.customerId === ""
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

            async getCustomerType(){
                  const res = await requestService.list('customer_types');
                  if (res.status === 200) {
                        this.customerTypeList = res.data;
                  }
            },
            
            async customerDetail(id:any){
                  const res = await requestService.detail(`customers/${id}`);
                  this.fileInfo.image = res.data.data.customer_picture;
                  this.customerInfo = {
                        id:res.data.data.id,
                        customerPicture: res.data.data.customer_picture,
                        customerName: res.data.data.customer_name,
                        customerPhone: res.data.data.customer_phone,
                        customerGender: res.data.data.customer_gender,
                        customerDod: res.data.data.customer_dob,
                        customerAddress: res.data.data.customer_address,
                        customerEmail: res.data.data.customer_email,
                        customerIdentity: res.data.data.customer_identity,
                        customerStatus: res.data.data.customer_status,
                        isdiscount: res.data.data.isdiscount,
                        customerId: res.data.data.customer_type_id.id
                  }
            },

            async handleCreate(){
                  const body = {
                        customer_picture: this.fileInfo.image,
                        customer_name: this.customerInfo.customerName,
                        customer_phone: this.customerInfo.customerPhone,
                        customer_gender: this.customerInfo.customerGender,
                        customer_dob: this.customerInfo.customerDod,
                        customer_address: this.customerInfo.customerAddress,
                        customer_email: this.customerInfo.customerEmail,
                        customer_identity: this.customerInfo.customerIdentity,
                        customerStatus: this.customerInfo.customerStatus,
                        isdiscount: Number(this.customerInfo.isdiscount),
                        customer_type_id: this.customerInfo.customerId
                  }
  
                  const res = await requestService.create('customers',body);
                  
                  if(res.status === 200){
                        this.handleBack()
                  }
            },

            async handleUpdate(){
                  const id = this.customerInfo.id;
                  const body = {
                        customer_picture: this.fileInfo.image,
                        customer_name: this.customerInfo.customerName,
                        customer_phone: this.customerInfo.customerPhone,
                        customer_gender: this.customerInfo.customerGender,
                        customer_dob: this.customerInfo.customerDod,
                        customer_address: this.customerInfo.customerAddress,
                        customer_email: this.customerInfo.customerEmail,
                        customer_identity: this.customerInfo.customerIdentity,
                        customerStatus: this.customerInfo.customerStatus,
                        isdiscount: this.customerInfo.isdiscount,
                        customer_type_id: this.customerInfo.customerId
                  };

                  const res = await requestService.update(`customers/${id}`, body);
                  if(res.status === 200){
                        this.handleBack()
                  }
            },

            handleUploadImage(event: any) {
                  const files = event.target.files;
                  const file = files[0];
                  this.getImage = file;
                  const size = fileSizeService.countFileSize(file.size);
                  this.fileInfo.imgSize = size;
                  const reader = new FileReader();
                  reader.readAsDataURL(file);
                  this.fileInfo.imageName = file.name;
                  reader.onload = () => {
                        this.fileInfo.image = String(reader.result);
                  };
            },

            handleBack(){
                  this.$router.push('/customer')
            }
      },
})
</script>