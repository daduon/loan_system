<template src="./Profile.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from "../../../js/services/request-service";
import { FileSize } from "../../../js/services/file-size";

const requestService = new RequestService();
const fileSizeService = new FileSize();

export default defineComponent({
      name: 'Profile',
      data() {
            const companyInfo = {
                  companyProfileName: '',
                  companyProfilePhone: '',
                  companyProfileEmail: '',
                  companyProfileAddress: '',
                  companyProfileImage: '',
                  companyProfileWifi: '',
                  companyProfileSetUpPoint: ''
            } as any;
            const fileInfo = {
                  image: '',
                  file: '',
                  fileSize: '',
                  imgSize: '',
                  fileName: '',
                  imageName: ''
            } as any;
            return {
                  companyInfo,
                  fileInfo,
                  getImage: null,
                  comapanyName:''
            }
      },

      created() {
            this.companyDetail();
      },

      methods: {
            async companyDetail() {
                  const res = await requestService.detail('CompanyProfile/1');
                  this.comapanyName = res.data.company_profile_name;
                  this.companyInfo = {
                        companyProfileName: res.data.company_profile_name,
                        companyProfilePhone: res.data.company_profile_phone,
                        companyProfileEmail: res.data.company_profile_email,
                        companyProfileAddress: res.data.company_profile_address,
                        companyProfileImage: res.data.company_profile_image,
                        companyProfileWifi: res.data.company_profile_wifi,
                        companyProfileSetUpPoint: res.data.company_profile_set_up_point
                  }
            },

            async handleSave(){
                  let image:any;
                  if(this.fileInfo.image !== ''){
                        image = this.fileInfo.image;
                  }else{
                        image = this.companyInfo.companyProfileImage
                  }
                  const body = {
                        company_profile_name: this.companyInfo.companyProfileName,
                        company_profile_phone: this.companyInfo.companyProfilePhone,
                        company_profile_email: this.companyInfo.companyProfileEmail,
                        company_profile_address: this.companyInfo.companyProfileAddress,
                        company_profile_image: image,
                        company_profile_wifi: this.companyInfo.companyProfileWifi,
                        company_profile_set_up_point: this.companyInfo.companyProfileSetUpPoint
                  };

                  const res = await requestService.update(`CompanyProfile/1`,body);
                  // console.log(res)
                  if(res.status === 200){
                        console.log('Company profile has updated!')
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
      }
})
</script>