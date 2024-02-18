<template  class="zaffri-modal-template">
      <div v-if="show" class="zaffri-modal">
            <div class="zaffri-modal-body">
                  <div class="row">
                        <div class="col-6">
                              <h4>{{ title }}</h4>
                        </div>
                        <div class="col-6 text-end">
                              <i @click="cancel(false)" class="bi bi-x-lg me-1"></i>
                        </div>
                  </div>
                  <section class="zaffri-modal-message">
                        <p>{{ message }}</p>
                  </section>
                  <div class="zaffri-modal-button-area">
                        <div class="row" v-if="typeBtn == 'confirm'">
                              <div class="col-6">
                                    <button type="button" class="btn btn-secondary" @click="cancel(false)">Cancel</button>
                              </div>
                              <div class="col-6 text-end">
                                    <button type="button" class="btn btn-primary" @click="confirm(true)">Confirm</button>
                              </div>
                        </div>
                        <div class="row" v-else>
                              <div class="col-12 text-end">
                                    <button type="button" class="btn btn-primary" @click="cancel(false)">OK</button>
                              </div>
                        </div>
                  </div>
            </div>

      </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
      name: "Modal",
      props: {
            show: {
                  type: Boolean,
                  default: false,
            },
            title: {
                  type: String,
                  default: "Title",
            },
            message: {
                  type: String,
                  default: "message"
            },
            typeBtn: {
                  type: String,
                  default: ''
            }
      },
      methods: {
            confirm(status:boolean) {
                  this.$emit('confirmed',status);
            },
            cancel(status:boolean) {
                  this.$emit('canceled',status);
            },
      },
});
</script>

<style lang="scss" scoped>
.zaffri-modal-template {
      display: none;
}

.zaffri-modal {
      position: fixed;
      top: 0;
      left: 0;
      z-index: 999999;
      background: rgba(0, 0, 0, 0.75);
      width: 100%;
      height: 100%;
}

.zaffri-modal-body {
      width: 500px;
      min-height: 180px;
      background: #fff;
      border-radius: 20px;
      margin: 0 auto;
      margin-top: 200px;
      padding: 30px 30px 10px 40px;
}

.zaffri-modal-message {
      padding: 15px 0 15px;
}

.zaffri-modal-button-area {
      padding: 25px 0 15px 0;
}

.clear {
      clear: both;
}
</style>