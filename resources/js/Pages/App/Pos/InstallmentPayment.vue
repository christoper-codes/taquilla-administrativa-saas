<script setup>

import { shallowRef, onMounted, ref, watch } from 'vue';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';
import useStringFormat from '@/composables/stringFormat';
import { installmentPaymentSchema } from '@/validation/pos/installment-payment-schema';
import { useForm, useField } from 'vee-validate'
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';

const { dateFormat } = useDateFormat();
const { formatPrice } = usePriceFormat();
const { formatFirstLetterUppercase, formatTitleCase } = useStringFormat();

const props = defineProps({
    stadium_id: {
        type: Number,
        required: true
    },
    active_cash_register: {
        type: Object,
        required: true
    },
    global_payment_types: {
        type: Array,
        required: true,
    },
    global_card_payment_types: {
        type: Array,
        required: true,
    },
})

const headers = [
    { title: 'Folio', key: 'folio' },
    { title: 'Deudor', key: 'debtor' },
    { title: 'Estatus', key: 'status' },
    { title: 'Fecha de venta', key: 'sale_date' },
    { title: 'Asientos', key: 'seat_codes' },
    { title: 'Monto total', key: 'total_amount' },
    { title: 'Monto Pagado', key: 'amount_paid' },
    { title: 'Adeudo', key: 'remaining_amount' },
    { title: 'Pagos realizados', key: 'payment_types' },
    { title: 'Acciones', key: 'actions', sortable: false }
];

const headerProps = {
    class: '!tw-font-bold'
};

const searchPending = ref('')
const searchPaid = ref('')
const paidTickets = ref([]);
const pendingTickets = ref([]);
const loadingPaidTickets = ref(true);
const loadingSaleTicketStatusPending = ref(true);
const loadingPayment = ref(false);
const loadingDownload = ref(false);

const formatPaymentTypesSummary = (histories) => {
    const paymentSummary = {};
    histories.forEach(history => {
        history.global_payment_types.forEach(type => {
            const name = type.name.charAt(0).toUpperCase() + type.name.slice(1);
            const amount = parseFloat(type.pivot.amount);

            if (paymentSummary[name]) {
                paymentSummary[name] += amount;
            } else {
                paymentSummary[name] = amount;
            }
        });
    });
    return Object.entries(paymentSummary).map(([name, amount]) => `${name}: $${amount.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`).join(', ');
};


const  tranformData = ($data) => {
    return $data.map((saleTicket) => ({
                    'folio': saleTicket.id,
                    'debtor': formatTitleCase(`${saleTicket.sale_debtor.first_name} ${saleTicket.sale_debtor.last_name}`),
                    'debtor_phone_number': saleTicket.sale_debtor.phone_number,
                    'debtor_email': saleTicket.sale_debtor.email,
                    'status': saleTicket.sale_ticket_status.name,
                    'sale_date': dateFormat(saleTicket.created_at),
                    'was_transferred': saleTicket.is_transfer ? 'Si' : 'No',
                    'seat_codes': saleTicket.event_seat_catalogues.map((seatCatalogue) => seatCatalogue.seat_catalogue.code).join(', '),
                    'seats': saleTicket.event_seat_catalogues,
                    'total_amount': formatPrice(saleTicket.total_amount),
                    'total_amount_without_format': saleTicket.total_amount,
                    'amount_paid': formatPrice(Number(saleTicket.installment_payment_histories.reduce((sum, item) => sum + parseFloat(item.total_amount), 0))),
                    'remaining_amount': saleTicket.remaining_amount,
                    'payments_made': saleTicket.installment_payment_histories,
                    'payment_types': formatPaymentTypesSummary(saleTicket.installment_payment_histories),
                    'seller': formatTitleCase(`${saleTicket.seller_user.first_name} ${saleTicket.seller_user.middle_name} ${saleTicket.seller_user.last_name}`),
                    'seller_email': saleTicket.seller_user.email,
                })
            );
}

const getSaleTicketStatusPending = () => {

    loadingSaleTicketStatusPending.value = true;

    axios.post(route('cash-registers.ticket-office.status.pending', { stadium_id: props.stadium_id }))
        .then(response => {

            pendingTickets.value = tranformData(response.data.data);
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            loadingSaleTicketStatusPending.value = false;
        })
};

