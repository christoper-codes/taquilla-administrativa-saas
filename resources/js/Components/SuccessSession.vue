<script setup>
import { ref, watch } from 'vue';

const showTostify = ref(false);
const message = ref('Exito en el proceso!');

watch(showTostify, (value) => {
    if(value) {
        Toastify({
            text: message.value,
            duration: 5000,
            className: "toastify-success",
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
    <div  v-if="$page.props.flash && $page.props.flash.success" class="tw-z-50 tw-relative">
        <span class="tw-hidden">
            {{ showTostify = true }}
            {{ message = $page.props.flash.success.message }}
        </span>
    </div>
</template>

<style scoped>

</style>
