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
                    custom_id: props.memberUserId ? props.memberUserId : null,
                }
            ]
          })
        },
        onApprove: async (data, actions) => {
            return actions.order.capture().then(details => {
                const transaction = {
                    source: 'paypal',

                    order_id: data.orderID ?? null,
                    payment_id: data.paymentID ?? null,
                    payer_id: data.payerID ?? null,

                    status: details?.status ?? null,
                    intent: details?.intent ?? null,

                    amount: details?.purchase_units?.[0]?.amount?.value ?? null,
                    currency: details?.purchase_units?.[0]?.amount?.currency_code ?? null,

                    capture_id: details?.purchase_units?.[0]?.payments?.captures?.[0]?.id ?? null,

                    payer_email: details?.payer?.email_address ?? null,
                    payee_email: details?.purchase_units?.[0]?.payee?.email_address ?? null,

                    create_time: details?.create_time ?? null,
                    update_time: details?.update_time ?? null,
                }
                confirmSeatsPurchase(transaction);
            });
        },
        onCancel: (data) => {
            router.visit('/eventos');
            drawerPaymentState.value = false;
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
        router.visit(route('events.index'));
    }
}

const confirmSeatsPurchase = (transaction = {}) =>{

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
        sale_debtor: props.saleDebtorData,
        online_payment_transaction: transaction,
    }

    axios.post(route('events.confirm-seats-purchase'), seatsSelectedData)
        .then((response) => {
            if(response.data.success) {
                toast(response.data.message, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
                })
                router.visit(route('events.success'));
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
      <v-navigation-drawer v-model="drawerPaymentState" temporary width="500">
        <div>
            <div class="tw-relative tw-flex tw-flex-col tw-bg-white tw-pointer-events-auto">
                <div class="tw-relative tw-overflow-hidden tw-min-h-32 tw-bg-gray-200 tw-text-center">
                    <figure class="tw-absolute tw-inset-x-0 tw-bottom-0 -tw-mb-px">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                            <path fill="currentColor" class="tw-fill-white" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                        </svg>
                    </figure>
                </div>

                <div class="tw-relative tw-z-10 -tw-mt-12">
                    <span class="tw-mx-auto tw-flex tw-justify-center tw-items-center tw-size-[62px] tw-rounded-full tw-border tw-border-gray-200 tw-bg-white tw-text-gray-700 tw-shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-size-7 tw-stroke-slate-800" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"/></svg>
                    </span>
                </div>
            </div>
            <div class="tw-p-4">
                <CountdownTimer :initialMinutes="10" />

                <!-- <CyberSoruce  :seats="seats" :client-reference-information="{ code: ticketOfficeId}" :order-information-amount-details="{totalAmount: totalAmount, currency: currency}"
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
                    @response-payment="responseCyberSource" @cancel-payment="cancelPayment"/> -->

                <div id="paypal-button-container" class="tw-mt-4"></div>
                <div class="tw-mt-10">
                    <div v-if="loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-5 tw-animate-pulse">
                        <p class="tw-font-bold tw-text-xs lg:tw-text-base">Completando compra en el sistema...</p>
                    </div>
                </div>
            </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>

<style scoped>
.v-navigation-drawer--temporary.v-navigation-drawer--active {
        width: 85% !important;
    }

    @media (min-width: 768px) {
        .v-navigation-drawer--temporary.v-navigation-drawer--active {
            width: 40% !important;
        }
    }
</style>
