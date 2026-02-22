
<template>
  <div
    :class="{ 'active-dropzone': active }"
    class="dropzone position-relative"
    @dragenter.prevent="toggleActive"
    @dragleave.prevent="toggleActive"
    @dragover.prevent
    @drop.prevent="toggleActive"
  >
    <div class="mb-1">
      <i class="display-4 text-muted ri-upload-cloud-2-fill" />
    </div>

    <h5>Drop files here or click to upload.</h5>
    <label
      for="dropzoneFile"
      class="bg-light text-dark stretched-link"
    >Upload</label>
    <input
      id="dropzoneFile"
      type="file"
      class="dropzoneFile btn btn-soft-primary"
    >
  </div>
</template>


<script>
import { ref } from "vue";
export default {
  name: "Dropzone",
  setup() {
    const active = ref(false);
    const toggleActive = () => {
      active.value = !active.value;
    };
    return { active, toggleActive };
  },
};
</script>
