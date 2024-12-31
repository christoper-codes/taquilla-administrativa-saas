<script setup>
import { Head, Link, router,  useForm as useFormInertia } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { cashRegisterSchema } from '@/validation/cash.-registers/cash-regiser-schema';
import { useForm, useField } from 'vee-validate'
import useTicketOfficeState from '@/composables/TicketOfficeState';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';
import axios from 'axios';
import { toast } from 'vue3-toastify'

const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();
const { handleSubmit } = useForm({validationSchema : cashRegisterSchema});
const cashRegisterFields = {
    'cash_register_type_id': useField('cash_register_type_id'),
    'opening_balance': useField('opening_balance'),
}
const loading = ref(false);
const { cashRegisterPresent } = useTicketOfficeState();

const selectedEvents = ref([]);
const cashRegisterData = useFormInertia({
    ticket_office_id: '',
    cash_register_type_id: '',
    seller_user_opening_id: '',
    opening_balance: '',
})

const cashRegisterSubmit = handleSubmit((values, isActive) => {
    loading.value = true;
    cashRegisterData.ticket_office_id = props.ticket_office.id;
    cashRegisterData.seller_user_opening_id = props.auth_user.id;
    cashRegisterData.cash_register_type_id = values.cash_register_type_id.id;
    cashRegisterData.opening_balance = values.opening_balance;

    cashRegisterData.post(route('cash-registers.store'), {
        onSuccess: (response) => {
            cashRegisterFields.cash_register_type_id.value.value = '';
            cashRegisterFields.opening_balance.value.value = '';
            localStorage.setItem('cashRegisterData', JSON.stringify(response.props.active_cash_register));
            cashRegisterPresent.value = response.props.active_cash_register.cash_register_type_id;
        },
        onFinish: () => {
            loading.value = false;
            isActive.evt.value = false;
        }
    });
});

const props = defineProps({
    'ticket_office': {
        type: Object,
        required: true,
    },
    'sale_tickets_cancellation_code': {
        type: Object,
        required: true
    },
    'events': {
        type: Array,
        required: true,
    },
    'auth_user': {
        type: Object,
        required: true,
    },
    'active_cash_register': {
        type: Object,
        required: false,
    },
    'cash_register_general_history': {
        type: Object,
        required: false,
    },
})

onMounted(() => {
    selectedEvents.value = props.events.map((event) => event);
    if(props.active_cash_register) {
        localStorage.setItem('cashRegisterData', JSON.stringify(props.active_cash_register));
        cashRegisterPresent.value = props.active_cash_register.cash_register_type_id;
    }
    cancelPassword.value = props.sale_tickets_cancellation_code.cancellation_code;
    console.log(cancelPassword.value);
})

const eventProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const ticketOfficeProps = (item) => {
  return {
    id: item.id,
    title: item.name,
    subtitle: item.description,
  };
};

const dialog = ref(false);
const notifications = ref(false);
const sound = ref(true);
const widgets = ref(false);

/*
* Data table items
*/
const tabs = ref(null);
const saleTicketsSelected = ref([]);
const paymentTypes = ref([]);
const paymentTypesSelected = ref([]);
const items = ref([]);
const loadingPrint = ref(false);
const loadingCancel = ref(false);
const cancelSeatCodes = ref([]);
const cancelPassword = ref('');
const cencellationPasswordEntered = ref('');

const headers = [
    { title: 'Folio', key: 'Folio' },
    { title: 'Estatus', key: 'Estatus' },
    { title: 'Fecha de venta', key: 'Fecha de venta' },
    { title: 'Fue transferido', key: 'Fue transferido' },
    { title: 'Asientos', key: 'Asientos' },
    { title: 'Monto recibido', key: 'Monto recibido' },
    { title: 'Monto total', key: 'Monto total' },
    { title: 'Monto de vuelto', key: 'Monto de vuelto' },
    { title: 'Tipos de pago', key: 'Tipos de pago' },
    {title: 'Acciones', key: 'Acciones', sortable:false}
];
const headerProps = {
    class: '!tw-font-bold'
};
if (props.cash_register_general_history && props.cash_register_general_history.cash_register) {
    props.cash_register_general_history.sale_tickets.forEach((saleTicket) => {
        const paymentTypes = saleTicket.global_payment_types.map(paymentType => {
            return `${paymentType.name}: ${formatPrice(paymentType.pivot.amount)}`;
        }).join(', ');
        const seatCatalogues  = saleTicket.event_seat_catalogues.map(seatCatalogue => {
            return `${seatCatalogue.seat_catalogue.code}`
        }).join(', ');

        items.value.push({
            'Folio': saleTicket.id,
            'Estatus': saleTicket.sale_ticket_status.name,
            'Fecha de venta': dateFormat(saleTicket.created_at),
            'Fue transferido': saleTicket.is_transfer ? 'Si' : 'No',
            'Asientos': seatCatalogues,
            'Monto recibido': formatPrice(saleTicket.amount_received),
            'Monto total': formatPrice(saleTicket.total_amount),
            'Monto de vuelto': formatPrice(saleTicket.total_returned),
            'Tipos de pago': paymentTypes
        });
    });
}

