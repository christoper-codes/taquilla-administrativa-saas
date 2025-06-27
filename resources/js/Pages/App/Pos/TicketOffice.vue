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
import useStringFormat from '@/composables/stringFormat'
import axios from 'axios';
import { toast } from 'vue3-toastify'
import InstallmentPayment from '@/Pages/App/Pos/InstallmentPayment.vue'
import AppNav from '@/Components/navs/AppNav.vue';
import GuestNav from '@/Components/navs/GuestNav.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';


const { formatFirstLetterUppercase } = useStringFormat();
const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();
const { handleSubmit } = useForm({validationSchema : cashRegisterSchema});
const cashRegisterFields = {
    'cash_register_type_id': useField('cash_register_type_id'),
    'opening_balance': useField('opening_balance'),
}
const loading = ref(false);
const { cashRegisterPresent, cashRegisterDataId, sellerUserId, ticketOfficeId } = useTicketOfficeState();

const selectedEvents = ref([]);
const cashRegisterData = useFormInertia({
    ticket_office_id: '',
    cash_register_type_id: '',
    seller_user_opening_id: '',
    opening_balance: '',
})

const cashRegisterSubmit = handleSubmit((values, isActive) => {
    loading.value = true;
    const data = {
        ticket_office_id: props.ticket_office.id,
        seller_user_opening_id: props.auth_user.id,
        cash_register_type_id: values.cash_register_type_id.id,
        opening_balance: values.opening_balance,
    };

    axios.post(route('cash-registers.store'), data)
        .then(response => {
            toast(response.data.message, {
                theme: 'auto',
                type: 'success',
                dangerouslyHTMLString: true
            });
            router.visit(route('ticket-offices.show', props.ticket_office.id));
        })
        .catch(error => {
            toast(error.response.data.message, {
                theme: 'auto',
                type: 'error',
                autoClose: 10000,
                dangerouslyHTMLString: true
            });
        })
        .finally(() => {
            loading.value = false;
            isActive.evt.value = false;
        });
});

