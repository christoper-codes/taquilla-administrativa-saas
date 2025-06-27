<script setup>
    defineProps({
        heightbtn: { type: String, required: false, default: '!tw-h-[50px]' },
        loading: { type: Boolean, required: false, default: false},
        paddingbtn: { type: String, required: false, default: '!tw-px-7' },
        bgbtn: { type: String, required: false, default: '!tw-bg-primary' },
    })
</script>

<template>
  <v-btn
    variant="tonal"
    :loading="loading"
    :class="`${heightbtn} ${paddingbtn} ${bgbtn} !tw-text-white !tw-rounded-2xl !tw-flex !tw-items-center !tw-justify-center !tw-gap-5`"
  >
    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">file_save</span>
    <slot></slot>
  </v-btn>
</template>
