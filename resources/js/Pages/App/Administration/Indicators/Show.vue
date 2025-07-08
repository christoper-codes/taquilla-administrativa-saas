<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';
import useEventResume from '@/composables/exports/eventResume';
import Eventshow from '@/Components/IndicatorsCharts/Eventshow.vue';
import { toast } from 'vue3-toastify';
import useStringFormat from '@/composables/stringFormat';
import ExportButton from '@/Components/buttons/ExportButton.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';

const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();
const { formatFirstLetterUppercase } = useStringFormat();
const { exportSummaryByTickets } = useEventResume();

const dateToFindResume = ref(null);
const itemsPerDate = ref([]);
const salesPerDate = ref({efectivo: 0, tarjeta: 0, adeudo: 0, total: 0, venta: 0});

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
    { title: 'Tipo de venta', key: 'Tipo de venta' },
    { title: 'Total asientos vendidos', key: 'Total asientos vendidos' },
    { title: 'Asientos', key: 'Asientos' },
    { title: 'Monto total de venta', key: 'Monto total de venta' },
    { title: 'Monto Pagado', key: 'Monto Pagado' },
    { title: 'Adeudo', key: 'Adeudo' },
    { title: 'Tipos de pago', key: 'Tipos de pago' },
    { title: 'Promoción', key: 'Promotion' },
    { title: 'Venta a meses', key: 'Venta a meses' },
    { title: 'Deudor', key: 'Deudor' },
    { title: 'Fecha de venta', key: 'Fecha' },
];

props.historyPerEvent.new_data.sale_tickets
    .slice()
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .forEach((saleTicket) => {
    const paymentTypes = saleTicket.global_payment_types.map(paymentType => {
        let paymentInfo = `${formatFirstLetterUppercase(paymentType.name)}: ${paymentType.pivot.amount}`;
        if (paymentType.name === 'tarjeta' && paymentType.pivot.card_type_name) {
            paymentInfo += ` (${paymentType.pivot.card_type_name})`;
        }
        return paymentInfo;
    }).join(', ');

    const seatCatalogues = saleTicket.event_seat_catalogs.map(seatCatalogue => {
        return `${seatCatalogue.seat_catalogue.code}`;
    }).join(', ');

    const totalReturned = saleTicket.sale_ticket_status.name === 'cancelado' ? 0 : saleTicket.total_returned;
    const adeudo = ref(0);
    if (saleTicket.sale_ticket_status.name === 'pendiente') {
        adeudo.value = Number(saleTicket.total_amount - (Number(saleTicket.installment_payment_histories.reduce((sum, item) => sum + parseFloat(item.total_amount), 0))));
    } else {
        adeudo.value = 0;
    }

    const originalDate = new Date(saleTicket.created_at);
    originalDate.setHours(originalDate.getHours() - 6);
    const adjustedDate = originalDate.toISOString();
    items.value.push({
        'Folio': saleTicket.id,
        'Estatus': saleTicket.sale_ticket_status.name,
        'Tipo de venta': saleTicket.is_online ? 'En linea' : 'Taquilla',
        'Total asientos vendidos': saleTicket.event_seat_catalogs.length,
        'Asientos': seatCatalogues,
        'Monto total de venta': formatPrice(saleTicket.total_amount),
        'Monto Pagado': formatPrice((Number(Number(saleTicket.amount_received) - Number(saleTicket.total_returned)))),
        'Adeudo': formatPrice(adeudo.value),
        'Tipos de pago': paymentTypes,
        'Promotion': saleTicket.promotion_id ? `${saleTicket.promotion.name}` : 'N/A',
        'Venta a meses': saleTicket.payment_in_installments ? saleTicket.payment_in_installments : 'N/A',
        'Deudor': saleTicket.sale_debtor_id ? `${saleTicket.sale_debtor.first_name} ${saleTicket.sale_debtor.last_name}` : 'N/A',
        'Fecha': adjustedDate,
    });

    amountOwed.value += Number(adeudo.value);
});