const exportExcelPending = () => {

    loadingDownload.value = true;

    axios.get(route('stadium.tickets.pending_debtor.exportar', props.stadium_id),{
        responseType: 'blob'
    }).then((response) => {
        window.location.href = response.request.responseURL;
    })
    .catch((error) => {
        console.error('Error:', error);
    })
    .finally(() => {
        loadingDownload.value = false;
    });
};


const getSaleTicketStatusPaid = () => {

    loadingPaidTickets.value = true;

    axios.post(route('cash-registers.tickets.with.installment.payments.completed', { stadium_id: props.stadium_id }))
        .then(response => {

            paidTickets.value = tranformData(response.data.data);
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            loadingPaidTickets.value = false;
        })
};

const exportExcelPaid = () => {

    loadingDownload.value = true;

    axios.get(route('stadium.tickets.paid_debtor.exportar', props.stadium_id),{
        responseType: 'blob'
    }).then((response) => {
        window.location.href = response.request.responseURL;
    })
    .catch((error) => {
        console.error('Error:', error);
    })
    .finally(() => {
        loadingDownload.value = false;
    });
};

/////////////////////////////////////////

const panel = ref([0,1]);
const tab = shallowRef('pending');

const { handleSubmit, resetForm } = useForm({
    validationSchema: installmentPaymentSchema,
    initialValues: {
        cash_register_id: props.active_cash_register?.id,
        total_amount:0,
        total_returned: 0,
        amount_received:0,
        global_payment_type_sale_ticket: [],

        payment_types: [],
        card_types: [],
        amount_card_to_pay: 0,
        amount_cash_received: 0,
        amount_cash_to_pay: 0,
        amount_cash_returned: 0
    },
});

const installmentPayment = {
    sale_ticket_id: useField('sale_ticket_id'),
    total_amount_ticket: useField('total_amount_ticket'),
    cash_register_id: useField('cash_register_id'),
    amount_received: useField('amount_received'),
    total_amount: useField('total_amount'),
    total_returned: useField('total_returned'),
    global_payment_type_sale_ticket: useField('global_payment_type_sale_ticket'),

    payment_types: useField('payment_types'),
    card_types: useField('card_types'),
    amount_card_to_pay: useField('amount_card_to_pay'),
    amount_cash_received: useField('amount_cash_received'),
    amount_cash_to_pay: useField('amount_cash_to_pay'),
    amount_cash_returned: useField('amount_cash_returned'),
}

const dataFormInstallmentPayment = useFormInertia({
    sale_ticket_id: '',
    cash_register_id: '',
    amount_received: '',
    total_amount: '',
    total_returned: '',
    global_payment_type_sale_ticket: []
});

const addDataTicket = (folio, total_amount_ticket) =>{
    activeDialogs.value[folio] = true;
    installmentPayment.sale_ticket_id.setValue(folio);
    installmentPayment.total_amount_ticket.setValue(total_amount_ticket);
}

const activeDialogConfirmation = ref({});
const confirmedPayment = ref(false);

const closeConfirmationDialog = (folio) => {
    confirmedPayment.value = false;

    if (folio !== 0) {
        activeDialogConfirmation.value[folio] = false;
    } else {
        for (const key in activeDialogConfirmation.value) {
            if (activeDialogConfirmation.value[key]) {
                activeDialogConfirmation.value[key] = false;
            }
        }
    }
}

