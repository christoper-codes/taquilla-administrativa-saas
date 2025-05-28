<script setup>
defineProps({
  heightbtn: { type: String, required: false, default: '!tw-h-[50px]' },
  paddingbtn: { type: String, required: false, default: '!tw-px-7' },
  loading: { type: Boolean, required: false, default: false},
})
</script>

<template>
  <v-btn variant="tonal" :loading="loading" :class="`${heightbtn} ${paddingbtn} !tw-rounded-2xl !tw-bg-neutral-100 !tw-text-neutral-700`">
    <slot></slot>
  </v-btn>
</template>
