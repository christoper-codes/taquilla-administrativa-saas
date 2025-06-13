<script setup>
import { ref } from 'vue'
import usePriceFormat from '@/composables/priceFormat';
import useStringFormat from '@/composables/stringFormat';

const { formatPrice } = usePriceFormat();
const { formatFirstLetterUppercase } = useStringFormat();

const emit = defineEmits(['response-payment', 'cancel-payment']);

const props = defineProps({
    seats: {
        type: Array,
        required: true,
        // default: () => ([
        //     {
        //         id: 4055,
        //         event_id: 2,
        //         seat_catalogue: {
        //             id: 1348,
        //             stadium_id: 1,
        //             zone_type_id: 4,
        //             seat_type_id: 3,
        //             row_type_id: 1,
        //             zone: "E",
        //             row: "A",
        //             seat: "48",
        //             code: "EA48",
        //             description: "Asiento",
        //             is_active: 1,
        //             seat_type: {
        //                 id: 3,
        //                 stadium_id: 1,
        //                 name: "purpura",
        //                 code: "AC",
        //                 percentage_cashback: 15,
        //                 description: "purpura",
        //                 is_active: 1,
        //             }
        //         },
        //         price_types: [
        //             {
        //                 id: 3,
        //                 name: "abonado",
        //                 description: "venta de abonado para al publico",
        //                 is_active: 1,
        //                 created_at: "2025-03-31T08:48:36.000000Z",
        //                 updated_at: "2025-03-31T08:48:36.000000Z",
        //                 pivot: {
        //                     event_seat_catalog_id: 4055,
        //                     price_type_id: 3,
        //                     price_catalogue_id: 6,
        //                     price: "3500.0000",
        //                     is_active: 1,
        //                     created_at: "2025-03-31T15:11:55.000000Z",
        //                     updated_at: "2025-03-31T15:11:55.000000Z"
        //                 }
        //             }
        //         ],
        //         seat_catalogue_status: {
        //             id: 1,
        //             name: "disponible",
        //             description: "Asiento disponible",
        //             is_active: 1,
        //             created_at: "2025-03-31T08:48:36.000000Z",
        //             updated_at: "2025-03-31T08:48:36.000000Z"
        //         },
        //         quantity: 1,
        //         final_price: 3500,
        //         holder_name: "zuriel",
        //         holder_last_name: "diaz",
        //         holder_middle_name: "agustin",
        //         is_owner: "Si",
        //         description: "delio",
        //         holder_jersey_type: "Masculino",
        //         holder_jersey_size: "L",
        //         holder_zip_code: "91015",
        //         holder_phone: "2222222222",
        //         holder_email: "zurielda97@gmail.com",
        //         is_promotion: false,
        //         promotion_id: ""
        //     }
        // ])
    },
    clientReferenceInformation: {
        type: Object,
        required: true,
        // default:{
        //         code: "TC50171_3"
        // }
    },
    orderInformationAmountDetails: {
        type: Object,
        required: true,
        // default:{
        //         totalAmount: "102.21",
        //         currency: "USD"
        // }
    },
    orderInformationBillTo: {
        type: Object,
        required: true,
        // default:{
        //  firstName: "RTS",
		// 	lastName: "VDP",
		// 	address1: "201 S. Division St.",
		// 	locality: "Ann Arbor",
		// 	administrativeArea: "MI",
		// 	postalCode: "48104-2201",
		// 	country: "US",
		// 	district: "MI",
		// 	buildingNumber: "123",
		// 	email: "test@cybs.com",
		// 	phoneNumber: "999999999"
        // }
    },
})


const numberContainer = ref(null)
const securityCodeContainer = ref(null)
const payButtonRef = ref(null)
const expMonthRef = ref(null)
const expYearRef = ref(null)
const errorsOutputRef = ref(null)
const isLoadingGenerateContextCapture = ref(false)
const dialogOpen = ref(false)
const paying = ref(false)


const dataMonths = Array.from({ length: 12 }, (_, i) => ({
  text: String(i + 1).padStart(2, '0'),
  value: String(i + 1).padStart(2, '0'),
}))

const currentYear = new Date().getFullYear()
const dataYears = Array.from({ length: 10 }, (_, i) => {
  const year = currentYear + i
  return { text: String(year), value: String(year) }
})

const generateContextCapture = () => {

    isLoadingGenerateContextCapture.value = true;

    axios.get(route('cyber.source.capture.context'))
        .then((response) => {
            if (response.data?.data) {
                initCyberSource(response.data.data)
            }
        })
        .catch((error) => {
            console.log(error)
        }).finally(()=>{
            setTimeout(() => {
                isLoadingGenerateContextCapture.value = false;
            }, 2000)
        });
}