const saveInstallmentPayment = handleSubmit((dataForm) => {

    const amount_card_to_pay = parseFloat(dataForm.amount_card_to_pay);
    const amount_cash_to_pay = parseFloat(dataForm.amount_cash_to_pay);
    const amount_cash_received = parseFloat(dataForm.amount_cash_received);

    const rule_card_types = isPaymetCardType.value ? rules.rule_card_types(dataForm.card_types) : true;
    const rule_amount_card_to_pay = isPaymetCardType.value ? rules.rule_amount_card_to_pay(amount_card_to_pay) : true;
    const rule_amount_cash_received = isPaymetCardType.value ? true : rules.rule_amount_cash_received(amount_cash_received);
    const rule_amount_cash_to_pay = isPaymetCardType.value ? true : rules.rule_amount_cash_to_pay(amount_cash_to_pay);

    if (rule_card_types !== true || rule_amount_card_to_pay !== true || rule_amount_cash_received !== true || rule_amount_cash_to_pay !== true) {
         return;
    }

    if(!activeDialogConfirmation.value[dataForm.sale_ticket_id]){
        activeDialogConfirmation.value[dataForm.sale_ticket_id]=true;
    }


    if(confirmedPayment.value){
        dataFormInstallmentPayment.sale_ticket_id = dataForm.sale_ticket_id;
        dataFormInstallmentPayment.cash_register_id = dataForm.cash_register_id;
        dataFormInstallmentPayment.amount_received = amount_card_to_pay + amount_cash_received;
        dataFormInstallmentPayment.total_amount = amount_card_to_pay + amount_cash_to_pay;
        dataFormInstallmentPayment.total_returned = dataForm.amount_cash_returned;
        dataFormInstallmentPayment.global_payment_type_sale_ticket = dataForm.payment_types.map((paymentType) => {
            return {
                global_payment_type_id: paymentType.id,
                global_card_payment_type_id: dataForm.card_types.length > 0 ? dataForm.card_types[0].id : null,
                sale_ticket_id: dataForm.sale_ticket_id,
                installment_payment_history_id:0,
                amount: dataForm.card_types.length > 0 ? amount_card_to_pay : amount_cash_to_pay,
            }
        });

        loadingPayment.value = true;
        axios.post(route('installment.payment.store'), dataFormInstallmentPayment.data())
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
            loadingPayment.value = false;
            closeDialogDebtor(dataForm.sale_ticket_id);
            getSaleTicketStatusPending();
        });
    }

    if(!confirmedPayment.value){
        confirmedPayment.value = true;
    }
});

const resetFormInstallmentPayment = () => {
    resetForm();
};

const activeDialogs = ref({});
const closeDialogDebtor = (folio) => {
    activeDialogs.value[folio] = false;
    resetFormInstallmentPayment();
    closeConfirmationDialog(0);
};

const handlePaymentTypeSelected = (newVal) => {

  if (newVal.length > 1) {
    installmentPayment.payment_types.setValue([newVal[newVal.length - 1]]);
  } else {
    installmentPayment.payment_types.setValue(newVal);
  }
}

const handlePaymentCardTypeSelected = (newVal) => {
  if (newVal.length > 1) {
    installmentPayment.card_types.setValue([newVal[newVal.length - 1]]);
  } else {
    installmentPayment.card_types.setValue(newVal);
  }
}

const isPaymetCardType = ref(false);
watch(installmentPayment.payment_types.value,(value) => {

    isPaymetCardType.value = value.some(payment => payment.name === 'tarjeta');

    if (!isPaymetCardType.value && installmentPayment.card_types.value.length > 0) {
      installmentPayment.card_types.setValue([]);
    }
  }
);

watch(installmentPayment.amount_cash_received.value,(value) => {
    calculateCashReturned();
});

watch(installmentPayment.amount_cash_to_pay.value,(value) => {
    calculateCashReturned();
});

const calculateCashReturned = () => {

    let cashReceived = installmentPayment.amount_cash_received.value.value;
    let cashToPay = installmentPayment.amount_cash_to_pay.value.value;

    if (cashReceived && cashToPay) {
        cashReceived = parseFloat(cashReceived);
        cashToPay = parseFloat(cashToPay);

        const cashReturned = cashReceived - cashToPay;
        installmentPayment.amount_cash_returned.setValue(cashReturned);
    } else {
        installmentPayment.amount_cash_returned.setValue(0);
    }
};

