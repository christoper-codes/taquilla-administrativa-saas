<script setup>
import { ref, watch } from 'vue';

const showTostify = ref(false);
const message = ref('Fracaso en el proceso!');

watch(showTostify, (value) => {
    if(value) {
        Toastify({
            text: message.value,
            duration: -1,
            className: "toastify-error",
            newWindow: true,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            onClick: function(){}
        }).showToast();
    }

    showTostify.value = false;
});

</script>

<template>
    <div  v-if="$page.props.flash && $page.props.flash.error" class="tw-text-red-500 tw-my-5">
        <span class="tw-hidden">
            {{ showTostify = true }}
            {{ message = $page.props.flash.error.message }}
        </span>
        <div class="tw-border-t-4 tw-border-tw-danger-primary-500 tw-rounded-lg tw-bg-tw-danger-primary-50 tw-p-4" role="alert" tabindex="-1" aria-labelledby="hs-bordered-red-style-label">
            <div class="tw-flex">
            <div class="tw-shrink-0">
                <!-- Icon -->
                <span class="tw-inline-flex tw-justify-center tw-items-center tw-size-8 tw-rounded-full tw-border-4 tw-border-red-100 tw-bg-red-200 tw-text-red-800">
                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
                </span>
                <!-- End Icon -->
            </div>
            <div class="tw-ms-3">
                <h3 id="hs-bordered-red-style-label" class="tw-text-tw-danger-primary-600 tw-font-semibold">
                {{ $page.props.flash.error.message }}
                </h3>
                <ul class="tw-mt-3 tw-list-disc tw-list-inside tw-text-xs">
                <li v-for="(error, index) in $page.props.flash.error.error" :key="index" class="tw-font-normal tw-text-tw-danger-primary-600">
                    <span class="tw-font-semibold">{{ index }}: </span>
                    <span>{{ error }}</span>
                </li>
                </ul>
            </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
