<script setup>
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm as useFormInertia, usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { computed, nextTick, onMounted, ref, watch } from 'vue';
import StadiumSVG from '@/Components/SectionsHdx/StadiumSVG.vue';
import FZona from '@/Components/SectionsHdx/FZona.vue';
import EstadioHdx from '@/Components/SectionsHdx/EstadioHdx.vue';
import ZonaA from '@/Components/SectionsHdx/ZonaA.vue';
import ZonaC from '@/Components/SectionsHdx/ZonaC.vue';
import ZonaF from '@/Components/SectionsHdx/ZonaF.vue';
import usePriceFormat from '@/composables/priceFormat';
import PaymentDrawer from '@/Components/PaymentDrawer.vue';
import useUserPolicy from '@/composables/UserPolicy';
import panzoom from 'panzoom';
import ErrorSession from '@/Components/ErrorSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { drawerPaymentState } from '@/composables/drawersStates';
import SuccessSession from '@/Components/SuccessSession.vue';
import CountdownTimer from '@/Components/CountdownTimer.vue';
import useDateFormat from '@/composables/dateFormat';
import useTicketOfficeState from '@/composables/TicketOfficeState';
import { saleTicketSchema } from '@/validation/pos/sale-ticket-schema';
import { useField, useForm } from 'vee-validate';
import axios from 'axios';
import { toast } from 'vue3-toastify'

const { dateFormat } = useDateFormat();
const { cashRegisterDataId, sellerUserId } = useTicketOfficeState();
const snackbar = ref(false);
const { handleSubmit } = useForm({
    validationSchema: saleTicketSchema,
    initialValues: {
        total: 0,
        amount_received: 0,
        amount_returned: 0,
    }
});

const paymentFileds = {
    'total': useField('total'),
    'amount_received': useField('amount_received', 0),
    'amount_returned': useField('amount_returned', 0),
}

const totalAmount = ref(0);
const amountReceived = ref(0);
const amountReturned = ref(0);

/*
* |--------------------------------------
* | declare properties
*/
const { formatPrice } = usePriceFormat();
const { viewVendorTopics } = useUserPolicy();
let panZoomInstance;
const paymentSection = ref(null);
const scrollTopaymentSection = () => {
    paymentSection.value.scrollIntoView({ behavior: 'smooth' });
}

function loadSvg(id) {
    setTimeout(() => {
        const zoneId = document.querySelector(`#${id}`);
        if (zoneId) {
            panZoomInstance = panzoom(zoneId);
            if(id != 'zones_hdx') {

                const { x, y } = getCenterCoordinates(id);
                if(window.innerWidth > 1024) {
                    panZoomInstance.smoothZoom(x, y, 2.3);
                }else {
                    panZoomInstance.smoothZoom(x, y, 5);
                }

            }
            if(window.innerWidth > 1024 && id == 'zones_hdx') {
                panZoomInstance.smoothZoom(400, 360, 0.6);
            }
        }else {
            alert('Zona no encontrada');
        }
    },300);
}

const getCenterCoordinates = (id) => {
  const svgElement = document.querySelector(`#${id}`);
  const { width, height } = svgElement.getBoundingClientRect();
  if(window.innerWidth > 1024) {
        return { x: width / -7, y: height / 2.5 };
    }else {
        return { x: width / -23, y: height / 2.05 };
    }
};

const zoomIn = () => {
  if (panZoomInstance) {
    panZoomInstance.smoothZoom(0, 0, 1.2);
  }
};

const resetZoom = () => {
  if (panZoomInstance) {
    panZoomInstance.moveTo(0, 0);
    panZoomInstance.zoomAbs(0, 0, 1);
  }
};

const zoomOut = () => {
  if (panZoomInstance) {
    panZoomInstance.smoothZoom(0, 0, 0.8);
  }
};

/*
* Handle POS section
*/
const seatsSelected = ref([]);
/* const paymentFileds.total value.= ref(0); */

function priceFinal(seat, priceTypeName) {
    return seat.price_types.reduce((acc, priceType) => {
        if(priceType.name === priceTypeName){
            return acc + parseFloat(priceType.pivot.price);
        }

        return acc;
    }, 0);
}


const addSeat = (seat) => {
    if(purchaseStatus.value == 'final' || purchaseStatus.value == 'retry') {
        purchaseStatus.value = 'retry';
        return;
    }
    const seatExist = seatsSelected.value.find((s) => s.seat_catalogue.code === seat.seat_catalogue.code);
    priceTypeId.value = seat.price_types[0].id;
    const priceFinalType = paymentTypesSelected.value.some(type => type.name == 'cortesia') ? 'cortesia' : 'regular';
   if(paymentTypesSelected.value.length == 0) {
        paymentTypesSelected.value.push(props.global_payment_types.find((item) => item.name === 'tarjeta'));
    }
    if (!seatExist) {
        seat.quantity = 1;
        seat.final_price = priceFinal(seat, priceFinalType);
        seatsSelected.value.push(seat);
        snackbar.value = true;

        if(viewVendorTopics(props.user_roles)) {
           // vendedor

        } else {
            /* amountReceived.value = totalAmount.value; */
        }
        const regularPrice = priceFinal(seat, priceFinalType);
        totalAmount.value = (parseFloat(totalAmount.value || 0) + parseFloat(regularPrice));
    } else {
        seatsSelected.value = seatsSelected.value.filter((s) => s.seat_catalogue.code !== seat.seat_catalogue.code);
        if(seatsSelected.value.length == 0) {
            snackbar.value = false;
        }

        if(viewVendorTopics(props.user_roles)) {
            // vendedor
        } else {
           /* amountReceived.value = totalAmount.value; */
        }
        const regularPrice = priceFinal(seat, priceFinalType);
        totalAmount.value = (parseFloat(totalAmount.value || 0) - parseFloat(regularPrice));
    }

    if (paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        //amountReceived.value = totalAmount.value;
    }
    amountToPayCash.value = totalAmount.value;
    amountToPayCard.value = totalAmount.value;
    updateTotal();
}



/*
* handle global payment types
*/
const globalPaymentTypes = ref([]);
const purchaseOnline = ref(true);
const priceTypeId = ref(1);

