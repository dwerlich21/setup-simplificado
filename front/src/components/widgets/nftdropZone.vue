<template>
  <div
    :class="{ 'active-dropzone': active }"
    class="dropzone position-relative"
    @dragenter.prevent="toggleActive"
    @dragleave.prevent="toggleActive"
    @dragover.prevent
    @drop.prevent="toggleActive"
  >
    <label
      for="dropzoneFile"
      class="bg-light text-dark stretched-link"
    >Drag and Drop your files or Browse</label>
    <input
      id="dropzoneFile"
      type="file"
      class="dropzoneFile btn btn-soft-primary"
    >
  </div>
</template>


<script>
  import {
    ref
  } from "vue";
  export default {
    name: "Dropzone",
    setup() {
      const active = ref(false);
      const toggleActive = () => {
        active.value = !active.value;
      };
      return {
        active,
        toggleActive
      };
    },
  };
</script>
