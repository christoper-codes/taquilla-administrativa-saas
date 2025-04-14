<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia, Link } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { onMounted, ref } from 'vue';
import EventsIndex from '@/Components/IndicatorsCharts/EventsIndex.vue';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';

const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();


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

const eventSelected = ref(null);
const saleTicketsPerWeek = ref(props.saleTicketsPerWeek);
const today = new Date();
const currentDay = today.getDate();
const currentMonth = today.getMonth() + 1;
const currentYear = today.getFullYear();
const loading = ref(false);
const showEvent = () => {
    loading.value = true;
};
const globalPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: dateFormat(item.start_date),
  };
};

</script>

<template>

    <Head title="indicadores"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Indicadores / Resumen por partidos</span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-min-h-screen tw-w-full tw-block tw-overflow-hidden tw-px-4 lg:tw-p-10 tw-mt-10 lg:tw-mt-0">
            <div class="tw-grid  tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8">
                <div v-for="(amount, type) in saleTicketsPerWeek.type_payments" :key="type">
                    <div data-aos="fade-down" data-aos-duration="2500" class="tw-p-5 tw-shadow-lg tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
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
                            <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-mt-20 tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-between tw-gap-5 tw-mb-20">
                <EventsIndex v-bind:saleTicketsPerWeek="saleTicketsPerWeek" />
                <div class="tw-shadow-lg tw-w-full lg:tw-w-1/3 lg:tw-min-h-60 tw-p-5 tw-rounded-xl">
                    <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-mt-3">
                        <h3 class="tw-text-xs tw-font-bold tw-text-gray-600">Ver detalles de un evento</h3>
                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-pink-100 tw-text-pink-600">folder</span>
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-1">
                        <v-select
                            color="purple"
                            label="Selecciona un evento"
                            hint="Selecciona un evento para ver los detalles"
                            v-model="eventSelected"
                            :item-props="globalPayementTypeProps"
                            :items="saleTicketsPerWeek.events"
                        ></v-select>
                        <Link
                            v-if="eventSelected"
                            :href="route('indicators.show', { slug: eventSelected.slug, id: eventSelected.id } )"
                            >
                            <v-btn @click="showEvent" :loading="loading" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 lg:!tw-flex" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">trending_up</span>Ver detalles</v-btn>
                        </Link>
                        <div v-else>
                            <v-btn variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 lg:!tw-flex" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">trending_up</span>Ver detalles</v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
