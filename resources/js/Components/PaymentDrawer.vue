<script setup>
import { drawerPaymentState } from '@/composables/drawersStates';
import { loadScript } from '@paypal/paypal-js';
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import CountdownTimer from '@/Components/CountdownTimer.vue';
import CyberSoruce from '@/Components/CyberSoruce.vue';
import axios from 'axios';
import { toast } from 'vue3-toastify'


const CLIENT_ID = 'AVvNWWNci4r1r8VQUZ919IvcgLmDbPHCSktDNXcwQMaNHNdfqMCDKWdsR4SDs93oNYJQYw6q87Z4mHql';
const SECRET_kEY = 'EAzjdcBn2Vp2CtTTYZtQfyiQuTLzZf4tQ2OQT_TmOytyz_m3uGW-DH9gYYduccLwHUxeLfgU_p-LPZrd';
const currency = 'MXN';

const loading  = ref(false);

const props = defineProps({
    purchaseType: {
        type: String,
        required: true,
    },
    stadiumId: {
        type: Number,
        required: true,
    },
    ticketOfficeId: {
        type: Number,
        required: true,
    },
    eventId: {
        type: Number,
        required: true,
    },
    cashRegisterId: {
        type: Number,
        required: true,
    },
    memberUserId: {
        type: Number,
        required: false,
    },
    sellerUserId: {
        type: Number,
        required: true,
    },
    priceTypeId: {
        type: Number,
        required: true,
    },
    seats: {
        type: Array,
        required: true,
    },
    amountReceived: {
        type: Number,
        required: true,
    },
    totalAmount : {
        type: Number,
        required: true,
    },
    amountReturned: {
        type: Number,
        required: true,
    },
    paymentInInstallments: {
        type: Object,
        required: false,
    },
    globalPaymentTypes: {
        type: Array,
        required: true,
    },
    isOnline: {
        type: Boolean,
        required: true,
    },
    serieId: {
        type: Number,
        required: true,
    },
    finalPromotion: {
        type: Object,
        required: false,
    },
    saleDebtorData: {
        type: Object,
        required: false,
        default: null,
    },
});

onMounted(async () => {
  try {
    const paypal = await loadScript({ clientId: CLIENT_ID, currency: currency });
    if (paypal) {
      paypal.Buttons({
        style: {
          shape: 'rect',
          layout: 'vertical',
          color: 'gold',
          label: 'paypal'
        },
        createOrder: async (data, actions) => {

          return actions.order.create({
            purchase_units: [
                {
                    amount: {
                        value: props.totalAmount,
                        currency_code: currency
                    },
                    custom_id: props.ticketOfficeId,
                }
            ]
          })
        },
        onApprove: async (data, actions) => {
            return actions.order.capture().then(details => {
                confirmSeatsPurchase();
            });
        },
        onCancel: (data) => {
            router.visit('/eventos');
        },
        onError: (err) => {
            console.error('An error occurred during the transaction:', err);
        }
      }).render('#paypal-button-container');
    }
  } catch (error) {
    console.error('failed to load the PayPal JS SDK script', error);
  }
})

const responseCyberSource = (value) => {
    if(value['status']){
        confirmSeatsPurchase('Cyber Source');
    }
};

const cancelPayment = (data) => {
    if(data['status']){
        router.visit('/eventos');
    }
}

const confirmSeatsPurchase = () =>{

    loading.value = true;

    const seatsSelectedData = {
        purchase_type: props.purchaseType,
        stadium_id: props.stadiumId,
        ticket_office_id: props.ticketOfficeId,
        event_id: props.eventId,
        cash_register_id: props.cashRegisterId,
        member_user_id: props.memberUserId,
        seller_user_id: props.sellerUserId,
        price_type_id: props.priceTypeId,
        seats: props.seats,
        amount_received: props.amountReceived,
        total_amount: props.totalAmount,
        total_returned: props.amountReturned,
        global_payment_types: props.globalPaymentTypes,
        is_online: props.isOnline,
        serie_id: props.serieId,
        sale_debtor: props.saleDebtorData
    }

    axios.post(route('events.confirm-seats-purchase'), seatsSelectedData)
        .then((response) => {
            if(response.data.success) {
                toast(response.data.message, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
                })
                router.visit('/pago-exitoso');
            }
        })
        .catch((error) => {
            toast(error.data.message, {
                "theme": "auto",
                "type": "error",
                "autoClose": 10000,
                "dangerouslyHTMLString": true
            })
        })
        .finally(() => {
            loading.value = false;
            drawerPaymentState.value = false;
        });
}

</script>
<template>
  <div class="tw-z-50">
    <v-layout >
      <v-navigation-drawer v-model="drawerPaymentState" temporary>
        <div>
            <div class="tw-relative tw-flex tw-flex-col tw-bg-white tw-pointer-events-auto">
                <div class="tw-relative tw-overflow-hidden tw-min-h-32 tw-bg-gray-800 tw-text-center">
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
            <div class="tw-p-4">

                <CyberSoruce  :seats="seats" :client-reference-information="{ code: ticketOfficeId}" :order-information-amount-details="{totalAmount: totalAmount, currency: currency}"
                :orderInformationBillTo="{
                    firstName: 'RTS',
                    lastName: 'VDP',
                    address1: '201 S. Division St.',
                    locality: 'Ann Arbor',
                    administrativeArea: 'MI',
                    postalCode: '48104-2201',
                    country: 'US',
                    district: 'MI',
                    buildingNumber: '123',
                    email: 'test@cybs.com',
                    phoneNumber: '999999999'
                }"
                @response-payment="responseCyberSource" @cancel-payment="cancelPayment"/>

                <div id="paypal-button-container"></div>
                <div class="tw-mt-10">
                    <CountdownTimer :initialMinutes="10" />
                    <div v-if="loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-5 tw-animate-pulse">
                        <p class="tw-font-bold tw-text-xs lg:tw-text-base">Completando compra en el sistema...</p>
                        <iframe class="tw-size-32 lg:tw-size-40 tw-rotate-45" src="https://lottie.host/embed/bf6d5e1b-537a-436b-8464-3d074f070d76/SAdIq1oqT7.json"></iframe>
                    </div>
                </div>
            </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>
<style scoped>
</style>