const panel = ref([0,1]);
const purchaseType = ref('partido');
const paymentTypesSelected = ref([]); //

const filteredPaymentTypes = computed(() => {
    if (paymentTypesSelected.value.some(type => type.name === 'cortesia')) {
        const newPaymentTypesSelected = paymentTypesSelected.value.filter(type => type.name === 'cortesia');
        paymentTypesSelected.value = newPaymentTypesSelected;
        return paymentTypesSelected.value;
    }
    return paymentTypesSelected.value;
});

watch(filteredPaymentTypes, updateTotal);

function updateTotal() {
    amountReceived.value = 0;
    if (paymentTypesSelected.value.length >= 2) {
        amountReceivedCash.value = 0;
        amountToPayCash.value = 0;
        amountToPayCard.value = 0;
    }
    totalAmount.value = 0;
    // Usamos un set para asegurar que cada asiento se procese una sola vez.
    const processedSeats = new Set();

    filteredPaymentTypes.value.forEach(paymentType => {
        seatsSelected.value.forEach((seat) => {
            if (!processedSeats.has(seat.seat_catalogue.code)) {
                let price;
                if (paymentType.name === 'cortesia') {
                    price = priceFinal(seat, 'cortesia');
                } else {
                    price = priceFinal(seat, 'regular');
                }
                totalAmount.value = (parseFloat(totalAmount.value || 0) + parseFloat(price));
                processedSeats.add(seat.seat_catalogue.code);
            }
        });
    });

    if (purchaseType.value === 'serie') {
        totalAmount.value = totalAmount.value * 2;
    }

    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        amountReceived.value = totalAmount.value;
    }

}



const globalPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const globalCardPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const isSvgVisible = ref(true);
const selectedSection = ref('');
const viewSelectedSection = ref('Zonas HDX');

const handleSectionClick = (section) => {
    selectedSection.value = section;
    isSvgVisible.value = false;

    if(section == 'zonaA'){
        loadSvg('zonaA');
        viewSelectedSection.value = 'Zona A';
        const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
        stadiumHdxImg.classList.remove('tw-rotate-0');
        stadiumHdxImg.classList.add('tw-rotate-90');
    }

    if(section == 'zonaC'){
        loadSvg('zonaC');
        viewSelectedSection.value = 'Zona C';
        const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
        stadiumHdxImg.classList.remove('tw-rotate-0');
        stadiumHdxImg.classList.add('tw-rotate-90');
    }

    if(section == 'zonaF'){
        loadSvg('zonaF');
        viewSelectedSection.value = 'Zona F';
        const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
        stadiumHdxImg.classList.remove('tw-rotate-0');
        stadiumHdxImg.classList.add('tw-rotate-90');
    }

};

const selectZones = () => {
    loadSvg('zones_hdx');
    isSvgVisible.value = true;
    selectedSection.value = '';
    totalAmount.value = 0;
    amountReceived.value = 0;
    amountReturned.value = 0;
    amountReceivedCash.value = 0;
    amountToPayCard.value = 0;
    amountToPayCash.value = 0;
    paymentTypesSelected.value = [];
    cardPaymentTypesSelected.value = 0;
    valid.value = true;
    purchaseStatus.value = 'process';
    viewSelectedSection.value = 'Zonas HDX';
    purchaseType.value = 'partido';
    loadingg.value = false;
    loading.value = false;
    userToTransfer.value = null;
    seatsSelected.value = [];
    const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
    stadiumHdxImg.classList.remove('tw-rotate-90');
    stadiumHdxImg.classList.add('tw-rotate-0');
};

/*
* declare props
*/
const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    a_zone: {
        type: Array,
        required: true,
    },
    b_zone: {
        type: Array,
        required: true,
    },
    c_zone: {
        type: Array,
        required: true,
    },
    f_zone: {
        type: Array,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
    user_roles: {
        type: Array,
        required: false,
    },
    global_payment_types: {
        type: Array,
        required: true,
    },
    global_card_payment_types: {
        type: Array,
        required: true,
    },
    purchase_types: {
        type: Array,
        required: true,
    },
});

const users_list = [];
const userToTransfer = ref(null);

props.users.forEach(element => {
    users_list.push(
        {
            name: `${element['first_name']} ${element['last_name']} (${element['email']})`,
            value: element['id']
        }
    )
});

/*
* |--------------------------------------
* | declare OnMounted
*/
onMounted(() => {
    nextTick(() => {
        loadSvg('zones_hdx');
    });

    const sellerDialog = document.getElementById('seller-dialog');
    if(viewVendorTopics(props.user_roles)) {
        if(sellerUserId.value != props.user.id) {
            sellerDialog.click();
        }
    }
});

/*
* |--------------------------------------
* | Reserve selected seats and complete purchase
*/

const loading = ref(false);
const form = ref(false);
const loadingg = ref(false);
const valid = ref(true);
const error = ref('');
const amountToPayCard = ref(0);
const cardPaymentTypesSelected = ref(0);
const amountToPayCash = ref(0);
const amountReceivedCash = ref(0);
const purchaseStatus = ref('process');
const originalTotalAmount = ref(0);
const totalAttempts = ref(0);
const memberUserId = ref(1);

watch(() => amountReceived.value, (newValue) => {
    amountReturned.value = parseFloat(amountReceived.value) - parseFloat(totalAmount.value)
});

watch(() => amountToPayCard.value, (newValue) => {
     amountReceived.value = parseFloat(amountToPayCard.value) + parseFloat(amountReceivedCash.value);
});

watch(() => amountReceivedCash.value, (newValue) => {
     amountReceived.value = parseFloat(amountToPayCard.value) + parseFloat(amountReceivedCash.value);
});

watch(() => purchaseType.value, (newValue) => {
    purchaseStatus.value = 'final';
    if(totalAttempts.value == 0) {
        originalTotalAmount.value = totalAmount.value;
        totalAttempts.value = 1;
    }
    if(newValue == 'serie') {
        totalAmount.value = totalAmount.value * 2;
    } else if(newValue == 'partido') {
        totalAmount.value = originalTotalAmount.value;
    }
    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        amountToPayCard.value = totalAmount.value;
    }
    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')) {
        amountToPayCash.value = totalAmount.value;
    }
});

