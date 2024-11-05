<script setup>
    const props = defineProps({
        activate: { type: Boolean, required: true },
    })
</script>
<template>

<div class="text-center">
    <v-overlay :model-value="activate" class="align-center justify-center">
      <v-progress-circular color="primary" size="64" indeterminate></v-progress-circular>
    </v-overlay>
  </div>
</template>
<style scoped>
</style>
