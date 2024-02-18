<template>
      <transition name="fade">
            <div class="loader-container" v-if="loading">
                  <div class="spinner-border text-primary" role="status">
                        <span class="sr-only"></span>
                  </div>
            </div>
      </transition>
</template>

<script lang="ts">
import { defineComponent } from "vue";


export default defineComponent({
      name: 'Loading',
      props: {
            loading: Boolean
      },
      data() {
            return {

            }
      }
})

</script>