const printTicket = (item, isActive) => {

    loadingPrint.value = true;
    const data = {
        'sale_ticket_id': item.Folio,
    };
    axios.post(route('events.print-sale-ticket'), data)
        .then(response => {
            console.log(response)
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
};
function printInKioskMode(url) {
    const ventana = window.open(url, '_blank', 'fullscreen=yes,kiosk=yes');
    ventana.onload = () => {
        ventana.print();
        setTimeout(() => {
            ventana.close();
        }, 4000);

    };
}
const cancelTicket = (item) => {

    if(cencellationPasswordEntered.value != cancelPassword.value) {
        toast('El password no coincide para ejecutar la cancelacion', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return
    }
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
        }
    })

};

const updateSaleTicketsSelected = (item) => {
    cencellationPasswordEntered.value = '';
    paymentTypesSelected.value = [];
    cancelSeatCodes.value = [];    
    saleTicketsSelected.value = item.Asientos.split(',');
    paymentTypes.value = item['Tipos de pago'].split(',');    
}

const pdf = () => {
    axios.post(route('pdf-test'), {}, { responseType: 'blob' })
        .then(response => {
            const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
            const pdfUrl = window.URL.createObjectURL(pdfBlob);
            printInKioskMode(pdfUrl);
        })
        .catch(error => {
            console.error('Error:', error);
        });
};



</script>

