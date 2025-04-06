<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import EventsIndex from '@/Components/IndicatorsCharts/EventsIndex.vue';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';
import Eventshow from '@/Components/IndicatorsCharts/Eventshow.vue';

const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
    "historyPerEvent" : {
        Type: Object,
        Required: true
    },
})

console.log(props.historyPerEvent);
const today = new Date();
const currentDay = today.getDate();
const currentMonth = today.getMonth() + 1;
const currentYear = today.getFullYear();

// Headers for the data table

const amountOwed = ref(0);
const items = ref([]);
const headerProps = {
    class: '!tw-font-bold'
};

const headers = [
    { title: 'Folio', key: 'Folio' },
    { title: 'Estatus', key: 'Estatus' },
    { title: 'Asientos', key: 'Asientos' },
    { title: 'Monto total de venta', key: 'Monto total de venta' },
    { title: 'Monto Pagado', key: 'Monto Pagado' },
    { title: 'Cambio', key: 'Cambio' },
    { title: 'Tipos de pago', key: 'Tipos de pago' },
    { title: 'Promoción', key: 'Promotion' },
    { title: 'Venta a meses', key: 'Venta a meses' },
    { title: 'Venta a plazos', key: 'Venta a plazos' },
    { title: 'Adeudo', key: 'Adeudo' },
    { title: 'Fecha de venta', key: 'Fecha de venta' },
];

props.historyPerEvent.new_data.sale_tickets.forEach((saleTicket) => {
        const paymentTypes = saleTicket.global_payment_types.map(paymentType => {
            return `${paymentType.name}: ${paymentType.pivot.amount}`;
        }).join(', ');
        const seatCatalogues  = saleTicket.event_seat_catalogs.map(seatCatalogue => {
            return `${seatCatalogue.seat_catalogue.code}`
        }).join(', ');

        const totalReturned = saleTicket.sale_ticket_status.name == 'cancelado' ? 0 : saleTicket.total_returned;
        const adeudo = ref(0);
        if(saleTicket.sale_ticket_status.name == 'pendiente'){
            adeudo.value = Number(saleTicket.total_amount - (Number(saleTicket.amount_received)-Number(saleTicket.total_returned)));
        } else {
            adeudo.value = 0;
        }

        items.value.push({
            'Folio': saleTicket.id,
            'Estatus': saleTicket.sale_ticket_status.name,
            'Asientos': seatCatalogues,
            'Monto total de venta': formatPrice(saleTicket.total_amount),
            'Monto Pagado': formatPrice((Number(Number(saleTicket.amount_received)-Number(saleTicket.total_returned)))),
            'Cambio': formatPrice(totalReturned),
            'Tipos de pago': paymentTypes,
            'Promotion': saleTicket.promotion_id ? `${saleTicket.promotion.name}` : 'Sin promoción',
            'Venta a meses': saleTicket.payment_in_installments ? saleTicket.payment_in_installments : 'No aplica',
            'Venta a plazos': saleTicket.sale_debtor_id ? saleTicket.sale_debtor.first_name : 'No aplica',
            'Adeudo': formatPrice(adeudo.value),
            'Fecha de venta': dateFormat(saleTicket.created_at),
        });

        amountOwed.value += Number(adeudo.value);
    });
</script>

