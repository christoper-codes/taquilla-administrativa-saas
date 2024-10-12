<script setup>
import { drawerPaymentState } from '@/composables/drawersStates';
import { loadScript } from '@paypal/paypal-js';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const CLIENT_ID = 'AVvNWWNci4r1r8VQUZ919IvcgLmDbPHCSktDNXcwQMaNHNdfqMCDKWdsR4SDs93oNYJQYw6q87Z4mHql';
const SECRET_kEY = 'EAzjdcBn2Vp2CtTTYZtQfyiQuTLzZf4tQ2OQT_TmOytyz_m3uGW-DH9gYYduccLwHUxeLfgU_p-LPZrd';
const currency = 'MXN';

const props = defineProps({
    seatsSelected: {
        type: Array,
        required: true,
    },
    total : {
        type: Number,
        required: true,
    }
})

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
                        value: props.total,
                        currency_code: 'MXN'
                    },
                    custom_id: 123,
                }
            ]
          })
        },
        onApprove: async (data, actions) => {
            return actions.order.capture().then(details => {
                document.getElementById('result-message').innerText = `Transaction completed by ${details.payer.name.given_name}`;
                console.log('Transaction details:', details);
                router.visit('/pago-exitoso');
            });
        },
        onCancel: (data) => {

            document.getElementById('result-message').innerText = 'Payment was cancelled.';
            router.visit('/eventos');

        },
        onError: (err) => {
            console.error('An error occurred during the transaction:', err);
            document.getElementById('result-message').innerText = 'An error occurred during the transaction.';
        }
      }).render('#paypal-button-container');
    }
  } catch (error) {
    console.error('failed to load the PayPal JS SDK script', error);
  }
})

</script>

<template>
  <div>
    <div class="" @click="drawerPaymentState = !drawerPaymentState">
        <v-btn variant="elevated" class="text-none !tw-bg-green-500 !tw-text-white" size="large" rounded="lg" block=""><span class="material-symbols-outlined tw-text-xl">shopping_bag</span>Completar pedido</v-btn>
    </div>

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
                <div id="paypal-button-container"></div>
                <p id="result-message"></p>
            </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>

<style scoped>

    .v-navigation-drawer--temporary.v-navigation-drawer--active {
        width: 75% !important;
    }

    @media (min-width: 768px) {
        .v-navigation-drawer--temporary.v-navigation-drawer--active {
            width: 35% !important;
        }
    }
</style>