<template>
    <Head title="Taquillas" />
    <GuestLayout />
    <NavigationDrawer />

    <main class="tw-relative tw-overflow-hidden">
        <section class="tw-max-w-7xl tw-pt-20 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">
            <SuccessSession />
                <div class="tw-w-full tw-flex tw-gap-10 lg:tw-gap-20 tw-flex-col lg:tw-flex-row">
                    <div class="tw-group tw-relative tw-flex tw-flex-col tw-w-full lg:tw-w-[40%] tw-min-h-60 tw-bg-[url('https://i.pinimg.com/564x/4a/04/11/4a04110cc00a352c8c8bc63c4731db1c.jpg')] tw-bg-center tw-bg-cover tw-overflow-hidden tw-rounded-xl tw-hover:shadow-lg tw-focus:outline-none tw-focus:shadow-lg tw-transition" href="#">
                        <div class="tw-uppercase tw-absolute tw-bottom-0 tw-w-[100%] tw-bg-black/40 tw-px-3 tw-py-7 tw-backdrop-blur-md tw-backdrop-brightness-150 tw-text-white tw-font-bold tw-text-center">
                            {{ ticket_office.name }}
                        </div>
                    </div>

                    <div class="tw-space-y-5 lg:tw-space-y-8 tw-w-full lg:tw-w-[60%]">
                        <Link :href="route('welcome')">
                            <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Regresar al inicio
                            </div>
                        </Link >
                        <!-- <v-btn @click="pdf">Pdf</v-btn> -->

                        <h2 class="lg:tw-text-4xl tw-text-3xl tw-font-bold">{{ ticket_office.name }}. Administracion para el club halcones de xalapa</h2>
                        <div class="tw-py-2 tw-px-5 tw-border-l-4 tw-border-l-purple-500 tw-w-full tw-bg-purple-200 tw-text-purple-600">
                            {{ ticket_office.description }}
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-center tw-gap-5">
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-base tw-shadow-xl tw-text-gray-800 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-2xl">location_on</span>Halcones de Xalapa
                            </div>
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-base tw-shadow-xl tw-text-gray-800 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-2xl">check</span>{{ ticket_office.is_active ? 'Activo' : 'Inactivo' }}
                            </div>
                        </div>
                    </div>

                </div>
        </section>

        <div class="tw-w-full tw-bg-slate-950 tw-relative tw-overflow-hidden tw-mt-16 tw-mb-36">
            <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-py-16 tw-gap-9 tw-px-4 lg:tw-px-0">
                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Abrir cajas registradoras</h3>
                    <v-dialog max-width="700">
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-1/2" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">store</span>Abrir caja registradora</v-btn>
                        </template>
                        <template v-slot:default="{ isActive }">
                            <v-card title="¿Estas seguro de abrir una caja registradora?">
                            <v-form>
                                <v-card-text>
                                <div>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Partidos activos"
                                        hint="Partidos activos"
                                        multiple
                                        v-model="selectedEvents"
                                        :item-props="eventProps"
                                        :items="events"
                                    ></v-select>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Seleciona la caja"
                                        hint="Selecciona la caja"
                                        v-model= "cashRegisterFields.cash_register_type_id.value.value"
                                        :item-props="ticketOfficeProps"
                                        :items="ticket_office.cash_register_types_no_actives"
                                        :error-messages="cashRegisterFields.cash_register_type_id.errorMessage.value"
                                    ></v-select>
                                    <v-text-field
                                        color="primary"
                                        label="Saldo de apertura"
                                        placeholder="$1000.00"
                                        hint="Ingresa el saldo de apertura"
                                        v-model="cashRegisterFields.opening_balance.value.value"
                                        :error-messages="cashRegisterFields.opening_balance.errorMessage.value"
                                    ></v-text-field>
                                </div>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                               <div class="tw-flex tw-items-center tw-gap-3 tw-mb-3">
                                    <v-btn variant="tonal" color="red" class="text-none !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Cancelar</v-btn>
                                    <v-btn :loading="loading" variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl" @click="cashRegisterSubmit(isActive)">Abrir caja</v-btn>
                               </div>
                            </v-card-actions>
                            </v-form>
                            </v-card>
                        </template>
                    </v-dialog>
                </div>

                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20 tw-relative">
                    <div v-if="active_cash_register" class="tw-absolute -tw-top-1 -tw-right-1">
                        <span class="tw-flex tw-h-6 tw-w-6">
                        <span class="tw-animate-ping tw-absolute tw-inline-flex tw-h-full tw-w-full tw-rounded-full tw-bg-green-400 tw-opacity-75"></span>
                        <span class="tw-inline-flex tw-rounded-full tw-h-6 tw-w-6 tw-bg-green-500"></span>
                        </span>
                    </div>
                    <h3 class="text-white tw-text-2xl">Resumen de caja <span v-if="active_cash_register">{{ active_cash_register.cash_register_type_id }}</span> </h3>
                    <div class="text-center pa-4">
                        <v-dialog
                            v-model="dialog"
                            transition="dialog-bottom-transition"
                            fullscreen
                        >
                            <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-20 !tw-w-[335px]" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">credit_score</span>Cajas aperturadas</v-btn>
                            </template>

                            <v-card>
                            <v-toolbar class="!tw-bg-gradient-to-r !tw-from-slate-950 !tw-via-purple-950 !tw-to-slate-950">
                                <v-btn
                                class="!tw-text-white"
                                icon="mdi-close"
                                @click="dialog = false"
                                ></v-btn>

                                <v-toolbar-title>
                                    <div class="tw-font-bold tw-text-white">Resumen de caja</div>
                                </v-toolbar-title>

                                <v-spacer></v-spacer>

                                <v-toolbar-items>
                                <v-btn
                                    color="white"
                                    text="Aceptar"
                                    variant="tonal"
                                    @click="dialog = false"
                                ></v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <div v-if="active_cash_register" class="tw-w-full tw-max-w-[90%] tw-mx-auto tw-mt-10">
                                <div class="tw-text-4xl tw-font-bold"> <span class="tw-text-purple-600">Apertura:</span> {{ dateFormat(active_cash_register.created_at) }}</div>
                                <div class="tw-grid tw-grid-cols-4 tw-gap-10  tw-mt-10">
                                    <div class="tw-p-5 tw-rounded-xl tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-gray-100 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Usuario vendedor</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ auth_user.first_name }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-gray-100 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Caja registradora</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ active_cash_register.cash_register_type_id }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-gray-100 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Saldo de apertura</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ formatPrice(active_cash_register.opening_balance) }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-green-100 tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-green-200 tw-text-green-600 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Saldo actual</div>
                                        <div class="tw-text-4xl tw-font-bold tw-text-green-600">{{ formatPrice(active_cash_register.current_balance) }}</div>
                                    </div>
                                    <div v-for="(amount, type) in cash_register_general_history.type_payments" :key="type">
                                        <div class="tw-p-5 tw-rounded-xl tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                            <div class="tw-bg-gray-100 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Ventas con <span class="tw-text-purple-600 tw-font-bold">{{ type }}</span> </div>
                                            <div class="tw-text-4xl tw-font-bold">{{ formatPrice(amount.amount) }}</div>
                                        </div>
                                    </div>
                                </div>
                                              
                                <div class="my-10 cash-register-history">
                                    <v-data-table :items="items" :headers="headers" :header-props="headerProps">
                                        <template v-slot:item.Estatus="{ item }">
                                            <span class="tw-py-1 tw-px-4 tw-rounded-full" :class="item.Estatus === 'pagado' ? '!tw-text-green-600 tw-bg-green-100' : '!tw-text-red-600 tw-bg-red-100'">
                                                {{ item.Estatus }}
                                            </span>
                                        </template>
                                        <template v-slot:item.Acciones="{ item }">
                                            <div class="tw-flex tw-items-center tw-gap-3 tw-justify-between !tw-my-3">
                                                    <v-dialog max-width="600">
                                                        <template v-slot:activator="{ props: activatorProps }">
                                                            <v-btn @click="updateSaleTicketsSelected(item)" v-bind="activatorProps" density="default" icon="mdi-printer" class="!tw-text-blue-600 !tw-bg-blue-200"></v-btn>
                                                        </template>
                                                        <template v-slot:default="{ isActive }">
                                                            <v-card title="¿Estas seguro de reimprimir el ticket?">
                                                            <v-card-text>
                                                                <p class="tw-opacity-50 tw-mt-3 tw-text-center">Preciona 'Imprimir ticket' para ejecutar la acción.</p>
                                                                <div class="tw-flex tw-items-center tw-justify-center tw-gap-3 mt-5">
                                                                    <p v-for="code in saleTicketsSelected" :key="code" class="tw-py-2 tw-px-7 tw-bg-purple-200 tw-text-purple-700 tw-rounded-md tw-text-xl">{{ code }}</p>
                                                                </div>
                                                            </v-card-text>

                                                            <v-card-actions class="tw-mb-4 tw-mr-4">
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                <v-btn :loading="loadingPrint" @click="printTicket(item, isActive)" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                                                    Imprimir ticket
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
                                                                    <p class="tw-inline tw-mt-3 tw-text-center tw-text-xs py-1 px-5 tw-bg-red-100 tw-text-red-500 tw-rounded-full">Ingresa el codigo de cancelacion y preciona 'Ejecutar Cancelaciòn' para confirmar.</p>
                                                                    <v-otp-input v-model="cencellationPasswordEntered"></v-otp-input>
                                                                </div>
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
                            <div v-else class="tw-flex tw-items-center tw-justify-center tw-mt-20 tw-flex-col tw-gap-10">
                                <div class="tw-font-bold tw-text-center tw-bg-gray-200 tw-rounded-full tw-inline-flex tw-px-7 tw-py-3 tw-text-gray-600">
                                    No hay cajas abiertas para este usuario en esta taquilla
                                </div>
                                <img class="tw-w-96" src="../../../../../public/img/seats-no-selected-img.svg" alt="">
                            </div>
                            </v-card>
                        </v-dialog>
                        </div>

                </div>

                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Vender entradas</h3>
                    <Link :href="route('events.index')" class="tw-h-full tw-w-full">
                        <v-btn variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-20 !tw-w-[335px]" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">credit_score</span>Vender entradas</v-btn>
                    </Link>
                </div>
            </div>

            <div class="tw-absolute tw-left-1/2 tw-top-[80%] tw-h-[1280px] tw-w-[800px] tw--translate-x-1/2 tw-rounded-full tw-bg-gradient-to-t tw-blur-[250px] tw-from-tw-primary-800 tw-to-tw-primary-600">
            </div>

        </div>

        <Footer />

    </main>
</template>

<style scoped>
.v-dialog > .v-overlay__content > .v-card, .v-dialog > .v-overlay__content > .v-sheet, .v-dialog > .v-overlay__content > form > .v-card, .v-dialog > .v-overlay__content > form > .v-sheet {
    border-radius: 0px !important;
}

</style>