const rules = {
    rule_card_types: value => installmentPayment.payment_types.value.value.some(payment => payment.name === 'tarjeta') && value.length === 1 || 'Campo requerido',
    rule_amount_card_to_pay: value => {
        if(installmentPayment.payment_types.value.value.some(payment => payment.name === 'tarjeta')){
            if(isNaN(parseFloat(installmentPayment.amount_card_to_pay.value.value)) || parseFloat(installmentPayment.amount_card_to_pay.value.value) <= 0){
                return 'Campo requerido o no válido';
            }else{
                if(parseFloat(installmentPayment.amount_card_to_pay.value.value) > parseFloat(installmentPayment.total_amount_ticket.value.value)){
                    return 'El monto a pagar no puede ser mayor al monto total';
                }else{
                    return true;
                }
            }
         }else{
            return true
         }
    },
    rule_amount_cash_received: value => {
        if(installmentPayment.payment_types.value.value.some(payment => payment.name === 'efectivo')){
            if(isNaN(parseFloat(installmentPayment.amount_cash_received.value.value)) || parseFloat(installmentPayment.amount_cash_received.value.value) <= 0){
                return 'Campo requerido o no válido';
            }else{
                return true;
            }
         }else{
            return true
         }
    },
    rule_amount_cash_to_pay: value =>{
         if(installmentPayment.payment_types.value.value.some(payment => payment.name === 'efectivo')){
            if(isNaN(parseFloat(installmentPayment.amount_cash_to_pay.value.value)) || parseFloat(installmentPayment.amount_cash_to_pay.value.value) <= 0){
                return 'Campo requerido o no válido';
            }else{
                if(parseFloat(installmentPayment.amount_cash_to_pay.value.value) > parseFloat(installmentPayment.amount_cash_received.value.value)){
                    return 'El monto a pagar no puede ser mayor al monto a recibido';
                }else{
                    if(parseFloat(installmentPayment.amount_cash_to_pay.value.value) > parseFloat(installmentPayment.total_amount_ticket.value.value)){
                        return 'El monto a pagar no puede ser mayor al monto total';
                    }else{
                        return true;
                    }
                }
            }
         }else{
            return 'Campo requerido'
         }
    },
    rule_amount_cash_returned: value => value >= 0 || 'El cambio no puede ser negativo',
};

const loadingPrint = ref(false);
const printSubscriberInstallmentReceipt = (folio, isActive) => {

    loadingPrint.value = true;

    axios.get(route('events.subscribers.installment.receipt', { id: folio }))
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
};

function printInKioskMode(url, close = true) {
    const ventana = window.open(url, '_blank', 'fullscreen=yes,kiosk=yes');
    ventana.onload = () => {
        ventana.print();

    };
}

/////////////////////////////////////////


onMounted(() => {
    getSaleTicketStatusPending();
    getSaleTicketStatusPaid();
})



</script>

