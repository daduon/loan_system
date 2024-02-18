<template>
      <div class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h5 class="modal-title">Vertically Centered</h5>
                              <button type="button" class="btn-close" @click="cancel"></button>
                        </div>
                        <div class="modal-body">
                              Non omnis incidunt qui sed occaecati magni asperiores est mollitia. Soluta at et reprehenderit.
                              Placeat autem numquam et fuga numquam. Tempora in facere consequatur sit dolor ipsum.
                              Consequatur nemo amet incidunt est facilis. Dolorem neque recusandae quo sit molestias sint
                              dignissimos.
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" @click="cancel">Close</button>
                              <button type="button" class="btn btn-primary" @click="confirm">Save changes</button>
                        </div>
                  </div>
            </div>
      </div>
</template>
    
<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
      props: {
            message: {
                  type: String,
                  required: true,
            } as any,
      },
      data() {
            return {
                  show: true,
            };
      },
      methods: {
            confirm() {
                  this.$emit('confirmed');
                  this.show = false;
            },
            cancel() {
                  this.$emit('canceled');
                  this.show = false;
            },
      },
});
</script>