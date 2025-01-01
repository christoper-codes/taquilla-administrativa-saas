<script setup>
import { drawerPaymentState } from '@/composables/drawersStates';
import { loadScript } from '@paypal/paypal-js';
import { onMounted, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CountdownTimer from '@/Components/CountdownTimer.vue';
import { useForm as useFormInertia} from '@inertiajs/vue3';
import axios from 'axios';
import { toast } from 'vue3-toastify'
import { jwtDecode } from 'jwt-decode';


const CLIENT_ID = 'AVvNWWNci4r1r8VQUZ919IvcgLmDbPHCSktDNXcwQMaNHNdfqMCDKWdsR4SDs93oNYJQYw6q87Z4mHql';
const SECRET_kEY = 'EAzjdcBn2Vp2CtTTYZtQfyiQuTLzZf4tQ2OQT_TmOytyz_m3uGW-DH9gYYduccLwHUxeLfgU_p-LPZrd';
const currency = 'MXN';

const loading  = ref(false);

const props = defineProps({
    purchaseType: {
        type: String,
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
})

const user = usePage().props.auth.user;
let payButton;
let flexResponse;
let expMonth;
let expYear;
let flex;

let micro;
let errorsOutput;
var loadingPaymentFlex = ref(false);
var contextCapture = ref(false);
var disabledButtonBuyFlex = ref(false);
var state = ref('');
var municipality = ref('');
var cp = ref('');
var numPhone = ref('');
var address = ref('');
const generalError = ref('');
var dialog = ref(false);



const script = document.createElement('script');
script.src = "https://flex.cybersource.com/microform/bundle/v2/flex-microform.min.js";
script.async = true;

script.onload = () => {
  console.log("Script de Microform cargado correctamente");
};

script.onerror = () => {
  console.error("Error al cargar el script de Microform");
};

document.body.appendChild(script);

const generaateContextCapture = async () => {

    await axios.get(route('capture.context')).
        then((response)=>{
            //if (response.data.data[0]) {
                console.log(response.data.datos[0]);
                //console.log(response.data.datos.jti);
                initMicroform(response.data.datos[0])
            //}
        }).
        catch((error) => {
            console.log(error);
        }
    );
}


const initMicroform = (jwt) => {
    try {

        state.value = ''
        municipality.value = ''
        cp.value = ''
        address.value = ''
        numPhone.value = ''
        payButton = document.querySelector('#pay-button');
        flexResponse = document.querySelector('#flexresponse');
        expMonth = document.querySelector('#expMonth');
        expYear = document.querySelector('#expYear');
        errorsOutput = document.querySelector('#errors-output');

        const estilosMicroforma = {
            'input': {
                'font-size': '14px',
                'font-family': 'helvetica, tahoma, calibri, sans-serif',
                'color': '#811484'
              },
              ':focus': { 'color': 'blue' },
              ':disabled': { 'cursor': 'not-allowed' },
              'valid': { 'color': '#3c763d' },
              'invalid': { 'color': '#a94442' }
        };



        flex = new Flex(jwt);
        micro = flex.microform({styles:estilosMicroforma});
        var number = micro.createField("number", { placeholder: "Número de tarjeta" });
        var securityCode = micro.createField("securityCode", { placeholder: "CVV" });
        number.load("#number-container");
        securityCode.load("#securityCode-container");

        contextCapture.value = !contextCapture.value

    } catch (error) {
        console.log(error);
    }
}

var deecodedjwt = ref();

const buy = async () => {
    console.log('entro al buy')
    generalError.value = '';
    loadingPaymentFlex.value = !loadingPaymentFlex.value;
    disabledButtonBuyFlex.value = !disabledButtonBuyFlex.value

    var options = {
        expirationMonth: document.querySelector('#expMonth').value,
        expirationYear: document.querySelector('#expYear').value
    };

    if (!micro) {
        console.error('Microform no inicializado');
        return;
    }

    micro.createToken(options, function (err, token) {
        if (err) {
            console.log(err);
            loadingPaymentFlex.value = !loadingPaymentFlex.value;
            disabledButtonBuyFlex.value = !disabledButtonBuyFlex.value
            generalError.value = err.message;
            if (err.reason == "CREATE_TOKEN_CAPTURE_CONTEXT_USED_TOO_MANY_TIMES") {
                dialog.value = !dialog.value
            }
        } else {
            console.log(JSON.stringify(token));
            flexResponse.value = JSON.stringify(token);
            deecodedjwt.value = jwtDecode(JSON.stringify(token))
            console.log(deecodedjwt.value);
            paymentFlexMicroForm(deecodedjwt.value);
        }
    });
}


const paymentFlexMicroForm = async (jwt) => {

    const data = {
        amount: props.totalAmount,
        token: jwt.jti,
        expirationMonth: jwt.content.paymentInformation.card.expirationMonth.value,
        expirationYear: jwt.content.paymentInformation.card.expirationYear.value,
        cardType: jwt.content.paymentInformation.card.number.detectedCardTypes[0],
        user: user,
        state: state.value,
        municipality: municipality.value,
        cp: cp.value,
        numPhone: numPhone.value,
        address: address.value
    };

    await axios.post(route('payment.payed.aut.setup'), data)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            generalError.value = 'Ocurrió un error al enviar el formulario. Intenta nuevamente.';
            disabledButtonBuyFlex.value = !disabledButtonBuyFlex.value
            loadingPaymentFlex.value = !loadingPaymentFlex.value;
            console.log(error);
        }
    );

}