const closeCashRegister = (isActive) => {
    loading.value = true;
    const data = {
        'cash_register_id': props.active_cash_register.id,
        'seller_user_closing_id': props.auth_user.id,
        'ticket_office_id': props.ticket_office.id,
    };

    axios.post(route('cash-registers.close'), data)
    .then(response => {

        toast(response.data.message, {
            "theme": "auto",
            "type": "success",
            "dangerouslyHTMLString": true
        })


        if(response.data.pdf) {
            const pdfContent = atob(response.data.pdf);
            const pdfBlob = new Blob([new Uint8Array([...pdfContent].map(char => char.charCodeAt(0)))], { type: 'application/pdf' });
            const pdfUrl = window.URL.createObjectURL(pdfBlob);
            printInKioskMode(pdfUrl, false);
        }

        localStorage.removeItem('cashRegisterData');
        cashRegisterPresent.value = false;
        cashRegisterDataId.value = 1;
        sellerUserId.value = 1;
        ticketOfficeId.value = 1;

        setTimeout(() => {
            router.visit('/taquillas');
        }, 3000);

    })
    .catch(error => {
        toast(error.response.data.message, {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
    })
    .finally(() => {
        loading.value = false;
        isActive.value = false;
    });
}

const closeTicketOfficeCashRegisters = (isActive) => {
    loadingCancel.value = true;
    if(cencellationPasswordEntered.value != cancelPassword.value) {
        toast('El password no coincide para ejecutar la cancelacion', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        loadingCancel.value = false;
        return
    }

    const data = {
        'ticket_office_id': props.ticket_office.id,
        'seller_user_closing_id': props.auth_user.id,
    };

    axios.post(route('cash-registers.close.all'), data)
        .then(response => {
            toast(response.data.message, {
                "theme": "auto",
                "type": "success",
                "dangerouslyHTMLString": true
            })

            localStorage.removeItem('cashRegisterData');
            cashRegisterPresent.value = false;
            cashRegisterDataId.value = 1;
            sellerUserId.value = 1;
            ticketOfficeId.value = 1;

            cencellationPasswordEntered.value = '';
        })
        .catch(error => {
            toast(error.response.data.message, {
                "theme": "auto",
                "type": "error",
                "autoClose": 10000,
                "dangerouslyHTMLString": true
            })
        })
        .finally(() => {
            loadingCancel.value = false;
            isActive.value = false;
        });
}

const getCashRegisterSummary = () => {
    loading.value = true;
    const data = {
        'cash_register_id': props.active_cash_register.id,
        'seller_user_closing_id': props.auth_user.id,
        'ticket_office_id': props.ticket_office.id,
    };

    axios.post(route('cash-registers.summary'), data)
    .then(response => {

        toast(response.data.message, {
            "theme": "auto",
            "type": "success",
            "dangerouslyHTMLString": true
        })

        if(response.data.pdf) {
            const pdfContent = atob(response.data.pdf);
            const pdfBlob = new Blob([new Uint8Array([...pdfContent].map(char => char.charCodeAt(0)))], { type: 'application/pdf' });
            const pdfUrl = window.URL.createObjectURL(pdfBlob);
            printInKioskMode(pdfUrl, false);
        }
    })
    .catch(error => {
        toast(error.response.data.message, {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
    })
    .finally(() => {
        loading.value = false;
    });
}

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
    'global_payment_types': {
        type: Array,
        required: true,
    },
    'global_card_payment_types': {
        type: Array,
        required: true,
    },
})

onMounted(() => {
    selectedEvents.value = props.events.map((event) => event);
    if(props.active_cash_register) {
        localStorage.setItem('cashRegisterData', JSON.stringify(props.active_cash_register));
        cashRegisterPresent.value = props.active_cash_register.cash_register_type_id;
    }
    cancelPassword.value = props.sale_tickets_cancellation_code.cancellation_code;
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
const dialogPayInstallment = ref(false);
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
    { title: 'Promoción', key: 'Promotion' },
    { title: 'Fecha de venta', key: 'Fecha de venta' },
    { title: 'Fue transferido', key: 'Fue transferido' },
    { title: 'Asientos', key: 'Asientos' },
    { title: 'Monto Pagado', key: 'Monto Pagado' },
    { title: 'Monto total', key: 'Monto total' },
    { title: 'Adeudo', key: 'Monto restante' },
    { title: 'Pagos realizados', key: 'Tipos de pago' },
    {title: 'Acciones', key: 'Acciones', sortable:false}
];
const headerProps = {
    class: '!tw-font-bold'
};
if (props.cash_register_general_history && props.cash_register_general_history.cash_register) {
    props.cash_register_general_history.sale_tickets.forEach((saleTicket) => {

        const paymentTypes = saleTicket.global_payment_types.map(paymentType => {
            return `${formatFirstLetterUppercase(paymentType.name)}: ${paymentType.pivot.amount}`;
        }).join(', ');
        const seatCatalogues  = saleTicket.event_seat_catalogues.map(seatCatalogue => {
            return `${seatCatalogue.seat_catalogue.code}`
        }).join(', ');

        items.value.push({
            'Folio': saleTicket.id,
            'Estatus': saleTicket.sale_ticket_status.name,
            'Promotion': saleTicket.promotion,
            'Fecha de venta': dateFormat(saleTicket.created_at),
            'Fue transferido': saleTicket.is_transfer ? 'Si' : 'No',
            'Asientos': seatCatalogues,
            'Monto total': formatPrice(saleTicket.total_amount),
            'Monto Pagado': formatPrice((Number(Number(saleTicket.amount_received)-Number(saleTicket.total_returned)))),
            'Monto restante': formatPrice(saleTicket.remaining_amount),
            'Tipos de pago': paymentTypes,
            'Deudor': saleTicket.sale_debtor,
            'PurcharseType': saleTicket.purchase_type
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
                    //const pdfUrl = window.URL.createObjectURL(pdfBlob);
                    //printInKioskMode(pdfUrl);
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

const cancelTicket = (item, isActive) => {

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
            isActive.value = false;
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
    <AppNav/>
    <SuccessSession />
    <main class="tw-relative tw-overflow-hidden tw-w-full">
        <div class="tw-text-white tw-bg-cover tw-relative tw-min-h-screen tw-aspect-3/4 tw-object-cover tw-bg-center tw-w-full tw-p-4 lg:tw-p-7 tw-shadow-xl tw-overflow-hidden tw-transition-all tw-duration-500" :style="`background-image: url('https://images.pexels.com/photos/4921264/pexels-photo-4921264.jpeg')`">
            <GuestNav/>
           <div class="tw-z-10 tw-relative tw-max-w-7xl tw-pt-20 tw-mx-auto tw-px-4 lg:tw-px-0 tw-mb-10">
                <div class="tw-flex tw-flex-col tw-gap-3">
                    <Link :href="route('welcome')">
                        <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                            <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Regresar al inicio
                        </div>
                    </Link >
                    <h2 class="tw-text-6xl tw-font-bold tw-font-bebas">{{ ticket_office.name }}</h2>
                    <p>{{ formatFirstLetterUppercase(ticket_office.description) }}</p>
                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-gap-5">
                        <v-dialog max-width="800">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-h-[70px] !tw-rounded-2xl !tw-px-12 !tw-bg-gradient-to-r tw-from-primary tw-to-secondary !tw-text-white">Cerrar cajas por lote</v-btn>
                                </template>
                                <template v-slot:default="{ isActive }">
                                    <v-card>
                                    <v-card-text>
                                        <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-5">
                                            <p class="tw-inline tw-mt-3 tw-text-center tw-text-xs py-3 px-5 tw-bg-blue-100 tw-text-blue-500 tw-rounded-xl tw-border-l-4 tw-border-l-blue-500">Ingresa el codigo de cancelación y preciona 'Cerrar cajas' para confirmar.</p>
                                            <v-otp-input v-model="cencellationPasswordEntered"></v-otp-input>
                                        </div>
                                    </v-card-text>

                                    <v-card-actions class="tw-mb-4 tw-mr-4">
                                        <v-spacer></v-spacer>
                                        <v-btn color="red" rounded="lg" variant="tonal" size="large" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                        <v-btn :loading="loadingCancel" @click="closeTicketOfficeCashRegisters(isActive)" rounded="lg" size="large"  variant="elevated" class="text-none !tw-bg-red-600 !tw-text-white">
                                            Cerrar cajas
                                        </v-btn>
                                    </v-card-actions>
                                    </v-card>
                                </template>
                        </v-dialog>
                        <div v-if="active_cash_register">
                            <v-dialog max-width="500">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-h-[70px] !tw-rounded-2xl !tw-px-12 !tw-bg-gradient-to-r tw-from-red-500 tw-to-pink-500 !tw-text-white">Cerrar caja actual</v-btn>
                                </template>
                                <template v-slot:default="{ isActive }">
                                    <v-card class="!tw-p-5">
                                        <v-card-title class="!tw-text-lg">¿Estás seguro de cerrar la caja registradora #{{ active_cash_register.cash_register_type_id }}? </v-card-title>
                                        <v-card-actions>
                                            <div class="tw-flex tw-items-center tw-gap-3 tw-mt-5">
                                                    <v-btn variant="tonal" color="red" class="text-none !tw-px-7" rounded="lg" size="large" @click="isActive.value = false">Cancelar</v-btn>
                                                    <v-btn @click="closeCashRegister(isActive)" :loading="loading" rounded="lg" size="large" variant="elevated" class="text-none !tw-bg-red-500 !tw-text-white !tw-px-7">Cerrar ahora</v-btn>
                                            </div>
                                        </v-card-actions>
                                    </v-card>
                                </template>
                            </v-dialog>
                        </div>
                    </div>
                </div>
                <div class="tw-mt-16 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-w-full tw-gap-7">
                    <div>
                         <v-dialog max-width="700">
                            <template v-slot:activator="{ props: activatorProps }">
                                <div v-bind="activatorProps" class="hover:tw-scale-105 tw-transition-all tw-duration-500 tw-full tw-h-48 tw-bg-white/20 tw-backdrop-blur-lg tw-flex tw-items-center tw-justify-center tw-cursor-pointer tw-rounded-md">
                                    <span class="tw-font-bold tw-uppercase tw-text-lg">Abrir caja registradora</span>
                                </div>
                            </template>
                            <template v-slot:default="{ isActive }">
                                <v-card title="¿Estas seguro de abrir una caja registradora?">
                                <v-form>
                                    <v-card-text>
                                    <div>
                                        <v-select
                                            color="primary"
                                            clearable
                                            variant="outlined"
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
                                            variant="outlined"
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
                                        <div>
                                            <SecondaryButton heightbtn="!tw-h-[60px]" paddingbtn="!tw-px-10">
                                                <span @click="isActive.value = false">Cancelar</span>
                                            </SecondaryButton>
                                        </div>
                                        <div @click="cashRegisterSubmit(isActive)">
                                            <PrimaryButton heightbtn="!tw-h-[60px]" paddingbtn="!tw-px-10" :loading="loading">
                                                <span>Abrir caja</span>
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </v-card-actions>
                                </v-form>
                                </v-card>
                            </template>
                        </v-dialog>
                    </div>
                    <div>
                         <v-dialog
                                v-model="dialog"
                                transition="dialog-bottom-transition"
                                fullscreen
                            >
                                <template v-slot:activator="{ props: activatorProps }">
                                     <div v-bind="activatorProps" class="hover:tw-scale-105 tw-transition-all tw-duration-500 tw-full tw-h-48 tw-bg-white/20 tw-backdrop-blur-lg tw-flex tw-items-center tw-justify-center tw-cursor-pointer tw-rounded-md">
                                        <span class="tw-font-bold tw-uppercase tw-text-lg">Resumen de caja</span>
                                    </div>
                                    <div v-if="active_cash_register">
                                        <div class="tw-h-3 tw-w-full tw-bg-gradient-to-r tw-from-green-400 tw-to-cyan-400 tw-rounded"></div>
                                    </div>
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

                                    <div class="tw-grid tw-grid-cols-2 tw-gap-10">
                                        <div class="tw-p-5 tw-flex tw-items-start tw-justify-center tw-flex-col tw-gap-3">
                                            <div class="tw-text-4xl tw-font-bold"> <span class="tw-text-purple-600">Apertura:</span> {{ dateFormat(active_cash_register.created_at) }}</div>
                                        </div>
                                        <div class="tw-p-5 tw-flex tw-items-end tw-justify-center tw-flex-col tw-gap-3">
                                            <v-btn @click="getCashRegisterSummary()" :loading="loading" variant="elevated" class="text-none !tw-bg-gradient-to-r !tw-from-purple-500 !tw-to-pink-500 !tw-text-white !tw-px-10 !tw-h-[60px] !tw-rounded-2xl">Imprimir Corte</v-btn>
                                        </div>
                                    </div>

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
                                            <div class="tw-bg-green-200 tw-text-green-600 tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Saldo actual (Efectivo + Tarjeta)</div>
                                            <div class="tw-text-4xl tw-font-bold tw-text-green-600">{{ formatPrice(active_cash_register.current_balance) }}</div>
                                        </div>
                                        <div v-for="(amount, type) in cash_register_general_history.type_payments" :key="type">
                                            <div :class="{'tw-p-5 tw-rounded-xl tw-shadow-xl tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3':true, 'tw-bg-green-100 ':['efectivo - Total','tarjeta - Total'].includes(type)}">
                                                <div :class="{ 'tw-bg-gray-100':!['efectivo - Total','tarjeta - Total'].includes(type), 'tw-py-2 tw-px-4 tw-rounded-full tw-text-sm':true, 'tw-text-green-600':['efectivo - Total','tarjeta - Total'].includes(type)}">
                                                    Ventas con
                                                <span :class="{'tw-text-purple-600 tw-font-bold':!['efectivo - Total','tarjeta - Total'].includes(type), 'tw-text-green-600':['efectivo - Total','tarjeta - Total'].includes(type)}">{{ formatFirstLetterUppercase(type) }}</span> </div>
                                                <div :class="{'tw-text-4xl tw-font-bold':true, 'tw-text-green-600':['efectivo - Total','tarjeta - Total'].includes(type)}">{{ formatPrice(amount.amount) }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tw-font-bold tw-text-4xl tw-text-center tw-inline-flex tw-pt-10 tw-pb-5">
                                        Historial de transacciones de caja
                                    </div>

                                    <div class="mb-10 cash-register-history">
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
                                                                <v-card title="¿Estas seguro de reimprimir el abono?">
                                                                <v-card-text>
                                                                    <p class="tw-opacity-50 tw-mt-3 tw-text-center">Preciona 'Imprimir abono' para ejecutar la acción.</p>
                                                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-3 mt-5">
                                                                        <p v-for="code in saleTicketsSelected" :key="code" class="tw-py-2 tw-px-7 tw-bg-purple-200 tw-text-purple-700 tw-rounded-md tw-text-xl">{{ code }}</p>
                                                                    </div>
                                                                </v-card-text>

                                                                <v-card-actions class="tw-mb-4 tw-mr-4">
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                    <v-btn :loading="loadingPrint" @click="printTicketAbonado(item, isActive)" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                                                        Imprimir abono
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
                                    <div class="tw-font-bold tw-text-center">
                                        No hay cajas abiertas para este usuario en esta taquilla
                                    </div>
                                    <img class="tw-w-40 lg:tw-w-72 tw-h-auto" src="/storage/public/empty-cart.webp" alt="Webiste image">
                                </div>
                            </v-card>
                        </v-dialog>
                    </div>
                    <Link :href="route('welcome')">
                        <div class="hover:tw-scale-105 tw-transition-all tw-duration-500 tw-full tw-h-48 tw-bg-white/20 tw-backdrop-blur-lg tw-flex tw-items-center tw-justify-center tw-cursor-pointer tw-rounded-md">
                            <span class="tw-font-bold tw-uppercase tw-text-lg">Vender boletos</span>
                        </div>
                    </Link>
                    <div>
                         <v-dialog v-model="dialogPayInstallment" transition="dialog-bottom-transition" fullscreen>
                            <template v-slot:activator="{ props: activatorProps }">
                                <div v-bind="activatorProps" class="hover:tw-scale-105 tw-transition-all tw-duration-500 tw-full tw-h-48 tw-bg-white/20 tw-backdrop-blur-lg tw-flex tw-items-center tw-justify-center tw-cursor-pointer tw-rounded-md">
                                    <span class="tw-font-bold tw-uppercase tw-text-lg">Liquidar ventas pendientes</span>
                                </div>
                            </template>
                            <v-card>
                                <v-toolbar class="!tw-bg-gradient-to-r !tw-from-slate-950 !tw-via-purple-950 !tw-to-slate-950">
                                    <v-btn class="!tw-text-white" icon="mdi-close" @click="dialogPayInstallment = false"></v-btn>
                                    <v-toolbar-title>
                                        <div class="tw-font-bold tw-text-white">Liquidar ventas pendientes</div>
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-toolbar-items>
                                    <v-btn color="white" text="Aceptar" variant="tonal" @click="dialogPayInstallment = false"></v-btn>
                                    </v-toolbar-items>
                                </v-toolbar>
                                <InstallmentPayment :stadium_id="ticket_office.stadium_id" :active_cash_register="active_cash_register" :global_payment_types="global_payment_types" :global_card_payment_types="global_card_payment_types" />
                            </v-card>
                        </v-dialog>
                    </div>
                </div>
           </div>

            <div class="tw-z-0 tw-absolute tw-backdrop-blur-md tw-bg-black/60 tw-bottom-0 tw-left-0 tw-right-0 tw-h-full tw-block"></div>
        </div>
    </main>
</template>
