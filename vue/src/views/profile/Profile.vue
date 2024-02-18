<template src="./Profile.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from "../../services/request-service";
import { FileSize } from "../../services/file-size";
import { ToastService } from '../../services/toast';

const requestService = new RequestService();
const fileSizeService = new FileSize();
const toastService = new ToastService();

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
                  comapanyName: ''
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

            async handleSave() {
                  let image: any;
                  if (this.fileInfo.image !== '') {
                        image = this.fileInfo.image;
                  } else {
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

                  await requestService.update(`CompanyProfile/1`, body)
                        .then(res => {
                              if (res) {
                                    toastService.toastMessage('success', 'Successfully!')
                              }
                        })
                        .catch(err => {
                              if (err) {
                                    toastService.toastMessage('error', 'Something has wrong!', err.response.data.message)
                              }
                        })
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

<style scoped>
.containers {
      height: 200px;
      position: relative;
      max-width: 250px;
      margin: auto;
}

.containers .imageWrapper {
      border: 3px solid #888;
      width: 73%;
      padding-bottom: 70%;
      border-radius: 50%;
      overflow: hidden;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
}

.containers .imageWrapper img {
      height: 105%;
      width: initial;
      max-height: 100%;
      max-width: initial;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
}


.file-upload {
  position: relative;
  overflow: hidden;
  margin: 10px; 
}

.file-upload {
  position: relative;
  overflow: hidden;
  margin: 10px;
  width:100%;
  max-width:150px;
  text-align:center;
  color:#615f5f;
  font-size:1.2em;
  background: #9dcff1;
  border: 2px solid #888;
  display:inline;
  -ms-transition: all 0.2s ease;
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
  border-radius: 5px;
}

.file-upload input.file-input {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  padding: 0;
  font-size: 20px;
  cursor: pointer;
  opacity: 0;
  filter: alpha(opacity=0);
  height:100%;
}

</style>