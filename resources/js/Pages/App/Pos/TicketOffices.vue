<script setup>
import { Head, Link, router,  useForm as useFormInertia } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import useStringFormat from '@/composables/stringFormat';
import AppNav from '@/Components/navs/AppNav.vue';
import GuestNav from '@/Components/navs/GuestNav.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';
import useDateFormat from '@/composables/dateFormat';
import usePriceFormat from '@/composables/priceFormat';
import axios from 'axios';
import { toast } from 'vue3-toastify'

const { dateFormat } = useDateFormat();
const { formatPrice } = usePriceFormat();

const props =  defineProps({
    'ticket_offices': {
        type: Array,
        required: true,
    }
})
const dialog = ref(false);
const currentDate = new Date();
const formattedDate = currentDate.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
});
const tabs = ref(null);
const saleTicketsSelected = ref([]);
const paymentTypes = ref([]);
const paymentTypesSelected = ref([]);
const items = ref([]);
const loadingPrint = ref(false);
const loadingCancel = ref(false);
const cancelSeatCodes = ref([]);
const cancelPassword = ref('');
const { formatFirstLetterUppercase } = useStringFormat();
const headers = [
    { title: 'Folio', key: 'Folio' },
    { title: 'Estatus', key: 'Estatus' },
    { title: 'Promoción', key: 'Promotion' },
    { title: 'Fecha de venta', key: 'Fecha de venta' },
    { title: 'Fue transferido', key: 'Fue transferido' },
    { title: 'Asientos', key: 'Asientos' },
    { title: 'Verificados', key: 'Verificados' },
    { title: 'Monto Pagado', key: 'Monto Pagado' },
    { title: 'Monto total', key: 'Monto total' },
    { title: 'Adeudo', key: 'Monto restante' },
    { title: 'Pagos realizados', key: 'Tipos de pago' },
    {title: 'Acciones', key: 'Acciones', sortable:false}
];
const headerProps = {
    class: '!tw-font-bold'
};
const updateSaleTicketsSelected = (item) => {
    paymentTypesSelected.value = [];
    cancelSeatCodes.value = [];
    saleTicketsSelected.value = item.Asientos.split(',');
    paymentTypes.value = item['Tipos de pago'].split(',');
}

