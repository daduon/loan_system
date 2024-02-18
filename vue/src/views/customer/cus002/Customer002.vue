<template src="./Customer002.html"></template>

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
      name: 'Customer002',
      components: {
            Loading
      },
      data() {
            const customerInfo = {
                  customerPicture: '',
                  customerName: '',
                  customerPhone: '',
                  customerGender: '1',
                  customerDod: '',
                  customerAddress: '',
                  customerEmail: '',
                  customerIdentity: '',
                  customerId: '',
                  id: '',
                  isUpdateType: ''
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
                  isInvalide: false,
                  getImage: null,
                  viewType: '',
                  isLoading: false,
                  textBreadcrumb: ''
            }
      },

      created() {
            this.getCustomerType();
            if (this.$route.params.type === 'edit') {
                  this.viewType = 'edit';
                  this.textBreadcrumb = 'Edit Customer'
                  this.customerDetail(this.$route.params.id);
            } else {
                  this.viewType = 'register'
                  this.textBreadcrumb = 'Create Customer'
            }
      },

      methods: {

            handleSave() {

                  if (
                        this.customerInfo.customerName === "" ||
                        this.customerInfo.customerId === "" ||
                        this.customerInfo.customerPhone === "" ||
                        this.customerInfo.customerIdentity === "" ||
                        this.customerInfo.customerDod === "" ||
                        this.customerInfo.customerAddress === ""
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

            async getCustomerType() {
                  const res = await requestService.list('customer_types');
                  if (res.status === 200) {
                        this.customerTypeList = res.data;
                  }
            },

            async customerDetail(id: any) {
                  this.isLoading = true;
                  const res = await requestService.detail(`customers/${id}`);
                  if (res.status === 200) {
                        this.fileInfo.image = res.data.data.customer_picture;
                        this.customerInfo = {
                              id: res.data.data.id,
                              customerPicture: res.data.data.customer_picture,
                              customerName: res.data.data.customer_name,
                              customerPhone: res.data.data.customer_phone,
                              customerGender: res.data.data.customer_gender,
                              customerDod: res.data.data.customer_dob,
                              customerAddress: res.data.data.customer_address,
                              customerEmail: res.data.data.customer_email,
                              customerIdentity: res.data.data.customer_identity,
                              customerId: res.data.data.customer_type_id.id,
                              isUpdateType: res.data.data.isUpdateType
                        }
                        this.isLoading = false;
                  }
            },

            async handleCreate() {
                  const body = {
                        customer_picture: this.fileInfo.image,
                        customer_name: this.customerInfo.customerName,
                        customer_phone: this.unFormatePhoneNumber(this.customerInfo.customerPhone),
                        customer_gender: this.customerInfo.customerGender,
                        customer_dob: this.customerInfo.customerDod,
                        customer_address: this.customerInfo.customerAddress,
                        customer_email: this.customerInfo.customerEmail,
                        customer_identity: this.customerInfo.customerIdentity,
                        customer_type_id: this.customerInfo.customerId
                  }

                  await requestService.create('customers', body)
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
                  const id = this.customerInfo.id;
                  const body = {
                        id: id,
                        customer_picture: this.fileInfo.image,
                        customer_name: this.customerInfo.customerName,
                        customer_phone: this.customerInfo.customerPhone,
                        customer_gender: this.customerInfo.customerGender,
                        customer_dob: this.customerInfo.customerDod,
                        customer_address: this.customerInfo.customerAddress,
                        customer_email: this.customerInfo.customerEmail,
                        customer_identity: this.customerInfo.customerIdentity,
                        customer_type_id: this.customerInfo.customerId
                  };

                  await requestService.update(`customers/${id}`, body).then(res => {
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
                  this.$router.push('/customer')
            },

            formatPhoneNumber(event: any) {
                  let phoneNumber = event.target.value;
                  this.customerInfo.customerPhone = phoneNumber.replace(/([0-9]{3})([0-9]{3})([0-9]{3})/, '$1 $2 $3');
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