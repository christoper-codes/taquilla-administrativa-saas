<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { onMounted, ref } from 'vue';
import EventsIndex from '@/Components/IndicatorsCharts/EventsIndex.vue';
import usePriceFormat from '@/composables/priceFormat';

const { formatPrice } = usePriceFormat();

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
    "saleTicketsPerWeek": {
        Type: Array,
        Required: true
    },
})

const saleTicketsPerWeek = ref(props.saleTicketsPerWeek);
const today = new Date();
const currentDay = today.getDate();
const currentMonth = today.getMonth() + 1;
const currentYear = today.getFullYear();

</script>

<template>

    <Head title="indicadores"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Indicadores / Resumen por partidos</span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-min-h-screen tw-w-full tw-block tw-overflow-hidden tw-p-10">
            <div class="tw-grid tw-grid-cols-3 tw-gap-8">
                <div v-for="(amount, type) in saleTicketsPerWeek.type_payments" :key="type">
                    <div class="tw-p-5 tw-shadow-lg tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                            <h3>Ventas de este mes</h3>
                            <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-pink-100 tw-text-pink-600">trending_up</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                            <div class="tw-flex tw-items-center tw-gap-1 tw-font-bold">
                                <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-green-500 tw-text-white">bar_chart</span>
                                <h3>{{ type }}</h3>
                            </div>
                            <span class="tw-block tw-font-bold">{{ formatPrice(amount.amount) }} MXN</span>
                        </div>
                        <div>
                            <p class="tw-text-right tw-text-xs">Actualizado al dia: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-mt-20 tw-w-full">
                <EventsIndex v-bind:saleTicketsPerWeek="saleTicketsPerWeek" />
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
