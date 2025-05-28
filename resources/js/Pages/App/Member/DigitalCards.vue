<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { ref } from 'vue';

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
})

</script>

<template>

    <Head title="mis tarjetas"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Mis tarjetas digitales</span>
        </BreadcrumbAppSecondary>

    </AppLayout>
</template>

<style scoped>

</style>