function initCyberSource(data) {
  const script = document.createElement('script')
  script.type = 'text/javascript'
  script.async = true
  script.onload = () => {
    flexSetup(data.captureContext)
  }

  if (data.clientLibraryIntegrity) {
    script.src = data.clientLibrary
    script.integrity = data.clientLibraryIntegrity
    script.crossOrigin = 'anonymous'
    document.head.appendChild(script)
  }

}

function flexSetup(captureContext) {
    var myStyles = {
        'input': {
        'font-size': '14px',
        'font-family': 'helvetica, tahoma, calibri, sans-serif',
        'color': '#555'
        },
        ':focus': { 'color': 'blue' },
        ':disabled': { 'cursor': 'not-allowed' },
        'valid': { 'color': '#3c763d' },
        'invalid': { 'color': '#a94442' }
    };


  const flex = new Flex(captureContext)
  const microform = flex.microform('card', { styles: myStyles })
  const number = microform.createField('number', { placeholder: 'Número de tarjeta' })
  const securityCode = microform.createField('securityCode', { placeholder: 'CVV' })

  number.load(numberContainer.value)
  securityCode.load(securityCodeContainer.value)

  payButtonRef.value?.$el.addEventListener('click', () => {

    paying.value = true;

    microform.createToken({expirationMonth: expMonthRef.value, expirationYear: expYearRef.value}, (err, token) => {

        errorsOutputRef.value = '';

        if (err) {
            errorsOutputRef.value = err.message;
            paying.value = false;
        } else {

            axios.post(route('cyber.source.payment.with.flex.transient.token'), {
                    clientReferenceInformation: props.clientReferenceInformation,
                    orderInformationAmountDetails: props.orderInformationAmountDetails,
                    orderInformationBillTo: props.orderInformationBillTo,
                    tokenInformation: token
                })
                .then((response) => {
                    emit('response-payment', {
                        response:response,
                        status:true
                    });
                })
                .catch((error) => {
                    emit('response-payment', {
                        response:error,
                        status:false
                    });
                }).finally(()=>{
                    paying.value = false;
                    dialogOpen.value = false;
                });
      }
    })
  })
}

const calcelPayment = () => {
    dialogOpen.value = false;
    emit('cancel-payment', {
        status:true
    });
}

</script>