const cancelTicket = (item, isActive) => {
    if(paymentTypes.value.length != paymentTypesSelected.value.length) {
        toast('Se deben selecionar todos los tipos de pago para ordenar el descuento de la venta', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return
    }
    const paymentTypesSelectedKeys = paymentTypesSelected.value.map(item => {
        return item.split(':')[0].trim();
    });

    const isPartialCancel = ref(false);

    if(cancelSeatCodes.value.length > 0) isPartialCancel.value = true

    const data  = useFormInertia({
        sale_ticket_id: item.Folio,
        is_partial_cancel: isPartialCancel.value,
        cancel_seat_codes: cancelSeatCodes.value,
        payment_types_selected_keys: paymentTypesSelectedKeys
    });

    loadingCancel.value = true;

    data.delete(route('sale-ticket.cancelation-sale-ticket'), {
        onSuccess: (response) => {
            toast('Los asientos se han cancelado exitosamente!', {
                "theme": "auto",
                "type": "success",
                "dangerouslyHTMLString": true
            })
            searchSaleTicket();
            isActive.value = false;
        },
        onError: (error) => {
            toast('Hubo un error al cancelar los asientos', {
                "theme": "auto",
                "type": "error",
                "autoClose": 10000,
                "dangerouslyHTMLString": true
            })
        },
        onFinish: () => {
            loadingCancel.value = false;
            isActive.value = false;
        }
    })
};
const openned = ref(false);

/*
* Search sale tickets by folio or id
*/
const sale_ticket_id = ref('');
const loadingSearch = ref(false);
const searchSaleTicket = () => {
    if(sale_ticket_id.value == '') {
        toast('El folio o id del ticket no puede estar vacio', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return;
    }
    loadingSearch.value = true;
    items.value = [];
    axios.get(route('ticket-offices.ticket.details', {id: sale_ticket_id.value}))
        .then(response => {
            console.log(response.data);
            const saleTicket = response.data.data.sale_ticket;
            const paymentTypes = saleTicket.global_payment_types.map(paymentType => {
                return `${formatFirstLetterUppercase(paymentType.name)}: ${paymentType.pivot.amount}`;
            }).join(', ');
            const seatCatalogues  = saleTicket.event_seat_catalogues.map(seatCatalogue => {
                return `${seatCatalogue.seat_catalogue.code}`;
            }).join(', ');

            const seatCataloguesVerified = saleTicket.event_seat_catalogues.map(seatCatalogue => {
                return `${seatCatalogue.seat_catalogue.code} - ${seatCatalogue.seat_catalogue.is_verified ? 'Verificado' : 'No verificado'}`;
            }).join(', ');

            items.value.push({
                'Folio': saleTicket.id,
                'Estatus': saleTicket.sale_ticket_status.name,
                'Promotion': saleTicket.promotion,
                'Fecha de venta': dateFormat(saleTicket.created_at),
                'Fue transferido': saleTicket.is_transfer ? 'Si' : 'No',
                'Asientos': seatCatalogues,
                'Verificados': seatCataloguesVerified,
                'Monto total': formatPrice(saleTicket.total_amount),
                'Monto Pagado': formatPrice((Number(Number(saleTicket.amount_received)-Number(saleTicket.total_returned)))),
                'Monto restante': formatPrice(saleTicket.remaining_amount),
                'Tipos de pago': paymentTypes,
                'Deudor': saleTicket.sale_debtor,
                'PurcharseType': saleTicket.purchase_type
            });
            openned.value = true;
        })
        .catch(error => {
            console.error(error);
            toast(error.message, {
                "theme": "auto",
                "type": "error",
                "autoClose": 10000,
                "dangerouslyHTMLString": true
            })
        })
        .finally(() => {
            loadingSearch.value = false;
        });

};

const printTicketAbonado = (item, isActive) => {
loadingPrint.value = true;
    if(item.Deudor){
        axios.get(route('events.subscribers.installment.receipt', { id: item.Folio }))
            .then(response => {
                const pdfContent = atob(response.data.pdf);
                const pdfBlob = new Blob([new Uint8Array([...pdfContent].map(char => char.charCodeAt(0)))], { type: 'application/pdf' });
                const pdfUrl = window.URL.createObjectURL(pdfBlob);
                printInKioskMode(pdfUrl);
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                loadingPrint.value = false;
                isActive.value = false;
            })

    }else{
        axios.get(route('events.printSubscriber', { id: item.Folio , purchase_type:item.PurcharseType }))
            .then(response => {
                const pdfContent = atob(response.data.pdf);
                const pdfBlob = new Blob([new Uint8Array([...pdfContent].map(char => char.charCodeAt(0)))], { type: 'application/pdf' });

                if (response.data.subscriber) {
                    const pdfUrl = window.URL.createObjectURL(pdfBlob);
                    printInKioskMode(pdfUrl);
                }else{
                    const pdfFile = new File([pdfBlob], 'arch.pdf', { type: 'application/pdf' });
                    const formData = new FormData();
                    formData.append('pdf', pdfFile);
                    axios.post(route('print'), formData)
                        .then(response => {
                            console.log(response.data);
                        })
                        .catch(error => {
                            console.error(error);
                            const pdfUrl = window.URL.createObjectURL(pdfBlob);
                            printInKioskMode(pdfUrl);
                        });
                }
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                loadingPrint.value = false;
                isActive.value = false;
            })
    };
}

function printInKioskMode(url, close = true) {
    const ventana = window.open(url, '_blank', 'fullscreen=yes,kiosk=yes');
    ventana.onload = () => {
        ventana.print();
        if (close){
            setTimeout(() => {
                //ventana.close();
            }, 4000);
        }
    };
}
</script>

<template>
    <Head title="Taquillas" />
    <AppNav/>
    <div class="tw-pt-5">
        <GuestNav/>
    </div>
    <main class="tw-relative tw-overflow-hidden">
        <div class="tw-absolute -tw-right-40 lg:-tw-right-96 tw-bottom-40 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[600px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
        </div>
        <section class="tw-max-w-7xl tw-min-h-screen tw-pt-28 lg:tw-pt-10 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">
            <div class="tw-w-full">
                <div class="tw-space-y-5 lg:tw-space-y-8 tw-max-w-2xl">
                    <Link :href="route('welcome')">
                        <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                            <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Regresar a eventos
                        </div>
                    </Link >

                    <h2 class="tw-text-3xl tw-font-bold lg:tw-text-6xl tw-font-bebas">Taquillas administrables</h2>
                    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen>
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-h-[70px] !tw-rounded-2xl !tw-px-12 !tw-bg-gradient-to-r tw-from-primary tw-to-secondary !tw-text-white">Administrar ventas</v-btn>
                        </template>
                        <v-card>
                            <v-toolbar class="!tw-bg-gradient-to-r !tw-from-slate-950 !tw-via-purple-950 !tw-to-slate-950">
                                <v-btn class="!tw-text-white" icon="mdi-close" @click="dialog = false"></v-btn>
                                <v-toolbar-title>
                                    <div class="tw-font-bold tw-text-white">Administración de ventas</div>
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                <v-btn color="white" text="Aceptar" variant="tonal" @click="dialog = false"></v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <div class="tw-w-full tw-max-w-[90%] tw-mx-auto tw-mt-10">
                                <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col tw-w-full">
                                    <v-text-field
                                        color="purple"
                                        label="Folio o ID del ticket"
                                        placeholder="123"
                                        hint="Ingresa el folio o id del ticket"
                                        v-model="sale_ticket_id"
                                        variant="outlined"
                                        class="!tw-w-full"
                                    ></v-text-field>
                                    <PrimaryButton @click="searchSaleTicket" heightbtn="!tw-h-[70px]" paddingbtn="!tw-px-12" :loading="loadingSearch">
                                        <span>Buscar venta asociada</span>
                                    </PrimaryButton>
                                </div>

                                <div v-if="openned" class="mb-10 cash-register-history">
                                    <v-data-table :items="items" :headers="headers" :header-props="headerProps">
                                        <template v-slot:item.Estatus="{ item }">
                                            <span class="tw-py-1 tw-px-4 tw-rounded-full"
                                            :class="{
                                                '!tw-text-green-600 tw-bg-green-100': item.Estatus === 'pagado',
                                                '!tw-text-red-600 tw-bg-red-100': item.Estatus === 'cancelado',
                                                '!tw-text-orange-600 tw-bg-orange-100': item.Estatus === 'parcialmente cancelado',
                                                '!tw-text-yellow-600 tw-bg-yellow-100': item.Estatus === 'pendiente'
                                            }">
                                                {{ formatFirstLetterUppercase(item.Estatus) }}
                                            </span>
                                        </template>
                                        <template v-slot:item.Promotion="{ item }">
                                            {{ item.Promotion ? `${item.Promotion.name} (${ formatFirstLetterUppercase(item.Promotion.promotion_type.name) })` : '' }}
                                        </template>
                                        <template v-slot:item.Acciones="{ item }">
                                            <div class="tw-flex tw-items-center tw-gap-3 tw-justify-between !tw-my-3">
                                                    <v-dialog max-width="600">
                                                        <template v-slot:activator="{ props: activatorProps }">
                                                            <v-btn @click="updateSaleTicketsSelected(item)" v-bind="activatorProps" density="default" icon="mdi-printer-settings" class="!tw-text-purple-600 !tw-bg-purple-300"></v-btn>
                                                        </template>
                                                        <template v-slot:default="{ isActive }">
                                                            <v-card title="¿Estas seguro de reimprimir el ticket?">
                                                            <v-card-text>
                                                                <p class="tw-opacity-50 tw-mt-3 tw-text-center">Preciona 'Imprimir' para ejecutar la acción.</p>
                                                                <div class="tw-flex tw-items-center tw-justify-center tw-gap-3 mt-5">
                                                                    <p v-for="code in saleTicketsSelected" :key="code" class="tw-py-2 tw-px-7 tw-bg-purple-200 tw-text-purple-700 tw-rounded-md tw-text-xl">{{ code }}</p>
                                                                </div>
                                                            </v-card-text>

                                                            <v-card-actions class="tw-mb-4 tw-mr-4">
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                <v-btn :loading="loadingPrint" @click="printTicketAbonado(item, isActive)" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                                                    Imprimir
                                                                </v-btn>
                                                            </v-card-actions>
                                                            </v-card>
                                                        </template>
                                                </v-dialog>
                                                <v-dialog max-width="800">
                                                        <template v-slot:activator="{ props: activatorProps }">
                                                            <v-btn @click="updateSaleTicketsSelected(item)" v-bind="activatorProps" density="default" icon="mdi-delete" class="!tw-text-red-600 !tw-bg-red-200"></v-btn>
                                                        </template>
                                                        <template v-slot:default="{ isActive }">
                                                            <v-card title="¿Estas seguro de cancelar el ticket?">
                                                            <v-card-text>
                                                                <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                                                    <p class="tw-text-xs py-1 px-5 tw-bg-red-100 tw-text-red-500 tw-rounded-full">Selecciona el tipo de pago y orden en el que descontara la venta</p>
                                                                    <div v-if="paymentTypesSelected.length > 0" class="tw-mt-3 tw-text-purple-600 tw-font-bold tw-flex tw-items-center tw-justify-center tw-gap-3">
                                                                        <div v-for="(type, index) in paymentTypesSelected" :key="index">
                                                                        <p class="tw-py-1 tw-px-3 tw-bg-purple-100 tw-rounded-md"> {{ index + 1 }} - {{ type }} </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tw-flex tw-items-center tw-gap-2">
                                                                        <v-checkbox
                                                                        class="!tw-flex !tw-items-center"
                                                                        v-for="(type, index) in paymentTypes" :key="index"
                                                                        v-model="paymentTypesSelected"
                                                                        :label="type"
                                                                        :value="type"
                                                                        color="purple"
                                                                        ></v-checkbox>
                                                                    </div>
                                                                </div>
                                                                <div class="!w-full tw-mt-0">
                                                                    <v-tabs
                                                                        v-model="tabs"
                                                                        color="purple"
                                                                        grow
                                                                        >
                                                                        <v-tab :value="1">
                                                                            <span>Cancelación total</span>
                                                                        </v-tab>

                                                                        <v-tab :value="2">
                                                                            <span>Cancelación parcial</span>
                                                                        </v-tab>

                                                                    </v-tabs>

                                                                    <v-tabs-window v-model="tabs">
                                                                    <v-tabs-window-item :value="1">
                                                                        <v-card>
                                                                        <v-card-text class="!tw-mt-5">
                                                                            <div class="tw-flex tw-items-center tw-justify-center tw-gap-3">
                                                                                <p v-for="code in saleTicketsSelected" :key="code" class="tw-py-2 tw-px-7 tw-bg-purple-200 tw-text-purple-700 tw-rounded-md tw-text-xl">{{ code }}</p>
                                                                            </div>
                                                                        </v-card-text>
                                                                        </v-card>
                                                                    </v-tabs-window-item>
                                                                    <v-tabs-window-item :value="2">
                                                                        <v-card>
                                                                        <v-card-text>
                                                                            <v-select
                                                                                append-inner-icon="mdi-qrcode"
                                                                                :items="saleTicketsSelected"
                                                                                v-model="cancelSeatCodes"
                                                                                multiple
                                                                                label="Selecciona los asientos a cancelar"
                                                                                color="purple"
                                                                                clearable
                                                                                class="tw-w-full"
                                                                                hint="Opcion multiple"
                                                                                persistent-hint=""
                                                                            ></v-select>
                                                                        </v-card-text>
                                                                        </v-card>
                                                                    </v-tabs-window-item>
                                                                    </v-tabs-window>
                                                                </div>
                                                            </v-card-text>

                                                            <v-card-actions class="tw-mb-4 tw-mr-4">
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                <v-btn :loading="loadingCancel" @click="cancelTicket(item, isActive)" rounded="large" variant="elevated" class="text-none !tw-bg-red-600 !tw-text-white">
                                                                    Ejecutar Cancelaciòn
                                                                </v-btn>
                                                            </v-card-actions>
                                                            </v-card>
                                                        </template>
                                                </v-dialog>
                                            </div>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </v-card>
                    </v-dialog>
                    <div class="tw-flex tw-flex-col tw-gap-3">
                         <div class="tw-flex tw-items-center tw-gap-x-5">
                            <div class="tw-flex tw-items-center tw-gap-x-2">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>El nido del halcón
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-x-2">
                                <span class="material-symbols-outlined tw-text-xl">calendar_today</span>{{formattedDate}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Title -->
                <ErrorSession />

                <div v-if="ticket_offices" class="tw-w-full tw-flex-col lg:tw-flex-row tw-flex tw-items-start tw-justify-between tw-gap-6 tw-mt-7">
                    <!-- Grid -->
                    <div class="tw-grid sm:tw-grid-cols-2 tw-gap-6 tw-w-full lg:tw-w-2/3">
                        <!-- Card -->
                        <div :href="route('ticket-offices.show', ticketOffice.id)" v-for="ticketOffice in ticket_offices" :key="ticketOffice.id" class="tw-group tw-flex tw-flex-col">
                            <div class="tw-relative tw-pt-[50%] sm:tw-pt-[70%] tw-rounded-xl tw-overflow-hidden">
                                <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-group-hover:tw-scale-105 tw-group-focus:tw-scale-105 tw-transition-transform tw-duration-500 tw-ease-in-out tw-rounded-xl" src="https://images.pexels.com/photos/4921264/pexels-photo-4921264.jpeg" alt="Blog Image">
                                 <div class="tw-absolute tw-top-0 tw-w-full tw-h-full tw-z-50 tw-bg-black/60 tw-backdrop-blur-sm tw-flex tw-items-center tw-justify-center tw-text-white">
                                    <div class="tw-font-bold">
                                        Taquilla Número {{ ticketOffice.id }}
                                    </div>
                                </div>
                            </div>

                            <div class="tw-mt-7">
                                <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-group-hover:tw-text-gray-600">
                                    {{ formatFirstLetterUppercase(ticketOffice.name) }}
                                </h3>
                                <p class="tw-mt-3 tw-text-gray-800">
                                    {{ formatFirstLetterUppercase(ticketOffice.description) }}
                                </p>
                                <div class="tw-flex tw-items-center tw-justify-between tw-mt-5">
                                    <Link :href="route('ticket-offices.show', ticketOffice.id)">
                                        <PrimaryButton heightbtn="!tw-h-[70px]" paddingbtn="!tw-px-12">
                                            <span>Administrar taquilla</span>
                                        </PrimaryButton>
                                    </Link>
                                    <Link :href="route('ticket-offices.check')">
                                        <SecondaryButton heightbtn="!tw-h-[70px]" paddingbtn="!tw-px-12">
                                            <span>Verificar</span>
                                        </SecondaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <Footer />

    </main>
</template>

<style scoped>

</style>