const filterByDate = () => {
    if (!dateToFindResume.value) {
        itemsPerDate.value = [];
        return;
    }
    const selectedDate = new Date(dateToFindResume.value).toISOString().split('T')[0];
    itemsPerDate.value = items.value.filter(item => {
        const itemDate = new Date(item['Fecha']).toISOString().split('T')[0];
        return itemDate === selectedDate;
    });

    salesPerDate.value = {efectivo: 0, tarjeta: 0, adeudo: 0, total: 0, venta: 0};

    itemsPerDate.value.forEach((item) => {
        if (item.Estatus === 'pagado' || item.Estatus === 'pendiente') {
           const paymentTypes = item['Tipos de pago'].split(',').reduce((acc, payment) => {
                const [key, value] = payment.split(':').map(str => str.trim());
                acc[key.toLowerCase()] = parseFloat(value);
                return acc;
            }, {});
            const seatsSolt = item.Asientos.split(',').length;
            salesPerDate.value.venta += seatsSolt;

            if (paymentTypes.efectivo) {
                salesPerDate.value.efectivo += paymentTypes.efectivo;
            }

            if (paymentTypes.tarjeta) {
                salesPerDate.value.tarjeta += paymentTypes.tarjeta;
            }

            const adeudo = parseFloat(item['Adeudo'].replace('$', '').replace(',', ''));
            salesPerDate.value.adeudo += adeudo;

            const total = parseFloat(item['Monto Pagado'].replace('$', '').replace(',', ''));
            salesPerDate.value.total += total;
        }
    });

    toast('Datos obtenidos con exito!', {
        "theme": "auto",
        "type": "success",
        "dangerouslyHTMLString": true
    })
};

const allowedDates = ref([]);
const generateAllowedDates = () => {
    allowedDates.value = items.value
        .filter(item => item.Estatus === 'pagado' || item.Estatus === 'pendiente')
        .map(item => {
            const date = new Date(item.Fecha);
            return date.toISOString().split('T')[0];
        });
};

const isAllowedDate = (val) => {
    const formattedDate = val.toISOString().split('T')[0];
    return allowedDates.value.includes(formattedDate);
};

onMounted(() => {
    generateAllowedDates();
});
</script>

