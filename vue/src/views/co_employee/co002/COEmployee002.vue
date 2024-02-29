<template src="./COEmployee002.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../../services/request-service';
import { FileSize } from "../../../services/file-size";
import Loading from "../../../components/Loading.vue";
import { ToastService } from '../../../services/toast';

const requestService = new RequestService();
const fileSizeService = new FileSize();
const toastService = new ToastService();


export default defineComponent({
      name: 'COEmployee002',
      components: {
            Loading
      },
      data() {
            const coEmployeeInfo = {
                  id:'',
                  name: '',
                  role: '',
                  phone: '',
                  address: ''
            };

            const fileInfo = {
                  image: '',
                  file: '',
                  fileSize: '',
                  imgSize: '',
                  fileName: '',
                  imageName: ''
            } as any;

            return {
                  fileInfo,
                  coEmployeeInfo,
                  isInvalide: false,
                  getImage: null,
                  viewType: '',
                  isLoading: false,
                  textBreadcrumb: ''
            }
      },

      created() {
            if (this.$route.params.type === 'edit') {
                  this.viewType = 'edit';
                  this.textBreadcrumb = 'Edit CO Employee'
                  this.coEmployeeDetail(this.$route.params.id);
            } else {
                  this.viewType = 'register'
                  this.textBreadcrumb = 'Create CO Employee'
            }
      },

      methods: {

            handleSave() {

                  if (
                        this.coEmployeeInfo.name === "" ||
                        this.coEmployeeInfo.role === "" ||
                        this.coEmployeeInfo.phone === "" ||
                        this.coEmployeeInfo.address === ""
                  ) {
                        this.isInvalide = true;
                        return;
                  }

                  if (this.$route.params.type === 'edit') {
                        this.handleUpdate();
                  } else {
                        this.handleCreate();
                  }
            },

            async coEmployeeDetail(id: any) {
                  this.isLoading = true;
                  const res = await requestService.detail(`coemployees/${id}`);
                  if (res.status === 200) {
                        this.coEmployeeInfo = {
                              id: res.data.data.id,
                              name: res.data.data.name,
                              role: res.data.data.role,
                              phone: res.data.data.phone,
                              address: res.data.data.address,
                        }
                        this.isLoading = false;
                  }
            },

            async handleCreate() {
                  const body = {
                        name: this.coEmployeeInfo.name,
                        role: this.coEmployeeInfo.role,
                        phone: this.unFormatePhoneNumber(this.coEmployeeInfo.phone),
                        address: this.coEmployeeInfo.address,
                  }

                  await requestService.create('coemployees', body)
                        .then(res => {
                              if (res) {
                                    this.handleBack()
                                    toastService.toastMessage('success', 'Successfully!')
                              }
                        })
                        .catch(err => {
                              if (err) {
                                    toastService.toastMessage('error', 'Something has wrong!', err.response.data.message)
                              }
                        })
            },

            async handleUpdate() {
                  const id = this.coEmployeeInfo.id;
                  const body = {
                        id: id,
                        name: this.coEmployeeInfo.name,
                        role: this.coEmployeeInfo.role,
                        phone: this.unFormatePhoneNumber(this.coEmployeeInfo.phone),
                        address: this.coEmployeeInfo.address,
                  };

                  await requestService.update(`coemployees/${id}`, body).then(res => {
                        if (res) {
                              this.handleBack()
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

            handleBack() {
                  this.$router.push('/coemployee')
            },

            formatPhoneNumber(event: any) {
                  let phoneNumber = event.target.value;
                  this.coEmployeeInfo.phone = phoneNumber.replace(/([0-9]{3})([0-9]{3})([0-9]{3})/, '$1 $2 $3');
            },

            unFormatePhoneNumber(str: string) {
                  return str.replace(/\D/g, "");
            }
      },
})
</script>

<style scoped>
      #picture__input {
            display: none;
      }

      .picture {
            width: 360px;
            aspect-ratio: 16/9;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            border-radius: 5px;
            border: 2px dashed currentcolor;
            cursor: pointer;
            font-family: sans-serif;
            transition: color 300ms ease-in-out, background 300ms ease-in-out;
            outline: none;
            overflow: hidden;
      }

      .picture:hover {
            color: #777;
            background: #ccc;
      }

      .picture:active {
            border-color: turquoise;
            color: turquoise;
            background: #eee;
      }

      .picture:focus {
            color: #777;
            background: #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }

      .picture__img {
            max-width: 100%;
      }
</style>