<template>

    <Head title="indicadores"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Indicadores / Resumen {{ historyPerEvent.event.name }} </span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-w-full tw-block tw-overflow-hidden tw-px-4 tw-pt-10 lg:tw-px-0 lg:tw-pt-0 tw-mb-20 tw-bg-white tw-pb-10">
            <section class=" lg:tw-p-10 tw-overflow-hidden tw-mt-0 tw-flex tw-flex-col lg:tw-flex-row tw-items-start tw-justify-between lg:tw-gap-10">
                <div class="tw-w-full lg:tw-w-1/2">
                    <img class="tw-w-full tw-rounded-xl" :src="`/storage/${historyPerEvent.event.global_image.file_path}`" alt="">
                </div>
                <div class="tw-w-full lg:tw-w-1/2 tw-shadow-lg tw-p-7 tw-rounded-lg tw-min-h-60 tw-mb-5">
                    <h2 class="tw-font-bold tw-text-2xl lg:tw-text-4xl">
                        {{ historyPerEvent.event.name }}
                    </h2>
                    <div class="tw-mt-5">
                        <div v-for="(sales, type) in historyPerEvent.type_sales" :key="type">
                            <div v-if="type == 'total'">
                                <h4 class="tw-font-bold tw-text-xl tw-bg-clip-text tw-bg-gradient-to-r tw-from-purple-600 tw-to-yellow-500 tw-text-transparent">Aforo esperado: {{ sales.sales }} <span class="tw-text-base">(asistentes)</span> </h4>
                            </div>
                        </div>
                    </div>
                    <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-mt-6 lg:tw-grid-cols-2">
                        <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                            <span class="material-symbols-outlined tw-text-xl">calendar_today</span>{{ historyPerEvent.event.serie.global_season.name }}
                        </div>
                        <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                            <span class="material-symbols-outlined tw-text-xl">location_on</span>El nido del halcon
                        </div>
                        <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                            <span class="material-symbols-outlined tw-text-xl">calendar_clock</span>{{ dateFormat(historyPerEvent.event.start_date) }}
                        </div>
                    </div>

                </div>
            </section>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8 tw-mt-10 lg:tw-mt-0  lg:tw-p-10">
                <div v-for="(amount, type) in historyPerEvent.type_payments" :key="type">
                    <div data-aos="fade-left" data-aos-duration="2500" class="tw-p-5 tw-shadow-lg tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                            <h3>Ventas para este evento</h3>
                            <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-pink-100 tw-text-green-600">payments</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
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
                <div data-aos="fade-left" data-aos-duration="2500" class="tw-p-5 tw-shadow-lg tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                    <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                        <h3>Adeudo para este evento</h3>
                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-pink-100 tw-text-red-600">payments</span>
                    </div>
                    <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                            <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-red-500 tw-text-white">bar_chart</span>
                            <h3>Adeudo</h3>
                        </div>
                        <span class="tw-block tw-font-bold">{{ formatPrice(amountOwed) }} MXN</span>
                    </div>
                    <div>
                        <p class="tw-text-right tw-text-xs">Actualizado al dia: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                    </div>
                </div>
            </div>

            <div class="tw-w-full lg:tw-px-10">
                <Eventshow v-bind:salesPerDay="historyPerEvent.sales_per_day" />
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8 tw-mt-10 lg:tw-mt-0  lg:tw-p-10">
                <div v-for="(sales, type) in historyPerEvent.type_sales" :key="type">
                    <div data-aos="fade-left" data-aos-duration="2500" class="tw-p-5 tw-shadow-lg tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                            <h3 v-if="type != 'total'">Tipos de asientos vendidos</h3>
                            <h3 v-else class="tw-text-2xl tw-font-bold">Ventas totales</h3>
                            <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-blue-100 tw-text-blue-600">trending_up</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                <span v-if="type != 'total'" class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-yellow-500 tw-text-white">confirmation_number</span>
                                <span v-else class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-red-500 tw-text-white">confirmation_number</span>
                                <h3>{{ type }}</h3>
                            </div>
                            <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos)</span> </p>
                        </div>
                        <div>
                            <p class="tw-text-right tw-text-xs">Actualizado al dia: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-w-full tw-mt-10 tw-px-0 lg:tw-px-10">
                <v-data-table :items="items" :headers="headers" :header-props="headerProps">
                    <template v-slot:item.Estatus="{ item }">
                        <span
                            class="tw-py-1 tw-px-4 tw-rounded-full"
                            :class="{
                                '!tw-text-green-600 tw-bg-green-100': item.Estatus === 'pagado',
                                '!tw-text-red-600 tw-bg-red-100': item.Estatus === 'cancelado',
                                '!tw-text-yellow-600 tw-bg-yellow-100': item.Estatus === 'pendiente'
                            }"
                        >
                            {{ item.Estatus }}
                        </span>
                    </template>
                </v-data-table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
