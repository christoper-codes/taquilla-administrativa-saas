<script setup>
defineProps({
  heightbtn: { type: String, required: false, default: '!tw-h-[50px]' },
  loading: { type: Boolean, required: false, default: false},
  paddingbtn: { type: String, required: false, default: '!tw-px-7' },
})
</script>

<template>
  <v-btn
    variant="tonal"
    :loading="loading"
    :class="`${heightbtn} ${paddingbtn} !tw-bg-primary !tw-text-white !tw-rounded-2xl`"
  >
    <slot></slot>
  </v-btn>
</template>