const xy = ()=>{
    if (response.data.response[1] && response.data.response[2]['X-RequestID']) {
                const seatsSelectedData = {
                    purchase_type: props.purchaseType,
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
                }
                axios.post(route('events.confirm-seats-purchase'), seatsSelectedData)
                    .then((response) => {
                        if(response.data.success) {
                            toast(response.data.message, {
                                "theme": "auto",
                                "type": "default",
                                "dangerouslyHTMLString": true
                            })
                            disabledButtonBuyFlex.value = !disabledButtonBuyFlex.value
                            router.visit('/pago-exitoso');
                        }
                    }
                ).catch((error) => {
                    toast(error.response.data.message, {
                            "theme": "auto",
                            "type": "error",
                            "autoClose": 10000,
                            "dangerouslyHTMLString": true
                        })
                    }
                )
           }
}

const refresh = () => {
    window.location.reload();
}




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
                        currency_code: 'MXN'
                    },
                    custom_id: 123,
                }
            ]
          })
        },
        onApprove: async (data, actions) => {
            return actions.order.capture().then(details => {

                loading.value = true;

                const seatsSelectedData = {
                    purchase_type: props.purchaseType,
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
                }

                axios.post(route('events.confirm-seats-purchase'), seatsSelectedData)
                    .then((response) => {
                        if(response.data.success) {
                            toast(response.data.message, {
                                "theme": "auto",
                                "type": "default",
                                "dangerouslyHTMLString": true
                            })

                            document.getElementById('result-message').innerText = `Transaction completed by ${details.payer.name.given_name}`;
                            console.log('Transaction details:', details);
                            router.visit('/pago-exitoso');
                        }
                    })
                    .catch((error) => {
                        toast(error.response.data.message, {
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

               /*  seatsSelectedData.post(route('events.confirm-seats-purchase'), {
                    onSuccess: (response) => {
                        if(!response.props.flash.error) {
                            document.getElementById('result-message').innerText = `Transaction completed by ${details.payer.name.given_name}`;
                            console.log('Transaction details:', details);
                            router.visit('/pago-exitoso');
                        }
                    },
                    onFinish: () => {
                        drawerPaymentState.value = false;
                    }
                }); */


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

                <div>

                </div>

                <div id="paypal-button-container"></div>
                <p id="result-message"></p>

                <v-btn v-if="!contextCapture" @click="generaateContextCapture" class="button-banbajio">Banbajio</v-btn>

                <form>
                    <p v-if="contextCapture" class="tw-font-bold tw-text-xs lg:tw-text-base tw-my-2">Datos de pago</p>
                    <div id="number-container" :class="{ 'form-control-available': contextCapture, 'form-control-not-available': !contextCapture }"></div>
                    <div class="form-group col-md-6">
                        <div class="form-group col-md-6">
                            <v-select
                                 v-if="contextCapture"
                                id="expMonth"
                                :class="{'form-control-not-available': !contextCapture}"
                                label="Mes de expiración"
                                :items="['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12']"
                                variant="outlined"
                            ></v-select>
                            <v-select
                                 v-if="contextCapture"
                                :class="{'form-control-not-available': !contextCapture }"
                                id="expYear"
                                label="Año de expiración"
                                :items="['2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032']"
                                variant="outlined"
                            ></v-select>
                        </div>
                    </div>
                    <div id="securityCode-container" :class="{ 'form-control-available': contextCapture, 'form-control-not-available': !contextCapture }"></div>
                    <p v-if="contextCapture" class="tw-font-bold tw-text-xs lg:tw-text-base tw-my-2">Dirección de pago</p>

                    <v-container  v-if="contextCapture">
                        <v-row>
                          <v-col
                            cols="12"
                            md="6"
                          >
                            <v-text-field
                              v-model="state"
                              :class="{'form-control-not-available': !contextCapture }"
                              label="Estado"
                              required
                            ></v-text-field>
                          </v-col>

                          <v-col
                            cols="12"
                            md="6"
                          >
                            <v-text-field
                              v-model="municipality"
                              :class="{'form-control-not-available': !contextCapture }"
                              label="Municipio"
                              required
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>

                      <v-container  v-if="contextCapture">
                        <v-row>

                          <v-col
                            cols="12"
                            md="6"
                          >
                            <v-text-field
                              v-model="cp"
                              :class="{'form-control-not-available': !contextCapture }"
                              label="C.P."
                              required
                            ></v-text-field>
                          </v-col>

                          <v-col
                            cols="12"
                            md="6"
                          >
                            <v-text-field
                              v-model="numPhone"
                              :class="{'form-control-not-available': !contextCapture }"
                              :counter="10"
                              label="Núm. Telefonico"
                              required
                            ></v-text-field>
                          </v-col>

                        </v-row>
                      </v-container>

                      <v-container  v-if="contextCapture">
                        <v-row>
                          <v-col
                            cols="12"
                            md="6"
                          >
                            <v-text-field
                              v-model="address"
                              :class="{'form-control-not-available': !contextCapture }"
                              label="Direccion"
                              required
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>

                    <v-btn :disabled="disabledButtonBuyFlex" type="button"  @click.prevent="buy" id="pay-button" :class="{ 'button-banbajio': contextCapture, 'form-control-not-available': !contextCapture }">Pagar</v-btn>
                    <input type="hidden" id="flexresponse" name="flexresponse">
                    <p v-if="generalError" class="error">{{ generalError }}</p>
                </form>

                <v-dialog
                    v-model="dialog"
                    width="auto"
                >
                    <v-card
                        max-width="400"
                        prepend-icon="mdi-update"
                        text="CREATE_TOKEN_CAPTURE_CONTEXT_USED_TOO_MANY_TIMES"
                        title="Update in progress"
                    >
                        <template v-slot:actions>
                            <v-btn
                                class="ms-auto"
                                text="Ok"
                                @click="refresh"
                            ></v-btn>
                        </template>
                    </v-card>
                </v-dialog>

                <div v-if="loadingPaymentFlex" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-5 tw-animate-pulse">
                    <v-progress-linear indeterminate :height="6"></v-progress-linear>
                    <p class="tw-font-bold tw-text-xs lg:tw-text-base">Completando compra en el sistema...</p>
                </div>


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


.error {
    color: red;
    margin-top: 10px;
    font-size: 1em;
  }


.button-banbajio{
    width: 100%;
    background-color: purple;
    color: white;

}

.form-row {
    display: flex;
}




.form-control-available {
    height: 50px;
    margin-bottom: 20px;
    border: 2px solid #787578;
    padding: 10px;
    width: 100%;

}

.form-control-not-available{
    height: 50px;
    border: 2px solid #ffffff;
    padding: 10px;
    margin-top: 10px;
    width: 100%;
    color: white;
    box-shadow: none !important;
}


    .v-navigation-drawer--temporary.v-navigation-drawer--active {
        width: 85% !important;
    }

    @media (min-width: 768px) {
        .v-navigation-drawer--temporary.v-navigation-drawer--active {
            width: 40% !important;
        }
    }
</style>