function completePurchase(isActive) {

    if(viewVendorTopics(props.user_roles)) {
        // vendedor
        purchaseOnline.value = false;
        sellerUserId.value = props.user.id;

    } else {
        globalPaymentTypes.value = globalPaymentTypes.value.map((item) => {
            return {
            ...item,
            amount: totalAmount.value,
            }
        })
    }

    const seatsSelectedData = useFormInertia({
        event_id: props.event.id,
        cash_register_id: cashRegisterDataId.value,
        member_user_id: props.user.id,
        seller_user_id: sellerUserId.value,
        price_type_id: priceTypeId.value,
        seats: seatsSelected.value,
        amount_received: amountReceived.value,
        total_amount: totalAmount.value,
        total_returned: amountReturned.value,
        global_payment_types: globalPaymentTypes.value,
        is_online: purchaseOnline.value,
    });

    loading.value = true;

    seatsSelectedData.post(route('events.reserve-seats-to-buy'), {
        onSuccess: (response) => {
            if(!response.props.flash.error) {
                drawerPaymentState.value = true;
            }
        },
        onFinish: () => {
            isActive.value = false;
            loading.value = false;
        }
    });
}

const onSubmit = () => {
    if(!form.value) return

    if(paymentTypesSelected.value.length == 0) {
        valid.value = false;
        error.value = 'Debe seleccionar al menos un tipo de pago';
        return;
    }

    if(viewVendorTopics(props.user_roles)) {
        purchaseOnline.value = false;
        if(sellerUserId.value != props.user.id) {
            valid.value = false;
            error.value = 'El vendedor seleccionado no coincide con el usuario logueado, por favor abra una caja. ';
            return;
        }
    }

    if(paymentTypesSelected.value.some(type => type.name === 'efectivo' )){
        //validar que el monto recibido para efectivo sea igual o mayor al monto a pagar para efectivo
        if(parseFloat(amountReceivedCash.value) < parseFloat(amountToPayCash.value)) {
            valid.value = false;
            error.value = 'El monto recibido en efectivo debe ser igual o mayor al monto a pagar en efectivo';
            return;
        }
    }

    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        amountToPayCard.value = totalAmount.value;
    }

    globalPaymentTypes.value = paymentTypesSelected.value.map((item) => {
        if(item.name === 'tarjeta') {
            return {
                id: item.id,
                global_card_payment_type_id: cardPaymentTypesSelected.value.id,
                amount: amountToPayCard.value,
            }
        }
        if(item.name === 'efectivo') {
            return {
                id: item.id,
                global_card_payment_type_id: null,
                amount: amountToPayCash.value,
            }
        }
        if(item.name === 'cortesia') {
            return {
                id: item.id,
                global_card_payment_type_id: null,
                amount: 0,
            }
        }
    });
    // validar que al recorrer globalPaymentTypes.amount sea igual al totalamount
    let totalFinal = 0;
    globalPaymentTypes.value.forEach((item) => {
        totalFinal += parseFloat(item.amount);
    });

    if(totalFinal != totalAmount.value) {
        valid.value = false;
        error.value = 'El monto total no coincide con el monto a pagar de los tipos de pago seleccionados';
        return;
    }

    if(purchaseStatus.value == 'retry') {
        valid.value = false;
        error.value = 'Estas en el proceso final de compra, si se require agregar otro asiento cancele la seleccion actual y reintente.';
        return;
    }

    const onSubmitConfirmDialog = document.getElementById('on-submit-confirm');
    onSubmitConfirmDialog.click();

}

const onSubmitConfirm = (isActive) => {

    loadingg.value = true;
    loading.value = true;
    const isTransfer = userToTransfer.value ? true : false;

    /* const seatsSelectedData = useFormInertia({
        purchase_type: purchaseType.value,
        event_id: props.event.id,
        cash_register_id: cashRegisterDataId.value,
        member_user_id: purchaseOnline.value ? props.user.id : (isTransfer ? userToTransfer.value : null),
        seller_user_id: sellerUserId.value,
        price_type_id: priceTypeId.value,
        seats: seatsSelected.value,
        amount_received: amountReceived.value,
        total_amount: totalAmount.value,
        total_returned: amountReturned.value,
        global_payment_types: globalPaymentTypes.value,
        is_online: purchaseOnline.value,
        serie_id: props.event.serie_id,
        is_transfer: isTransfer,
        user_to_transfer: userToTransfer.value,
    });

    seatsSelectedData.post(route('events.reserve-seats-to-buy'), {
        onSuccess: (response) => {
            if(!response.props.flash.error && purchaseOnline.value) {
                drawerPaymentState.value = true;
            }

            // crear el pdf como ticket de compra
            console.log('success');

        },
        onFinish: () => {
            isActive.value = false;
            loading.value = false;
            loadingg.value = false;
        }
    }); */

    const seatsSelectedData = {
    purchase_type: purchaseType.value,
    event_id: props.event.id,
    cash_register_id: cashRegisterDataId.value,
    member_user_id: purchaseOnline.value ? props.user.id : (isTransfer ? userToTransfer.value : null),
    seller_user_id: sellerUserId.value,
    price_type_id: priceTypeId.value,
    seats: seatsSelected.value,
    amount_received: amountReceived.value,
    total_amount: totalAmount.value,
    total_returned: amountReturned.value,
    global_payment_types: globalPaymentTypes.value,
    is_online: purchaseOnline.value,
    serie_id: props.event.serie_id,
    is_transfer: isTransfer,
    user_to_transfer: userToTransfer.value,
};

axios.post(route('events.reserve-seats-to-buy'), seatsSelectedData)
    .then(response => {
        if (response.data.success && purchaseOnline.value) {
            drawerPaymentState.value = true;
        }
        toast(response.data.message, {
            "theme": "auto",
            "type": "default",
            "dangerouslyHTMLString": true
        })

        console.log('success');
        console.log(response);
    })
    .catch(error => {
        console.error('Error: upps');
        console.error('Error:', error);
        toast(error.response.data.message, {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
    })
    .finally(() => {
        isActive.value = false;
        loading.value = false;
        loadingg.value = false;
    });


}

const rules = {
    required: value => !!value || 'Campo requerido',
    isNumber: value => !isNaN(value) || 'Debe ser un número',
    //validar si en el array de pagos selecionados solo existe un tipo de pago ya sea efectivo o tarjeta y validar que el monto sea igual al total
    isAmountToPay: value => {
        if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
            return parseFloat(value) == parseFloat(totalAmount.value) || 'El monto debe ser igual al total';
        }
        if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')) {
            return parseFloat(value) == parseFloat(totalAmount.value) || 'El monto debe ser igual al total';
        }
        return true;
    }
};