<template>
    <div v-if="active_cash_register" class="my-10">
        <v-tabs v-model="tab" fixed-tabs>
            <v-tab value="pending">Pendientes</v-tab>
            <v-tab value="paid">Pagados</v-tab>
        </v-tabs>
        <v-tabs-window v-model="tab" class="tw-my-10 tw-mx-10">
            <v-tabs-window-item value="pending">
                <div class="tw-flex tw-justify-between tw-items-center">
                    <v-text-field v-model="searchPending" label="Buscar" prepend-inner-icon="mdi-magnify" variant="outlined" hide-details single-line></v-text-field>
                    <v-btn size="x-large" color="success" class="tw-mx-2" @click="exportExcelPending" :loading="loadingDownload" :disabled="loadingDownload">
                        <span class="material-symbols-outlined">Download</span> Excel
                    </v-btn>
                </div>
                <v-data-table :items="pendingTickets" :headers="headers" :header-props="headerProps" :search="searchPending" :loading="loadingSaleTicketStatusPending">
                    <template v-slot:item.status="{ item }">
                        <span class="tw-py-1 tw-px-4 tw-rounded-full" :class="item.status === 'pagado' ? '!tw-text-green-600 tw-bg-green-100' : '!tw-text-red-600 tw-bg-red-100'">
                            {{ formatFirstLetterUppercase(item.status) }}
                        </span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <div class="tw-flex tw-items-center tw-gap-3 tw-justify-between !tw-my-3">
                            <v-dialog  v-model="activeDialogs[item.folio]" max-width="1000" persistent>
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn
                                        @click="addDataTicket(item.folio, item.total_amount_without_format )"
                                        v-bind="activatorProps"
                                        density="default" class="!tw-text-blue-600 !tw-bg-blue-200">
                                        <span class="material-symbols-outlined tw-text-lg">Payments</span>
                                    </v-btn>
                                </template>
                                <v-card class="tw-font-bold" title="Realizar pago de deuda">
                                    <v-card-text>
                                        <div class="tw-max-w-5xl tw-mx-auto tw-p-6 tw-bg-white tw-rounded-2xl tw-space-y-6 tw-text-gray-800">
                                            <!-- Encabezado -->
                                            <div class="tw-flex tw-justify-between tw-items-center tw-border-b tw-pb-4">
                                                <span class="">Folio {{ item.folio }} </span>
                                                <span class="">Reservado el {{  item.sale_date }}</span>
                                            </div>

                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">person</span>
                                                    Deudor
                                                </h3>
                                                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm">
                                                    <p><span class="tw-font-semibold">Nombre: </span>{{ item.debtor }}</p>
                                                    <p><span class="tw-font-semibold">Teléfono:</span> {{ item.debtor_phone_number}}</p>
                                                    <p><span class="tw-font-semibold">Email:</span> <em>{{ item.debtor_email }}</em></p>
                                                </div>
                                            </div>

                                            <!-- Resumen de Pagos -->
                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">payments</span>
                                                    Resumen de pago</h3>
                                                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm">
                                                    <p><span class="tw-font-semibold">Total venta: </span>{{ item.total_amount }} MXN</p>
                                                    <p><span class="tw-font-semibold">Pagado: </span>{{ item.amount_paid }} MXN</p>
                                                    <p><span class="tw-font-semibold tw-text-red-600">Pendiente por pagar: </span>
                                                    <span class="tw-text-red-600 tw-font-bold">{{ formatPrice(item.remaining_amount) }} MXN</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Asientos -->
                                            <div>
                                            <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                <span class="material-symbols-outlined">chair</span>
                                                Asientos
                                            </h3>
                                            <ul class="tw-space-y-2 tw-text-sm">
                                                <li v-for="(seat, index) in item.seats" :key="index" class="tw-border tw-p-3 tw-rounded-lg tw-flex tw-justify-between tw-items-center tw-bg-gray-50">
                                                    <div>
                                                        <p><strong>Asiento:</strong> Zona {{ seat.seat_catalogue.zone }} - Fila {{ seat.seat_catalogue.row }} - Asiento {{ seat.seat_catalogue.seat }} <span class="tw-text-gray-500">({{ seat.seat_catalogue.code }})</span></p>
                                                        <p><strong>Tipo:</strong> {{ formatFirstLetterUppercase(seat.purchase_type) }}</p>
                                                    </div>
                                                    <p class="tw-font-semibold"> {{ formatPrice(seat.price) }} MXN</p>
                                                </li>
                                            </ul>
                                            </div>

                                            <!-- Historial de pagos -->
                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">assignment</span>
                                                    Historial de pagos
                                                </h3>
                                                <ul class="tw-space-y-2 tw-text-sm">
                                                    <li v-for="(payments, index) in item.payments_made" class="tw-border tw-p-3 tw-rounded-lg tw-flex tw-justify-between tw-bg-green-50">
                                                    <div>
                                                        <p><strong>Fecha:</strong> {{ dateFormat(payments.created_at) }}</p>
                                                        <p><strong>Vendedor:</strong> {{
                                                        formatTitleCase(
                                                            `${payments?.cash_register?.seller_user_opening?.first_name ?? ''} ` +
                                                            `${payments?.cash_register?.seller_user_opening?.middle_name ?? ''} ` +
                                                            `${payments?.cash_register?.seller_user_opening?.last_name ?? ''}`
                                                        )
                                                        }}</p>
                                                    </div>
                                                    <p class="tw-font-semibold tw-text-green-700"> {{ formatPrice(payments.total_amount) }} MXN </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="tw-max-w-5xl tw-mx-auto tw-p-6 tw-bg-white tw-rounded-2xl tw-space-y-6 tw-mb-4 shadow-lg tw-text-gray-800">
                                            <v-form>
                                                <v-expansion-panels v-model="panel" variant="popout">
                                                    <v-expansion-panel title="Pagar">
                                                        <v-expansion-panel-text>

                                                            <div :class="{ 'tw-grid tw-gap-4': true, 'tw-grid-cols-2': isPaymetCardType, 'tw-grid-cols-1': !isPaymetCardType}">

                                                                <v-select color="purple" label="Selecciona el tipo de pago" hint="Selecciona el tipo de pago"
                                                                    chips multiple clearable
                                                                    :items="global_payment_types"
                                                                    :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                    return-object
                                                                    v-model="installmentPayment.payment_types.value.value"
                                                                    @update:modelValue="handlePaymentTypeSelected"
                                                                    :error-messages="installmentPayment.payment_types.errorMessage.value"
                                                                    ></v-select>

                                                                <v-select
                                                                    v-if="isPaymetCardType"
                                                                    color="purple" label="Selecciona el tipo de pago" hint="Selecciona el tipo de pago"
                                                                    chips multiple clearable
                                                                    :items="global_card_payment_types"
                                                                    :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                    return-object
                                                                    v-model="installmentPayment.card_types.value.value"
                                                                    :rules="[rules.rule_card_types]"
                                                                    @update:modelValue="handlePaymentCardTypeSelected"
                                                                    ></v-select>
                                                            </div>

                                                            <!-- pago con tarjeta -->
                                                            <div v-if="isPaymetCardType" class="tw-grid tw-gap-4 tw-grid-cols-1">
                                                                <v-text-field
                                                                    label="Monto a pagar con tarjeta" color="purple" hint="Monto recibido por el cliente"
                                                                    v-model="installmentPayment.amount_card_to_pay.value.value"
                                                                    :rules="[rules.rule_amount_card_to_pay]"
                                                                ></v-text-field>
                                                            </div>

                                                            <!-- pago en efectivo -->
                                                            <div v-if="installmentPayment.payment_types.value.value.length && !isPaymetCardType" class="tw-grid tw-gap-4 tw-grid-cols-2">
                                                                <v-text-field
                                                                    label="Monto recibido para efectivo" color="purple" clearable hint="Monto recibido por el cliente"
                                                                    v-model="installmentPayment.amount_cash_received.value.value"
                                                                    :rules="[rules.rule_amount_cash_received]"
                                                                ></v-text-field>
                                                                <v-text-field
                                                                    label="Monto a pagar para efectivo"
                                                                    color="purple"
                                                                    clearable
                                                                    hint="Monto a pagar por el cliente"
                                                                    v-model="installmentPayment.amount_cash_to_pay.value.value"
                                                                    :rules="[rules.rule_amount_cash_to_pay]"
                                                                ></v-text-field>
                                                            </div>
                                                            <div v-if="installmentPayment.payment_types.value.value.length && !isPaymetCardType" class="tw-grid tw-gap-4 tw-grid-cols-1">
                                                                <v-text-field
                                                                    label="Cambio a devolver"
                                                                    color="purple"
                                                                    readonly
                                                                    v-model="installmentPayment.amount_cash_returned.value.value"
                                                                    :rules="[rules.rule_amount_cash_returned]"
                                                                ></v-text-field>
                                                            </div>

                                                        </v-expansion-panel-text>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-form>
                                        </div>

                                    </v-card-text>
                                    <v-card-actions class="tw-mb-4 tw-mr-4">
                                        <v-spacer></v-spacer>
                                        <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="closeDialogDebtor(item.folio)"></v-btn>
                                        <v-btn @click="saveInstallmentPayment" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                            Pagar
                                        </v-btn>
                                        <v-dialog v-model="activeDialogConfirmation[item.folio]" max-width="800">
                                                <v-card>
                                                <v-card-text>
                                                    <p class="tw-font-bold tw-text-sm lg:tw-text-xl tw-text-gray-700">¿Estas seguro de realizar el abono a la deuda?</p>
                                                    <v-card-title class="tw-mt-4">Resumen de pago</v-card-title>
                                                    <v-card-subtitle class="tw-my-4">
                                                        <v-row>
                                                            <v-col cols="12" md="4">
                                                                <strong>Metodos de Pago: </strong>{{  installmentPayment.payment_types.value.value.map(payment => formatFirstLetterUppercase(payment.name)).join(', ') }}
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="installmentPayment.payment_types.value.value.length && !isPaymetCardType">
                                                            <v-col cols="12" md="4">
                                                                <strong>Monto a Pagar con Efectivo: </strong> $ {{  installmentPayment.amount_cash_to_pay.value }} MXN
                                                            </v-col>
                                                        </v-row>
                                                        <v-row v-if="isPaymetCardType">
                                                            <v-col cols="12" md="4">
                                                                <strong>Monto a Pagar con Tarjeta: </strong> $ {{  installmentPayment.amount_card_to_pay.value }} MXN
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col cols="12" md="4">
                                                                <strong>Monto Restante despues del pago: </strong> {{ formatPrice(item.remaining_amount - installmentPayment.amount_card_to_pay.value.value - installmentPayment.amount_cash_to_pay.value.value) }} MXN
                                                            </v-col>
                                                        </v-row>
                                                    </v-card-subtitle>
                                                </v-card-text>

                                                <v-card-actions class="tw-mb-2 tw-mr-2">
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="red" variant="tonal" class="text-none" text="Cancelar" @click="closeConfirmationDialog(item.folio)"></v-btn>
                                                    <v-btn :loading="loadingPayment" @click="saveInstallmentPayment" text="Aceptar" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white"></v-btn>
                                                </v-card-actions>

                                                </v-card>
                                    </v-dialog>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog max-width="600">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" density="default" icon="mdi-printer-settings" class="!tw-text-purple-600 !tw-bg-purple-300"></v-btn>
                                </template>
                                <template v-slot:default="{ isActive }">
                                    <v-card title="¿Estas seguro de reimprimir el recibo?">
                                    <v-card-text>
                                        <p class="tw-opacity-50 tw-mt-3 tw-text-center">Preciona 'Imprimir Recibo' para ejecutar la acción.</p>
                                    </v-card-text>

                                    <v-card-actions class="tw-mb-4 tw-mr-4">
                                        <v-spacer></v-spacer>
                                        <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                        <v-btn :loading="loadingPrint" @click="printSubscriberInstallmentReceipt(item.folio, isActive)" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                            Imprimir Recibo
                                        </v-btn>
                                    </v-card-actions>
                                    </v-card>
                                </template>
                            </v-dialog>
                        </div>
                    </template>
                </v-data-table>
            </v-tabs-window-item>
            <v-tabs-window-item value="paid">
                <div class="tw-flex tw-justify-between tw-items-center">
                    <v-text-field v-model="searchPaid" label="Buscar" prepend-inner-icon="mdi-magnify" variant="outlined" hide-details single-line></v-text-field>
                    <v-btn size="x-large" color="success" class="tw-mx-2" @click="exportExcelPaid" :loading="loadingDownload" :disabled="loadingDownload">
                        <span class="material-symbols-outlined">Download</span> Excel
                    </v-btn>
                </div>
                <v-data-table :items="paidTickets" :headers="headers" :header-props="headerProps" :search="searchPaid" :loading="loadingPaidTickets">
                    <template v-slot:item.status="{ item }">
                        <span class="tw-py-1 tw-px-4 tw-rounded-full" :class="item.status === 'pagado' ? '!tw-text-green-600 tw-bg-green-100' : '!tw-text-red-600 tw-bg-red-100'">
                            {{ formatFirstLetterUppercase(item.status) }}
                        </span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <div class="tw-flex tw-items-center tw-gap-3 tw-justify-between !tw-my-3">
                            <v-dialog  v-model="activeDialogs[item.folio]" max-width="1000" persistent>
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn
                                        @click="addDataTicket(item.folio, item.total_amount_without_format )"
                                        v-bind="activatorProps"
                                        density="default" class="!tw-text-blue-600 !tw-bg-blue-200">
                                        <span class="material-symbols-outlined tw-text-lg">Visibility</span>
                                    </v-btn>
                                </template>
                                <v-card class="tw-font-bold" title="Compra a plazos pagada">
                                    <v-card-text>
                                        <div class="tw-max-w-5xl tw-mx-auto tw-p-6 tw-bg-white tw-rounded-2xl tw-space-y-6 tw-text-gray-800">
                                            <!-- Encabezado -->
                                            <div class="tw-flex tw-justify-between tw-items-center tw-border-b tw-pb-4">
                                                <span class="">Folio {{ item.folio }} </span>
                                                <span class="">Reservado el {{  item.sale_date }}</span>
                                            </div>

                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">person</span>
                                                    Deudor
                                                </h3>
                                                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm">
                                                    <p><span class="tw-font-semibold">Nombre: </span>{{ item.debtor }}</p>
                                                    <p><span class="tw-font-semibold">Teléfono:</span> {{ item.debtor_phone_number}}</p>
                                                    <p><span class="tw-font-semibold">Email:</span> <em>{{ item.debtor_email }}</em></p>
                                                </div>
                                            </div>

                                            <!-- Resumen de Pagos -->
                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">payments</span>
                                                    Resumen de pago</h3>
                                                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm">
                                                    <p><span class="tw-font-semibold">Total venta: </span>{{ item.total_amount }} MXN</p>
                                                    <p><span class="tw-font-semibold">Pagado: </span>{{ item.amount_paid }} MXN</p>
                                                    <p><span class="tw-font-semibold tw-text-red-600">Pendiente por pagar: </span>
                                                    <span class="tw-text-red-600 tw-font-bold">{{formatPrice(item.remaining_amount) }} MXN</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Asientos -->
                                            <div>
                                            <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                <span class="material-symbols-outlined">chair</span>
                                                Asientos
                                            </h3>
                                            <ul class="tw-space-y-2 tw-text-sm">
                                                <li v-for="(seat, index) in item.seats" :key="index" class="tw-border tw-p-3 tw-rounded-lg tw-flex tw-justify-between tw-items-center tw-bg-gray-50">
                                                    <div>
                                                        <p><strong>Asiento:</strong> Zona {{ seat.seat_catalogue.zone }} - Fila {{ seat.seat_catalogue.row }} - Asiento {{ seat.seat_catalogue.seat }} <span class="tw-text-gray-500">({{ seat.seat_catalogue.code }})</span></p>
                                                        <p><strong>Tipo:</strong> {{ formatFirstLetterUppercase(seat.purchase_type) }}</p>
                                                    </div>
                                                    <p class="tw-font-semibold"> {{ formatPrice(seat.price) }} MXN</p>
                                                </li>
                                            </ul>
                                            </div>

                                            <!-- Historial de pagos -->
                                            <div>
                                                <h3 class="tw-text-lg tw-font-semibold tw-mb-2">
                                                    <span class="material-symbols-outlined">assignment</span>
                                                    Historial de pagos
                                                </h3>
                                                <ul class="tw-space-y-2 tw-text-sm">
                                                    <li v-for="(payments, index) in item.payments_made" class="tw-border tw-p-3 tw-rounded-lg tw-flex tw-justify-between tw-bg-green-50">
                                                    <div>
                                                        <p><strong>Fecha:</strong> {{ dateFormat(payments.created_at) }}</p>
                                                        <p><strong>Vendedor:</strong> {{
                                                        formatTitleCase(
                                                            `${payments?.cash_register?.seller_user_opening?.first_name ?? ''} ` +
                                                            `${payments?.cash_register?.seller_user_opening?.middle_name ?? ''} ` +
                                                            `${payments?.cash_register?.seller_user_opening?.last_name ?? ''}`
                                                        )
                                                        }}</p>
                                                    </div>
                                                    <p class="tw-font-semibold tw-text-green-700"> {{ formatPrice(payments.total_amount) }} MXN </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </v-card-text>
                                    <v-card-actions class="tw-mb-4 tw-mr-4">
                                        <v-spacer></v-spacer>
                                        <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cerrar" @click="closeDialogDebtor(item.folio)"></v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog max-width="600">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" density="default" icon="mdi-printer-settings" class="!tw-text-purple-600 !tw-bg-purple-300"></v-btn>
                                </template>
                                <template v-slot:default="{ isActive }">
                                    <v-card title="¿Estas seguro de reimprimir el recibo?">
                                    <v-card-text>
                                        <p class="tw-opacity-50 tw-mt-3 tw-text-center">Preciona 'Imprimir Recibo' para ejecutar la acción.</p>
                                    </v-card-text>

                                    <v-card-actions class="tw-mb-4 tw-mr-4">
                                        <v-spacer></v-spacer>
                                        <v-btn color="red" rounded="large" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                        <v-btn :loading="loadingPrint" @click="printSubscriberInstallmentReceipt(item.folio)" rounded="large" variant="elevated" class="text-none !tw-bg-purple-600 !tw-text-white">
                                            Imprimir Recibo
                                        </v-btn>
                                    </v-card-actions>
                                    </v-card>
                                </template>
                            </v-dialog>
                        </div>
                    </template>
                </v-data-table>
            </v-tabs-window-item>
        </v-tabs-window>
        <v-dialog max-width="800">
            <template v-slot:activator="{ props: activatorProps }">
                <v-btn id="on-submit-confirm" v-bind="activatorProps" variant="elevated" class="!tw-hidden text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
            </template>
        </v-dialog>
    </div>
    <div v-else class="tw-flex tw-items-center tw-justify-center tw-mt-20 tw-flex-col tw-gap-10">
        <div class="tw-font-bold tw-text-center">
            No hay cajas abiertas para este usuario en esta taquilla
        </div>
        <img class="tw-w-40 lg:tw-w-72 tw-h-auto" src="/storage/public/empty-cart.webp" alt="Webiste image">
    </div>
</template>