<template>

    <Head title="indicadores"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <div class="tw-relative tw-w-full tw-block tw-overflow-hidden tw-px-4 tw-pt-10 lg:tw-px-0 lg:tw-pt-10 tw-mb-20 tw-pb-10">
            <section
                class="tw-w-full tw-relative tw-bg-cover tw-bg-center lg:tw-h-[450px] tw-flex tw-flex-col tw-items-start tw-justify-center tw-rounded-2xl tw-overflow-hidden"
                :style="{ backgroundImage: `url(/storage/${historyPerEvent.event.global_image.file_path})` }"
                >
                <div class="tw-w-full tw-rounded-xl lg:tw-rounded-none tw-bg-black/40 tw-p-7 tw-text-white tw-backdrop-blur-md tw-h-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-6">
                    <h2 data-aos="fade-left" data-aos-duration="2500" class="tw-font-bold tw-text-2xl lg:tw-text-4xl">
                        {{ historyPerEvent.event.name }}
                    </h2>
                    <div>
                    <div v-for="(sales, type) in historyPerEvent.type_sales" :key="type">
                        <div v-if="type == 'total'">
                       <h4 class="tw-font-bold tw-text-xl">
                            Aforo esperado: {{ sales.sales + (historyPerEvent.availability.abonados || 0) }}
                            <span class="tw-text-base">(asistentes)</span>
                        </h4>
                        </div>
                    </div>
                    </div>

                    <div data-aos="fade-left" data-aos-duration="2500" class="tw-grid lg:tw-grid-cols-3 tw-gap-5 tw-w-full">
                        <div class="tw-p-10 tw-bg-white/20 tw-rounded tw-text-center">
                            <h3 class="tw-font-bold">Temporada</h3>
                            <p>{{ historyPerEvent.event.serie.global_season.name }}</p>
                        </div>
                        <div class="tw-p-10 tw-bg-white/20 tw-rounded tw-text-center">
                            <h3 class="tw-font-bold">Ubicación</h3>
                            <p>{{ historyPerEvent.event.serie.global_season.name }}</p>
                        </div>
                        <div class="tw-p-10 tw-bg-white/20 tw-rounded tw-text-center">
                            <h3 class="tw-font-bold">Fecha</h3>
                            <p>{{ dateFormat(historyPerEvent.event.start_date) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tw-mt-16 lg:tw-px-10">
                <h2 class="tw-text-4xl tw-font-bold">Disponibilidad de asientos</h2>
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8 tw-mt-5 tw-mb-16">
                    <div v-for="(count, type) in historyPerEvent.availability" :key="type">
                        <div  class="tw-p-5 tw-border-[3px] tw-border-gray-200 tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                            <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                    <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-blue-500 tw-text-white">check_circle</span>
                                    <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                </div>
                                <span class="tw-block tw-font-bold">{{ count }}</span>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-3">
                                 <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-blue-100 tw-text-blue-600">check_circle</span>
                                <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tw-mt-16 lg:tw-px-10">
                <h2 class="tw-text-4xl tw-font-bold">Venta general</h2>
            </div>
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8 lg:tw-px-10 tw-mt-5 tw-mb-16">
                <div v-for="(amount, type) in historyPerEvent.type_payments" :key="type">
                    <div  class="tw-p-5 tw-border-[3px] tw-border-gray-200 tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                            <h3>Ventas para este evento</h3>
                            <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-full tw-bg-green-100 tw-text-green-600">payments</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-green-500 tw-text-white">bar_chart</span>
                                <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                            </div>
                            <span class="tw-block tw-font-bold">{{ formatPrice(amount.amount) }} MXN</span>
                        </div>
                        <div>
                            <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                        </div>
                    </div>
                </div>
                <div  class="tw-p-5 tw-border-[3px] tw-border-gray-200 tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
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
                        <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                    </div>
                </div>
            </div>
            <div class="tw-w-full lg:tw-px-10 tw-flex tw-flex-col lg:tw-flex-row tw-justify-between tw-gap-10 tw-mt-16">
                <div class="tw-w-full lg:tw-w-1/3">
                    <div class="tw-w-full">
                        <v-date-picker header="Venta por día" title="Selecciona una fecha"
                            v-model="dateToFindResume"
                            :allowed-dates="isAllowedDate"
                            class="!tw-w-full"
                        ></v-date-picker>

                        <PrimaryButton @click="filterByDate" heightbtn="!tw-h-[60px]" paddingbtn="!tw-w-full !tw-block">
                            <span>Buscar resumen</span>
                        </PrimaryButton>
                    </div>
                </div>
                <div class="tw-w-full lg:tw-w-2/3">
                    <div v-if="itemsPerDate.length > 0">
                        <div class="tw-mb-16">
                            <div class="tw-mb-10 mt-2">
                                <h2 class="tw-text-4xl tw-font-bold">Venta</h2>
                                <h3 class="tw-text-lg tw-mt-4">{{ formatPrice(salesPerDate.total) }} MXN</h3>
                                <h3 class="tw-text-lg">{{ salesPerDate.venta }} asientos vendidos</h3>
                            </div>
                            <div class="tw-w-full tw-grid lg:tw-grid-cols-2 tw-gap-10">
                                <div class="tw-p-5 tw-text-center tw-shadow-xl tw-border-b-8 tw-border-b-green-500 tw-rounded-lg w-full">
                                    <p class="tw-font-bold tw-opacity-50">Efectivo</p>
                                    <h3 class="tw-text-2xl tw-font-bold mt-2">{{ formatPrice(salesPerDate.efectivo) }} MXN</h3>
                                </div>
                                <div class="tw-p-5 tw-text-center tw-shadow-xl tw-border-b-8 tw-border-b-blue-500 tw-rounded-lg w-full">
                                    <p class="tw-font-bold tw-opacity-50">Tarjeta</p>
                                    <h3 class="tw-text-2xl tw-font-bold mt-2">{{ formatPrice(salesPerDate.tarjeta) }} MXN</h3>
                                </div>
                                <div class="tw-p-5 tw-text-center tw-shadow-xl tw-border-b-8 tw-border-b-red-500 tw-rounded-lg w-full">
                                    <p class="tw-font-bold tw-opacity-50">Adeudo</p>
                                    <h3 class="tw-text-2xl tw-font-bold mt-2">{{ formatPrice(salesPerDate.adeudo) }} MXN</h3>
                                </div>
                                <div class="tw-p-5 tw-text-center tw-shadow-xl tw-border-b-8 tw-border-b-yellow-500 tw-rounded-lg w-full">
                                    <p class="tw-font-bold tw-opacity-50">Total</p>
                                    <h3 class="tw-text-2xl tw-font-bold mt-2">{{ formatPrice(salesPerDate.total) }} MXN</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="tw-flex tw-bg-white tw-items-center tw-justify-center tw-text-center tw-min-h-[500px] tw-shadow-lg tw-rounded-xl tw-flex-col tw-gap-1">
                        <h2 class="tw-text-lg">Seleccione una fecha válida para un resumen de venta</h2>
                    </div>
                </div>
            </div>

            <div v-if="itemsPerDate.length > 0" class="tw-w-full tw-mt-16 lg:tw-px-10">
                <v-data-table :items="itemsPerDate" :headers="headers" :header-props="headerProps">
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

                    <template v-slot:item.Fecha="{ item }">
                        {{ dateFormat(item.Fecha) }}
                    </template>
                </v-data-table>
            </div>

            <div class="tw-w-full lg:tw-px-10 tw-mt-10 tw-bg-white">
                <Eventshow v-bind:salesPerDay="historyPerEvent.sales_per_day" />
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8 tw-mt-10  lg:tw-p-10">
                <div v-for="(sales, type) in historyPerEvent.type_sales" :key="type" :class="{ 'lg:tw-col-span-3': type == 'total'}">
                    <div class="tw-border-[3px] tw-border-gray-200 tw-p-5 tw-rounded-2xl tw-bg-white tw-flex tw-flex-col tw-justify-between tw-gap-5">
                        <div v-if="type == 'total'" class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                            <h3 class="tw-text-3xl tw-font-bold tw-mb-2">Asientos vendidos</h3>
                            <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                    <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-green-500 tw-text-white">trending_up</span>
                                    <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                </div>
                                <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos)</span> </p>
                            </div>
                            <div>
                                <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                            </div>
                        </div>
                        <div v-else-if="type == 'online'">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-3">
                                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-blue-500 tw-text-white">confirmation_number</span>
                                        <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                    </div>
                                    <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos)</span> </p>
                                </div>
                                <div>
                                    <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="type == 'taquilla'">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-3">
                                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-pink-500 tw-text-white">confirmation_number</span>
                                        <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                    </div>
                                    <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos)</span> </p>
                                </div>
                                <div>
                                    <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="type == 'taquilla cortesias'">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-3">
                                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-pink-500 tw-text-white">confirmation_number</span>
                                        <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                    </div>
                                    <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos cortesias)</span> </p>
                                </div>
                                <div>
                                    <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-3">
                                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-xl">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-font-bold">
                                        <span class="material-symbols-outlined tw-block tw-p-2 tw-rounded-md tw-bg-yellow-500 tw-text-white">confirmation_number</span>
                                        <h3>{{ formatFirstLetterUppercase(type) }}</h3>
                                    </div>
                                    <p class="tw-block tw-font-bold">{{ sales.sales }} <span class="tw-text-xs">(asientos)</span> </p>
                                </div>
                                <div>
                                    <p class="tw-text-right tw-text-xs">Actualizado al día: {{currentDay + '/' + currentMonth + '/' + currentYear }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-w-full tw-mt-16 tw-px-0 lg:tw-px-10">
                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-5">
                    <h2 class="tw-font-bold tw-text-4xl">Historial de ventas</h2>
                    <ExportButton @click="exportSummaryByTickets(historyPerEvent.event.id)" heightbtn="!tw-h-[60px]" paddingbtn="!tw-px-14" bgbtn="!tw-bg-green-500">
                        <span>Exportar a Excel</span>
                    </ExportButton>
                </div>
                <v-data-table :items="items" :headers="headers" :header-props="headerProps">
                    <template v-slot:item.Estatus="{ item }">
                        <span
                            class="tw-py-1 tw-px-4 tw-rounded-full"
                            :class="{
                                '!tw-text-green-600 tw-bg-green-100': item.Estatus === 'pagado',
                                '!tw-text-red-600 tw-bg-red-100': item.Estatus === 'cancelado',
                                '!tw-text-orange-600 tw-bg-orange-100': item.Estatus === 'parcialmente cancelado',
                                '!tw-text-yellow-600 tw-bg-yellow-100': item.Estatus === 'pendiente'
                            }"
                        >
                            {{ formatFirstLetterUppercase(item.Estatus) }}
                        </span>
                    </template>
                    <template v-slot:item.Fecha="{ item }">
                        {{ dateFormat(item.Fecha) }}
                    </template>
                </v-data-table>
            </div>
        </div>
    </AppLayout>
</template>