<template>
    <button type="button" @click="generateContextCapture(); dialogOpen = true" class="tw-w-full tw-font-bold tw-text-white tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-500 hover:tw-bg-gradient-to-bl tw-focus:ring-4 tw-focus:outline-none tw-focus:ring-cyan-300 dark:tw-focus:ring-cyan-800 tw-rounded-lg tw-px-5 tw-py-2.5 tw-text-center tw-me-2 tw-mb-2">
        Cybersource
    </button>
    <v-dialog v-model="dialogOpen" width="auto">
        <v-card>
            <template #title>
                <div class="tw-text-center tw-w-full tw-text-3xl tw-font-semibold">
                Cybersource
                </div>
            </template>
            <v-card-text>

                <div class="tw-relative">

                    <div v-if="isLoadingGenerateContextCapture" class="tw-absolute tw-inset-0 tw-bg-white/80 tw-backdrop-blur-sm tw-z-50 tw-flex tw-items-center tw-justify-center">
                        <v-progress-circular indeterminate color="primary" size="40" />
                    </div>


                    <div :class="{ 'tw-opacity-50 pointer-events-none': isLoadingGenerateContextCapture }" class="tw-max-w-3xl tw-mx-auto tw-bg-white tw-p-6 tw-space-y-6 tw-font-sans">
                        <h2 class="tw-text-2xl tw-font-bold tw-text-center tw-text-gray-800">🛒 Revisión de tu compra</h2>

                        <div class="tw-overflow-x-auto">
                            <table class="tw-w-full tw-text-left tw-border tw-rounded-lg tw-text-sm">
                                <thead class="tw-bg-gray-100 tw-text-gray-700">
                                <tr>
                                    <th class="tw-p-3">Asiento</th>
                                    <th class="tw-p-3">Tipo</th>
                                    <th class="tw-p-3">Precio</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="seat in seats " class="tw-border-t">
                                        <td class="tw-p-3">{{ seat.seat_catalogue.code }}</td>
                                        <td class="tw-p-3">{{ formatFirstLetterUppercase(seat.seat_catalogue.seat_type.name) }}</td>
                                        <td class="tw-p-3">{{ formatPrice(seat.price_types[0].pivot.price) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Resumen de costos -->
                        <div class="tw-text-right tw-text-sm tw-space-y-1">
                            <hr class="tw-my-2">
                            <p class="tw-text-lg tw-font-bold">Total a pagar: <span class="tw-text-blue-600">$ {{ orderInformationAmountDetails.totalAmount }} MXN</span></p>
                        </div>

                        <!-- Formulario de pago -->
                        <div class="tw-space-y-4">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Información de pago</h3>
                        <div class="tw-text-right tw-text-sm tw-space-y-2">
                            <h3 class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-text-lg tw-font-semibold tw-text-gray-800">
                                Tarjetas de crédito
                                <span class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-10 tw-h-10" viewBox="0 0 48 48">
                                        <path fill="#1565C0" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path>
                                        <path fill="#FFF" d="M15.186 19l-2.626 7.832c0 0-.667-3.313-.733-3.729-1.495-3.411-3.701-3.221-3.701-3.221L10.726 30v-.002h3.161L18.258 19H15.186zM17.689 30L20.56 30 22.296 19 19.389 19zM38.008 19h-3.021l-4.71 11h2.852l.588-1.571h3.596L37.619 30h2.613L38.008 19zM34.513 26.328l1.563-4.157.818 4.157H34.513zM26.369 22.206c0-.606.498-1.057 1.926-1.057.928 0 1.991.674 1.991.674l.466-2.309c0 0-1.358-.515-2.691-.515-3.019 0-4.576 1.444-4.576 3.272 0 3.306 3.979 2.853 3.979 4.551 0 .291-.231.964-1.888.964-1.662 0-2.759-.609-2.759-.609l-.495 2.216c0 0 1.063.606 3.117.606 2.059 0 4.915-1.54 4.915-3.752C30.354 23.586 26.369 23.394 26.369 22.206z"></path>
                                        <path fill="#FFC107" d="M12.212,24.945l-0.966-4.748c0,0-0.437-1.029-1.573-1.029c-1.136,0-4.44,0-4.44,0S10.894,20.84,12.212,24.945z"></path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-10 tw-h-10" viewBox="0 0 48 48">
                                        <path fill="#3F51B5" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path>
                                        <path fill="#FFC107" d="M30 14A10 10 0 1 0 30 34A10 10 0 1 0 30 14Z"></path>
                                        <path fill="#FF3D00" d="M22.014,30c-0.464-0.617-0.863-1.284-1.176-2h5.325c0.278-0.636,0.496-1.304,0.637-2h-6.598C20.07,25.354,20,24.686,20,24h7c0-0.686-0.07-1.354-0.201-2h-6.598c0.142-0.696,0.359-1.364,0.637-2h5.325c-0.313-0.716-0.711-1.383-1.176-2h-2.973c0.437-0.58,0.93-1.122,1.481-1.595C21.747,14.909,19.481,14,17,14c-5.523,0-10,4.477-10,10s4.477,10,10,10c3.269,0,6.162-1.575,7.986-4H22.014z"></path>
                                    </svg>
                                </span>
                            </h3>
                        </div>

                        <v-alert v-if="errorsOutputRef" type="error"  density="compact" class="" border="start" variant="tonal" prominent>
                            {{ errorsOutputRef }}
                        </v-alert>


                        <div class="tw-mb-4">
                            <label for="cardNumber-label" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 mb-1">Número de tarjeta</label>
                            <div ref="numberContainer" class="tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-bg-white tw-transition tw-duration-300 tw-h-10"></div>
                        </div>

                        <div class="tw-mb-4">
                            <label for="securityCode-container" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 mb-1">CVV</label>
                            <div ref="securityCodeContainer" class="tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-bg-white tw-transition tw-duration-300 tw-h-10"></div>
                        </div>

                        <div class="tw-flex tw-gap-4 tw-mb-4">
                            <div class="tw-w-1/2 tw-relative">
                                <v-select v-model="expMonthRef" :items="dataMonths" item-title="text" item-value="value" label="Mes de expiración" outlined dense class="tw-w-full" />
                            </div>

                            <div class="tw-w-1/2 tw-relative">
                                <v-select v-model="expYearRef" :items="dataYears" item-title="text" item-value="value" label="Año de expiración" outlined dense class="tw-w-full"/>
                            </div>
                        </div>

                        <v-btn ref="payButtonRef" :disabled="paying" :loading="paying" class="text-none mb-4" color="indigo-darken-3" size="x-large" variant="flat" block>
                            Pagar ahora
                        </v-btn>

                        <p class="tw-text-xs tw-text-center tw-text-gray-500">
                            🔐 Tus datos están protegidos con cifrado SSL a través de Visa CyberSource.
                        </p>

                        <div class="tw-text-center tw-mt-4">
                            <button type="button" @click="calcelPayment" class="tw-text-base tw-text-gray-500 hover:tw-underline tw-mt-4">
                                Cancelar
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<style>
</style>