</script>

<template>
    <Head title="Evento" />
    <GuestLayout />
    <NavigationDrawer />
    <SuccessSession />
    <v-dialog max-width="700" max-height="300">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn id="seller-dialog" v-bind="activatorProps" variant="elevated" class="!tw-hidden" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
        </template>
        <template v-slot:default="{ isActive }">
            <v-card>
            <v-card-text class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-text-center">
                <h2 class="tw-bg-gray-100 tw-rounded-full tw-px-4 tw-py-1 tw-inline">Taquilla activa</h2>
                <h1 class="tw-font-bold tw-text-xl lg:tw-text-2xl tw-mt-3 tw-text-gray-600">Se debe abrir una caja para usar esta seccion como taquilla.</h1>
            </v-card-text>

            <v-card-actions>
                <Link
                    :href="route('ticket-offices.index')"
                    >
                    <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600 !tw-px-7 tw-mb-2 tw-mr-2" size="large" rounded="xl" @click="isActive.value = false">Abrir caja</v-btn>
                </Link>
            </v-card-actions>
            </v-card>
        </template>
    </v-dialog>

    <div class="tw-hidden lg:tw-block">
        <section class="tw-h-[250px]">
            <div class="tw-rounded-none tw-h-[300px] tw-relative tw-bg-white">
                <img class="tw-w-[1300px] tw-h-auto tw-absolute tw-right-0 tw-top-0 lg:tw-mt-[-80px]" src="../../../../../public/img/hero.svg" alt="">
                <div class="d-flex flex-column fill-height justify-center align-center text-white tw-relative tw-px-4 lg:tw-px-0">
                    <div class="tw-max-w-[90%] tw-mx-auto tw-flex tw-flex-col tw-w-full tw-gap-10">
                        <div class="tw-flex tw-items-end tw-gap-0 lg:tw-gap-3">
                            <h2 class="tw-font-bold tw-text-3xl lg:tw-text-5xl tw-text-gray-600 tw-max-w-2xl">
                                {{ event.name }}
                                <span>
                                    <svg class="tw-shrink-0 tw-size-10 tw-text-gray-500 tw-inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                                </span>
                            </h2>
                        </div>
                        <div class="tw-flex tw-items-center">
                            <ol class="tw-flex tw-items-center tw-whitespace-nowrap">
                                <li class="tw-inline-flex tw-items-center tw-py-1.5 tw-px-2">
                                    <a class="tw-flex tw-items-center tw-text-sm tw-text-gray-600 hover:tw-text-purple-600" href="/">
                                        Comienzo
                                    </a>
                                    <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </li>
                                <li class="tw-inline-flex tw-items-center tw-text-sm">
                                    <div class="[--placement:top-left] tw-relative tw-inline-flex">
                                    <div class="tw-flex tw-items-center gap-2 tw-bg-gray-200 tw-py-2 tw-px-4 tw-rounded-full tw-cursor-pointer tw-text-gray-600">
                                        <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                        </svg>
                                        <p>Pagina actual</p>
                                    </div>
                                    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 tw-w-48 tw-hidden tw-z-10 tw-transition-[margin,opacity] tw-opacity-0 tw-duration-300 tw-mb-2 tw-bg-white tw-shadow-md tw-rounded-lg tw-p-1 tw-space-y-0.5" role="menu">
                                        <a class="tw-flex tw-items-center tw-gap-x-3.5 tw-py-2 tw-px-3 tw-rounded-lg tw-text-sm tw-text-gray-800 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 disabled:tw-opacity-50 disabled:tw-pointer-events-none" href="#">
                                        Projects
                                        </a>
                                        <a class="tw-flex tw-items-center tw-gap-x-3.5 tw-py-2 tw-px-3 tw-rounded-lg tw-text-sm tw-text-gray-800 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 disabled:tw-opacity-50 disabled:tw-pointer-events-none" href="#">
                                        Preline
                                        </a>
                                    </div>
                                    </div>
                                    <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </li>
                                <li class="tw-inline-flex tw-items-center tw-py-1.5 tw-px-2">
                                    <a class="tw-flex tw-items-center tw-text-sm tw-text-gray-600 hover:tw-text-purple-600" href="/">
                                        Dashboard
                                    </a>
                                    <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500 tw-hidden lg:tw-block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </li>
                            </ol>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div v-if="seatsSelected.length > 0" @click="scrollTopaymentSection" class="tw-fixed tw-bottom-20 tw-right-3 tw-z-[60]">
        <div class="tw-flex tw-items-center tw-justify-center tw-cursor-pointer hover:tw-scale-110 tw-transition-transform tw-duration-700">
            <div class="tw-relative">
                <div class="tw-bg-gradient-to-r tw-from-green-500 tw-to-green-300 lg:tw-to-green-400 tw-w-[50px] tw-h-[50px] tw-rounded-full tw-flex tw-items-center tw-justify-center">
                    <span class="tw-animate-bounce material-symbols-outlined tw-z-20 tw-text-white tw-text-xl lg:tw-text-2xl">shopping_cart</span>
                </div>
            </div>
        </div>
    </div>

    <section class="tw-overflow-hidden tw-mt-0">
       <div class="lg:tw-hidden">
            <img class="tw-w-full" :src="`/storage/${event.global_image.file_path}`" alt="">
        </div>
    </section>

    <section class="tw-w-full tw-min-h-screen tw-bg-white tw-mt-[-37px] lg:tw-mt-0 tw-rounded-[35px] lg:tw-rounded-[0px] tw-relative tw-mb-20">
        <div class="max-w-full md:tw-max-w-[90%] tw-mx-auto tw-py-1 lg:tw-pb-7 tw-px-4 lg:tw-px-0">
            <main class="">

                <div class="tw-mt-10 tw-w-full tw-flex tw-flex-col lg:tw-flex-row tw-items-start tw-justify-between tw-gap-7 lg:tw-gap-10">
                    <div class="tw-w-full lg:tw-w-[70%] tw-relative lg:tw-min-h-[1000pxx]">
                        <div class="tw-space-y-5 lg:tw-space-y-8">
                            <Link :href="route('welcome')">
                                <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                    <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    Regresar al inicio
                                </div>
                            </Link >

                            <h2 class="tw-text-3xl tw-font-bold lg:tw-text-4xl tw-hidden lg:tw-block">{{ dateFormat(event.start_date) }}</h2>
                            <h2 class="tw-text-2xl tw-font-bold lg:tw-hidden">{{ event.name }}</h2>

                            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start lg:tw-items-center tw-gap-2 lg:tw-gap-5">
                                <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                    <span class="material-symbols-outlined tw-text-xl">location_on</span>El nido del halcon
                                </div>
                                <p class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-text-gray-800 tw-bg-gray-100 hover:tw-bg-gray-200">📅 | {{ dateFormat(event.start_date) }} </p>
                            </div>
                        </div>
                        <div class="tw-mt-7">
                            <div class="tw-flex tw-flex-col tw-gap-3 tw-justify-between mb-4">
                                <div>
                                    <p class="tw-font-bold tw-text-xl">Mapa de disponibilidad</p>
                                    <ErrorSession />

                                    <PaymentDrawer
                                        v-bind:purchaseType="purchaseType"
                                        v-bind:eventId="event.id"
                                        v-bind:cashRegisterId="cashRegisterDataId"
                                        v-bind:memberUserId="user.id"
                                        v-bind:sellerUserId="sellerUserId"
                                        v-bind:priceTypeId="priceTypeId"
                                        v-bind:seats="seatsSelected"
                                        v-bind:amountReceived="amountReceived"
                                        v-bind:totalAmount="totalAmount"
                                        v-bind:amountReturned="amountReturned"
                                        v-bind:globalPaymentTypes="globalPaymentTypes"
                                        v-bind:isOnline="purchaseOnline"
                                        v-bind:serieId="event.serie_id"
                                    />

                                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-6 tw-items-center tw-gap-3 tw-mt-7">
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-yellow-500 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">done_outline</span>
                                            </div>
                                            <p>Disponible</p>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-purple-500 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">star</span>
                                            </div>
                                            <p>Vendido</p>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-green-500 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">web_traffic</span>
                                            </div>
                                            <p>Seleccionado</p>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-pink-600 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">block</span>
                                            </div>
                                            <p>No vendible</p>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-gray-600 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">block</span>
                                            </div>
                                            <p>Inhabilitado</p>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                            <div class="tw-h-9 tw-w-full tw-bg-cyan-500 tw-flex tw-items-center tw-justify-center tw-rounded-full">
                                                <span class="material-symbols-outlined tw-text-sm tw-text-white">block</span>
                                            </div>
                                            <p>En transito</p>
                                        </div>
                                     </div>
                                </div>
                                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-w-full tw-gap-3 tw-my-3">
                                    <div class="tw-flex tw-items-center tw-gap-3 tw-flex-col md:tw-flex-row">
                                        <div class="tw-flex tw-items-center tw-gap-3">
                                            <v-btn @click="zoomIn" color="purple" variant="tonal" class="text-none" rounded="xl" size="large"><span class="material-symbols-outlined tw-text-2xl">add</span>zoom</v-btn>
                                            <v-btn @click="zoomOut" color="purple" variant="tonal" class="text-none" rounded="xl" size="large"><span class="material-symbols-outlined tw-text-2xl">remove</span>zoom</v-btn>
                                        </div>
                                    </div>
                                    <div class="tw-items-center tw-gap-2 tw-hidden lg:tw-flex tw-relative">
                                        <div class="tw-font-bold tw-text-3xl tw-text-center">
                                            {{ viewSelectedSection}}
                                        </div>
                                        <v-dialog max-width="800">
                                            <template v-slot:activator="{ props: activatorProps }">
                                                <div v-bind="activatorProps" class="!tw-absolute -tw-top-4 -tw-right-6 ">
                                                    <div class="tw-animate-ping tw-absolute tw-right-[2px] tw-top-[5px] tw-inline-flex tw-h-5 tw-w-5 tw-rounded-full tw-bg-purple-500 tw-opacity-80"></div>
                                                    <span class="material-symbols-outlined tw-text-2xl tw-text-purple-600">photo_library</span>
                                                </div>
                                            </template>
                                            <template v-slot:default="{ isActive }">
                                                <v-card :title="'Imagen de referencia para la ' + viewSelectedSection">
                                                <v-card-text>
                                                    <img class="tw-w-full tw-h-auto tw-rounded-xl" src="../../../../../public/img/zonashdx/zona-a-img.jpg" alt="zona hdx">
                                                </v-card-text>

                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="purple" rounded="xl" variant="tonal" class="text-none !tw-px-6" text="Cerrar" @click="isActive.value = false"></v-btn>
                                                </v-card-actions>
                                                </v-card>
                                            </template>
                                        </v-dialog>
                                    </div>
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <v-btn @click="resetZoom" color="purple" variant="tonal" class="text-none" rounded="xl" size="large"><span class="material-symbols-outlined tw-text-2xl">my_location</span>reset</v-btn>

                                        <v-btn @click="selectZones" color="purple" variant="tonal" class="text-none" rounded="xl" size="large"><span class="material-symbols-outlined tw-text-2xl">location_on</span>zonas</v-btn>
                                    </div>
                                </div>

                                <div class="tw-inline-flex tw-items-center tw-gap-2 lg:tw-hidden tw-justify-center">
                                    <div class="tw-font-bold tw-text-2xl tw-text-center tw-inline-flex tw-relative">
                                        {{ viewSelectedSection}}
                                        <v-dialog max-width="800">
                                        <template v-slot:activator="{ props: activatorProps }">
                                            <div v-bind="activatorProps" class="!tw-absolute -tw-top-4 -tw-right-5 ">
                                                <span class="material-symbols-outlined tw-text-2xl tw-text-purple-600">photo_library</span>
                                            </div>
                                        </template>
                                        <template v-slot:default="{ isActive }">
                                            <v-card :title="'Imagen de referencia para la ' + viewSelectedSection">
                                            <v-card-text>
                                                <img class="tw-w-full tw-h-auto tw-rounded-xl" src="../../../../../public/img/zonashdx/zona-a-img.jpg" alt="zona hdx">
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="purple" rounded="xl" variant="tonal" class="text-none !tw-px-6" text="Cerrar" @click="isActive.value = false"></v-btn>
                                            </v-card-actions>
                                            </v-card>
                                        </template>
                                    </v-dialog>
                                    </div>
                                </div>

                                <div class="tw-flex tw-h-[400px] tw-cursor-grab lg:tw-h-[500px] tw-items-center tw-justify-center tw-overflow-hidden tw-bordertw-mt-5 tw-gap-3 tw-relative">
                                    <div class="tw-size-[100px] lg:tw-size-36 tw-border tw-border-gray-300 tw-absolute tw-top-0 tw-left-0 tw-z-20 tw-bg-white tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                        <img id="stadium-hdx-img" class="tw-size-20 lg:tw-size-32 tw-rotate-0 tw-transition-all tw-duration-1000" src="../../../../../public/img/stadium-hdx-img.svg" alt="">
                                    </div>
                                    <div v-if="isSvgVisible">
                                        <!-- <StadiumSVG @handle-section-click="handleSectionClick" /> -->
                                        <EstadioHdx  @handle-section-click="handleSectionClick"/>
                                    </div>
                                   <!--  <div v-if="selectedSection == 'zonaF'" class="">
                                        <FZona @add-seat="addSeat" v-bind:seats="props.a_zone" v-bind:seatsSelected="seatsSelected" />
                                    </div> -->
                                    <div v-if="selectedSection == 'zonaA'" class="">
                                        <ZonaA @add-seat="addSeat" v-bind:seats="props.a_zone" v-bind:seatsSelected="seatsSelected" />
                                    </div>
                                    <div v-if="selectedSection == 'zonaC'" class="">
                                        <ZonaC @add-seat="addSeat" v-bind:seats="props.c_zone" v-bind:seatsSelected="seatsSelected" />
                                    </div>
                                    <div v-if="selectedSection == 'zonaF'" class="">
                                        <ZonaF @add-seat="addSeat" v-bind:seats="props.f_zone" v-bind:seatsSelected="seatsSelected" />
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="tw-w-full lg:tw-w-[30%] tw-sticky tw-top-20 lg:tw-mt-[-100px]">
                        <h3 class="tw-text-2xl tw-font-bold">Asientos seleccionados</h3>
                        <h4 class="tw-text-sm mt-1">📍 El nido del halcon | Xalapa Ver.</h4>
                        <div class="tw-h-auto lg:tw-h-[650px] tw-w-full mt-3  tw-rounded-2xl lg:tw-overflow-y-scroll tw-shadow-lg">
                            <div class="tw-relative tw-flex tw-flex-col tw-bg-white tw-rounded-xl tw-pointer-events-auto">
                                <div class="tw-relative tw-overflow-hidden tw-min-h-32 tw-bg-gray-800 tw-text-center tw-rounded-xl lg:tw-rounded-tr-none">
                                    <!-- SVG Background Element -->
                                    <figure class="tw-absolute tw-inset-x-0 tw-bottom-0 -tw-mb-px">
                                    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                                        <path fill="currentColor" class="tw-fill-white" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                                    </svg>
                                    </figure>
                                    <!-- End SVG Background Element -->
                                </div>

                                <div class="tw-relative tw-z-10 -tw-mt-12">
                                    <!-- Icon -->
                                    <span class="tw-mx-auto tw-flex tw-justify-center tw-items-center tw-size-[62px] tw-rounded-full tw-border tw-border-gray-200 tw-bg-white tw-text-gray-700 tw-shadow-sm">
                                    <svg class="tw-shrink-0 tw-size-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"></path>
                                        <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"></path>
                                    </svg>
                                    </span>
                                    <!-- End Icon -->
                                </div>
                            </div>
                            <div class="tw-px-5 tw-relative tw-flex tw-flex-col-reverse">
                                <div v-if="seatsSelected.length == 0" class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-7">
                                    <p class="tw-w-full tw-text-center tw-text-xs tw-p-3 tw-rounded-full tw-bg-gray-100 tw-mt-5">No se han selecionado asientos</p>
                                    <img class="tw-w-60 tw-h-auto" src="../../../../../public/img/seats-no-selected-img.svg" alt="">
                                </div>
                                <div v-if="seatsSelected.length > 0" class="">
                                    <div ref="paymentSection" class="tw-w-full ">
                                        <h3 class="tw-font-bold tw-text-lg tw-text-center tw-my-3">Resumen de compra</h3>
                                        <v-expansion-panels v-model="panel" class="" multiple>
                                            <v-expansion-panel>
                                                <v-expansion-panel-title expand-icon="mdi-menu-down">
                                                  asientos seleccionados
                                                </v-expansion-panel-title>
                                                <v-expansion-panel-text>
                                                    <div>
                                                        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                                                            <thead class="tw-bg-gray-100">
                                                                <tr>
                                                                <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                        zona
                                                                    </span>
                                                                </th>

                                                                <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                        Fila
                                                                    </span>
                                                                </th>

                                                                <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                        asiento
                                                                    </span>
                                                                </th>

                                                                <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    precio
                                                                    </span>
                                                                </th>
                                                                <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                        Accion
                                                                    </span>
                                                                </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody class="tw-divide-y tw-divide-gray-200">
                                                                <tr v-for="seat in seatsSelected" :key="seat.seat_catalogue.code">
                                                                <td class="tw-size-px tw-whitespace-nowrap tw-p-2">
                                                                    <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.zone }}</span>
                                                                </td>
                                                                <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                    <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.row }}</span>
                                                                </td>
                                                                <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                    <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.seat }}</span>
                                                                </td>
                                                                <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                    <span class="tw-text-sm tw-text-green-600">
                                                                        <div v-for="priceType in seat.price_types" :key="priceType.id">
                                                                            <div v-if="viewVendorTopics(user_roles)">
                                                                                {{ priceType.name }}: {{ formatPrice(priceType.pivot.price) }}
                                                                            </div>
                                                                            <div v-else>
                                                                                <span v-if="priceType.name === 'regular'">
                                                                                    {{ formatPrice(priceType.pivot.price) }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </span>
                                                                </td>
                                                                <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                    <span @click="addSeat(seat)" class="material-symbols-outlined tw-text-xl tw-text-red-500 tw-cursor-pointer">delete</span>
                                                                </td>
                                                                </tr>
                                                            </tbody>
                                                            </table>
                                                    </div>
                                                </v-expansion-panel-text>
                                            </v-expansion-panel>

                                            <v-expansion-panel>
                                                <v-expansion-panel-title expand-icon="mdi-menu-down">
                                                   Tipos de pago
                                                </v-expansion-panel-title>
                                                <v-form v-model="form" @submit.prevent="onSubmit" lazy-validation>
                                                    <v-expansion-panel-text>
                                                    <v-select
                                                        v-if="viewVendorTopics(user_roles)"
                                                        color="purple"
                                                        label="selecciona el tipo de pago"
                                                        hint="Selecciona el tipo de pago"
                                                        :item-props="globalPayementTypeProps"
                                                        :items="global_payment_types"
                                                        chips
                                                        multiple
                                                        v-model="paymentTypesSelected"
                                                        :rules="[rules.required]"
                                                    ></v-select>

                                                    <div v-if="paymentTypesSelected.some(type => type.name === 'tarjeta')">
                                                        <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full tw-bg-purple-200 tw-text-purple-600 tw-text-center tw-mb-2">
                                                        Complemento para pago con tarjeta
                                                        </h4>
                                                        <v-select
                                                        color="purple"
                                                        clearable
                                                        label="seleciona el tipo de tarjeta"
                                                        hint="Selecciona el tipo de tarjeta"
                                                        :item-props="globalCardPayementTypeProps"
                                                        :items="global_card_payment_types"
                                                        v-model="cardPaymentTypesSelected"
                                                        :rules="[rules.required]"
                                                        ></v-select>
                                                        <v-text-field
                                                            v-if="viewVendorTopics(user_roles)"
                                                            label="Monto a pagar para tarjeta"
                                                            color="purple"
                                                            clearable
                                                            hint="Monto recibido por el cliente"
                                                            v-model="amountToPayCard"
                                                            :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                        ></v-text-field>
                                                        <v-text-field
                                                            v-else
                                                            label="Monto a pagar para tarjeta"
                                                            color="purple"
                                                            hint="Monto recibido por el cliente"
                                                            readonly
                                                            v-model="amountToPayCard"
                                                            :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                        ></v-text-field>
                                                    </div>

                                                    <div v-if="paymentTypesSelected.some(type => type.name === 'efectivo')">
                                                        <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full tw-bg-green-200 tw-text-green-600 tw-text-center tw-mb-2">
                                                        Complemento para pago con efectivo
                                                        </h4>
                                                        <v-text-field
                                                        label="Monto recibido para efectivo"
                                                        color="purple"
                                                        clearable
                                                        hint="Monto recibido por el cliente"
                                                        v-model="amountReceivedCash"
                                                        :rules="[rules.required, rules.isNumber]"
                                                        ></v-text-field>

                                                        <v-text-field
                                                        label="Monto a pagar para efectivo"
                                                        color="purple"
                                                        clearable
                                                        hint="Monto a pagar por el cliente"
                                                        v-model="amountToPayCash"
                                                        :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                        ></v-text-field>
                                                    </div>

                                                    <p v-if="!valid" class="tw-py-2 tw-px-4 tw-bg-red-100 tw-border-l-4 tw-border-l-red-500 tw-text-red-500 tw-text-xs tw-my-4">{{ error }}</p>

                                                    <div class="tw-my-5">
                                                        <v-radio-group :disabled="!form" inline label="Tipo de compra a realizar" v-model="purchaseType">
                                                            <v-radio
                                                            v-for="(type, index) in purchase_types"
                                                            :key="index"
                                                            :color="'purple'"
                                                            :label="type"
                                                            :value="type"
                                                            ></v-radio>
                                                        </v-radio-group>

                                                        <div v-if="purchaseStatus == 'retry'">
                                                            <p class="tw-py-2 tw-px-4 tw-bg-red-100 tw-border-l-4 tw-border-l-red-500 tw-text-red-500 tw-text-xs tw-my-4">Estas en el proceso final de compra, si se require agregar otro asiento cancele la seleccion actual y reintente.</p>
                                                        </div>
                                                        <div v-if="purchaseType == 'partido'">
                                                            <p class="tw-py-2 tw-px-4 tw-bg-green-100 tw-border-l-4 tw-border-l-green-500 tw-text-green-500 tw-text-xs tw-my-4">Los boletos adquiridos seran validos solo para un partido.</p>
                                                        </div>
                                                        <div v-else-if="purchaseType == 'serie'">
                                                            <p class="tw-py-2 tw-px-4 tw-bg-purple-100 tw-border-l-4 tw-border-l-purple-500 tw-text-purple-500 tw-text-xs tw-my-4">Los boletos adquiridos seran validos solo para dos partidos del mismo evento.</p>
                                                        </div>

                                                        <p class="tw-opacity-50 tw-text-right tw-mb-3 tw-text-xs">Subtotal (tipos de precios selecionados): {{ formatPrice(totalAmount) }}</p>
                                                        <p class="tw-font-semibold tw-text-right tw-mb-3">Total: {{ formatPrice(totalAmount) }}</p>
                                                        <v-btn
                                                            :disabled="!form"
                                                            :loading="loadingg"
                                                            type="submit"
                                                            rounded="xl" size="large" block
                                                            class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400"
                                                        >
                                                            <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos
                                                        </v-btn>
                                                        <v-btn
                                                            @click="selectZones"
                                                            rounded="xl" size="large" block
                                                            class="text-none !tw-text-white !tw-bg-gradient-to-b !tw-from-red-600 !tw-to-red-400 tw-mt-5"
                                                        >
                                                            <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">delete</span>Cancelar seleccion
                                                        </v-btn>

                                                        <!-- <v-dialog max-width="700" v-if="viewVendorTopics(user_roles)">
                                                            <template v-slot:activator="{ props: activatorProps }">
                                                                <v-btn :disabled="!form" type="submit" :loading="loadingg" v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                            </template>
                                                            <template v-slot:default="{ isActive }">
                                                                <v-card title="¿Estas seguro de realizar la compra?">
                                                                <v-card-text>
                                                                    <iframe src="http://127.0.0.1:8000/img/ticket_test.pdf" width="100%" height="400"></iframe>
                                                                </v-card-text>

                                                                <v-card-actions>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="red" rounded="xl" variant="tonal" class="text-none" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                </v-card-actions>
                                                                </v-card>
                                                            </template>
                                                        </v-dialog> -->
                                                        <v-dialog max-width="600">
                                                            <template v-slot:activator="{ props: activatorProps }">
                                                                <v-btn id="on-submit-confirm" v-bind="activatorProps" variant="elevated" class="!tw-hidden text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                            </template>
                                                            <template v-slot:default="{ isActive }">
                                                                <v-card>
                                                                <v-card-text>
                                                                    <p class="tw-font-bold tw-text-sm lg:tw-text-xl tw-text-gray-700">¿Estas seguro de realizar la compra?</p>
                                                                    <v-container v-if="viewVendorTopics(user_roles)">
                                                                        <v-row>
                                                                            <v-col xs12 sm6 md4>
                                                                                <v-autocomplete
                                                                                    v-model="userToTransfer"
                                                                                    clearable
                                                                                    color="purple"
                                                                                    chips
                                                                                    label="Buscar usuario para asignar la compra"
                                                                                    hint="El usuario que se seleccione tendra sus boletos en su applicacion."
                                                                                    persistent-hint=""
                                                                                    :items="users_list"
                                                                                    variant="solo-filled"
                                                                                    item-title="name"
                                                                                    item-value="value"
                                                                                ></v-autocomplete>
                                                                            </v-col>
                                                                        </v-row>
                                                                    </v-container>
                                                                    <p class="tw-opacity-50 tw-mt-3 tw-text-xs lg:tw-text-base">Subtotal (precio en compra): {{ formatPrice(totalAmount) }}</p>
                                                                    <p class="tw-font-semibold tw-text-gray-700">Total: {{ formatPrice(totalAmount) }}</p>
                                                                </v-card-text>

                                                                <v-card-actions class="tw-mb-2 tw-mr-2">
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="red" rounded="xl" variant="tonal" class="text-none" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                    <v-btn :loading="loading" rounded="xl" variant="elevated" class="text-none !tw-bg-green-500 !tw-text-white tw-mb-2 !tw-px-4" text="Reservar y comprar" @click="onSubmitConfirm(isActive)"></v-btn>
                                                                </v-card-actions>

                                                                </v-card>
                                                            </template>
                                                        </v-dialog>

                                                    </div>
                                                </v-expansion-panel-text>


                                                </v-form>
                                            </v-expansion-panel>
                                        </v-expansion-panels>

                                        <div class="tw-my-5">
                                            <div v-if="viewVendorTopics(user_roles)" class="text-center">
                                                <v-snackbar
                                                    v-model="snackbar"
                                                    variant="elevated"
                                                    color="white"
                                                    multi-line
                                                    timeout="-1"
                                                    location="top"
                                                    class="!tw-w-full !tw-m-0 !tw-rounded-none"
                                                    min-width="100%"
                                                    min-height="90px"
                                                    rounded="0"
                                                >
                                                <div class="tw-flex tw-items-center tw-justify-center tw-gap-5 tw-max-w-5xl tw-w-full tw-h-full tw-mx-auto">
                                                    <v-text-field
                                                        label="Monto total"
                                                        variant="outlined"
                                                        color="purple"
                                                        clearable
                                                        hint="Monto total a pagar"
                                                        persistent-hint=""
                                                        rounded="lg"
                                                        v-model.number="totalAmount"
                                                        :error-messages="paymentFileds.total.errorMessage.value"
                                                        readonly
                                                    ></v-text-field>
                                                    <v-text-field
                                                        label="Monto recibido"
                                                        variant="outlined"
                                                        color="purple"
                                                        clearable
                                                        hint="Monto recibido por el cliente"
                                                        persistent-hint=""
                                                        rounded="lg"
                                                        v-model="amountReceived"
                                                        :error-messages="paymentFileds.amount_received.errorMessage.value"
                                                        readonly
                                                    ></v-text-field>
                                                    <v-text-field
                                                        label="Cambio"
                                                        variant="outlined"
                                                        color="purple"
                                                        clearable
                                                        hint="Cambio a devolver al cliente"
                                                        persistent-hint=""
                                                        rounded="lg"
                                                        v-model.number="amountReturned"
                                                        :error-messages="paymentFileds.amount_returned.errorMessage.value"
                                                        readonly
                                                    ></v-text-field>
                                                </div>

                                                <template v-slot:actions>
                                                    <v-btn
                                                    color="red"
                                                    variant="tonal"
                                                    @click="snackbar = false"
                                                    >
                                                    Cerrar
                                                    </v-btn>
                                                </template>
                                                </v-snackbar>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>

        </div>
    </section>

    <Footer />
</template>

<style scoped>
.v-parallax {
    z-index: -10;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.tw-animate-spin {
        animation: tw-spin 2s linear infinite;
}
@keyframes tw-bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}

.tw-animate-bounce {
  animation: tw-bounce 1s infinite;
}

</style>
