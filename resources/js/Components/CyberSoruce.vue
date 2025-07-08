<script setup>

import { ref, reactive, onMounted, computed, watch, watchEffect } from 'vue'
import usePriceFormat from '@/composables/priceFormat';
import useStringFormat from '@/composables/stringFormat';
import { toast } from 'vue3-toastify'

const { formatPrice } = usePriceFormat();
const { formatFirstLetterUppercase } = useStringFormat();

const cybersourceReturnUrl = import.meta.env.VITE_CYBERSOURCE_RETURN_URL;

const emit = defineEmits(['response-payment', 'cancel-payment']);
const props = defineProps({
    seats: {
        type: Array,
        required: true,
    },
    clientReferenceInformation: {
        type: Object,
        required: true,
    },
    orderInformationAmountDetails: {
        type: Object,
        required: true,
    }
})

const openDialogCyberSource = ref(false);

const itemsStep = ref([ 'Información de facturación', 'Información de pago']);
const step = ref(1);
const nextButtonDisabled = ref(false);
const isLoadingView = ref(false);
const isLoadingSuccess = ref(false);
const messageLoading = ref(null);
const formRef = ref(null);
const colonies = ref([])
const orderInformationBill = reactive({
    // Data structure for billing information
    firstName: '',
    lastName: '',
    phoneNumber: '',
    email: '',
    address1: '',
    country: '',
    locality: '',
    address2: '',
    postalCode: '',
    // Data structure for client reference information
    code: props.clientReferenceInformation.code,
    // Data structure for order information amount details
    totalAmount: props.orderInformationAmountDetails.totalAmount,
    currency: props.orderInformationAmountDetails.currency,
});

watchEffect(() => {
  orderInformationBill.code = props.clientReferenceInformation.code;
  orderInformationBill.totalAmount = props.orderInformationAmountDetails.totalAmount;
  orderInformationBill.currency = props.orderInformationAmountDetails.currency;
});

const microform = ref(null);
const errorsOutMicroForm = ref(null);
const numberContainerMicroForm = ref(null);
const securityCodeContainerMicroForm = ref(null);
const tokenInformationMicroForm = ref(null);
const expirationMicroForm = reactive({ expirationMonth: '', expirationYear: ''});

const referenceIdAuthSetup= ref(null);

const cardinalCollectionIframe = ref(null);
const cardinalCollectionForm = ref(null);
const deviceDataCollectionUrl = ref(null);
const accessTokenCollection = ref(null);

const stepUpUrlChallenge = ref(null);
const accessTokenChallenge = ref(null);
const windowSizeChallenge = ref({height: '0', width: '0'});
const stepUpIframeChallenge = ref(null);
const stepUpFormChallenge = ref(null);

const profileCompletedHandled = ref(false);

const nameNextButton = computed(() => itemsNextButton[step.value]);
const namePrevButton = computed(() => itemsPrevButton[step.value]);

const countries = [
  { value: 'MX', label: 'México' },
]

const messageLoadingList = {
    "1": 'Cargando información de facturación...',
    "2": 'Cargando información de pago...',
    "3": 'Procesando Pago...',
    "4": 'Cargando información de autenticación...',
    "5": 'Completando autenticación...',
    "6": 'Pago Procesado...'
};

const myStyles = {
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

const dataMonths = Array.from({ length: 12 }, (_, i) => ({
    text: String(i + 1).padStart(2, '0'),
    value: String(i + 1).padStart(2, '0')
}));

const dataYears = Array.from({ length: 10 }, (_, i) => {
  const year = new Date().getFullYear() + i;
  return { text: String(year), value: String(year) };
});

const itemsNextButton = { 1: 'Información de pago', 2: 'Pagar', 3: ''}
const itemsPrevButton = { 1: '', 2: 'Información de facturación', 3:'Información de pago'}

const rules = {
    required: v => !!v || 'Este campo es requerido',
    email: v => (!v || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) || 'Email inválido',
    phone: v => (!v || /^[0-9\+\-\(\)\s]{7,15}$/.test(v)) || 'Teléfono inválido',
    postal: v => !!v && /^\d{5}$/.test(v) || 'Código postal inválido',
};

const fieldRules = {
    firstName: [rules.required],
    lastName: [rules.required],
    phoneNumber: [rules.required, rules.phone],
    email: [rules.required, rules.email],
    address1: [rules.required],
    country: [rules.required],
    locality: [rules.required],
    address2: [rules.required],
    postalCode: [rules.required, rules.postal],
};

const windowSizeChallengeList = {
    "01": {
        width: '250',
        height: '400',
    },
    "02": {
        width: '390',
        height: '400',
    },
    "03": {
        width: '500',
        height: '600'
    },
    "04": {
        width: '600',
        height: '400'
    },
    "05": {
        height: '600',
        width: '600'
    }
};

const pushAndPopItemStep = (action) =>{
    if(action === 'push') {
        itemsStep.value.push('Autenticación de pago');
    } else if (action === 'pop' && itemsStep.value.length > 2) {
            itemsStep.value.pop();
    }
}

const setLoadingStep = (newMessageLoading, newIsLoadingView, newStep, newIsLoadingSuccess) => {

    if(newMessageLoading != null){
        messageLoading.value = messageLoadingList[newMessageLoading];
    }

    if(newIsLoadingView != null){
        isLoadingView.value = newIsLoadingView
    }

    if(newStep != null){
        step.value = newStep
    }

    if(newIsLoadingSuccess != null){
        isLoadingSuccess.value = newIsLoadingSuccess;
    }
};

const actionNextButton = () => {
    if (step.value === 1) {
        onSubmit();
    } else if (step.value === 2) {
        microformCreateToken();
    }
};

const actionPrevButton = () => {
    if (step.value === 2) {
        setLoadingStep(1, true, 1, null);
        setTimeout(() => {
            setLoadingStep(null, false, null, null);
        }, 2000);
    }else if(step.value === 3){
        pushAndPopItemStep('pop');
        setLoadingStep(2, true, 2, null);
        setTimeout(() => {
            setLoadingStep(null, false, null, null);
        }, 2000);
        generateContextCapture();
    }
};

const cancelProcess = () => {

    pushAndPopItemStep('pop');
    step.value = 1;
    nextButtonDisabled.value = false;
    isLoadingView.value = false;
    isLoadingSuccess.value = false;
    messageLoading.value = null;
    formRef.value?.resetValidation();
    microform.value = null;
    errorsOutMicroForm.value = null;
    tokenInformationMicroForm.value = null;
    expirationMicroForm.expirationMonth = '';
    expirationMicroForm.expirationYear = '';
    referenceIdAuthSetup.value = null;
    cardinalCollectionIframe.value = null;
    cardinalCollectionForm.value = null;
    deviceDataCollectionUrl.value = null;
    accessTokenCollection.value = null;
    stepUpUrlChallenge.value = null;
    accessTokenChallenge.value = null;
    windowSizeChallenge.value = {height: '0', width: '0'};
    stepUpIframeChallenge.value = null;
    stepUpFormChallenge.value = null;
    profileCompletedHandled.value = false;

    Object.keys(orderInformationBill).forEach(key => {
        if(!['code','totalAmount','currency'].includes(key)){
            orderInformationBill[key] = '';
        }
    });
    openDialogCyberSource.value = false;
    emit('cancel-payment',{
        response:{},
        status:false
    });
}

const onSubmit = async () => {
    const valid = await formRef.value.validate();

    if (valid.valid) {
        setLoadingStep(2, true, 2, null);
        generateContextCapture();
    }else{
        return;
    }
}

const generateContextCapture = () => {
    axios.get(route('cyber.source.capture.context'))
        .then((response) => {
            if(response.status === 200) {
                initCyberSource(response.data.data);
            }
        })
        .catch((error) => {

            cancelProcess();
            toast(error.response.data.error.message, {
                "theme": "auto",
                "type": "default",
                "dangerouslyHTMLString": true
            });

        }).finally(()=>{
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

const microformCreateToken = () => {

    setLoadingStep(3, true, null, null);

    microform.value.createToken({expirationMonth: expirationMicroForm.expirationMonth.value, expirationYear: expirationMicroForm.expirationYear.value}, (err, token) => {
        errorsOutMicroForm.value = '';
        if (err) {
            setLoadingStep(null, false, null, null);
            errorsOutMicroForm.value = err.message;
        } else {
            tokenInformationMicroForm.value = token;
            authenticationSetup(token);
      }
    })
}

const flexSetup = async (captureContext) => {

  const flex = new Flex(captureContext);
  microform.value = flex.microform('card', { styles: myStyles });
  const number = microform.value.createField('number', { placeholder: 'Número de tarjeta' });
  const securityCode = microform.value.createField('securityCode', { placeholder: 'CVV' });

  number.load(numberContainerMicroForm.value);
  securityCode.load(securityCodeContainerMicroForm.value);

  setTimeout(() => {
      setLoadingStep(null, false, null, null);
  }, 1000);
}

const deviceDataCollection = (pDeviceDataCollectionUrl, pAccessToken) => {
    deviceDataCollectionUrl.value = pDeviceDataCollectionUrl;
    accessTokenCollection.value = pAccessToken;

    setTimeout(() => {
        cardinalCollectionForm.value.submit();
    }, 100)
}

const authenticationSetup = (token) => {
    axios.post(route('cyber.source.authentication.setup'), {
        expirationMonth:expirationMicroForm.expirationMonth,
        expirationYear:expirationMicroForm.expirationYear,
        tokenInformation: token
    })
    .then((response) => {
        if(response.status === 200) {

            if(response.data?.data?.errorInformation){
                throw {
                    type: 'cybersource_decline',
                    reason: response.data.data.errorInformation.reason,
                    message: response.data.data.errorInformation.message,
                    raw: response.data.data
                };

            }else{
                referenceIdAuthSetup.value = response.data?.data.consumerAuthenticationInformation.referenceId;

                deviceDataCollection(
                    response.data?.data.consumerAuthenticationInformation.deviceDataCollectionUrl,
                    response.data?.data.consumerAuthenticationInformation.accessToken
                );
            }
        }
    })
    .catch((error) => {

        if(error.response?.data?.error?.message){
            cancelProcess();
            toast(error.response.data.error.message, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
            });
        }

        if(error.response?.data?.errors){
            errorsOutMicroForm.value = 'One or more fields have a validation error';
        }

        if(error?.message){
            errorsOutMicroForm.value = error?.message;
        }

        setLoadingStep(null, false, null, null);

    }).finally(()=>{
    });
}

const cyberSourceEnrollWithTransientToken = () => {
    const browserInfo = {
        httpAcceptContent: navigator.userAgent,
        httpBrowserLanguage: navigator.language || navigator.userLanguage,
        httpBrowserJavaEnabled: navigator.javaEnabled ? navigator.javaEnabled() : false,
        httpBrowserColorDepth: screen.colorDepth ? screen.colorDepth.toString() : "",
        httpBrowserScreenHeight: screen.height ? screen.height.toString() : "",
        httpBrowserScreenWidth: screen.width ? screen.width.toString() : "",
        httpBrowserTimeDifference: (new Date().getTimezoneOffset()).toString(),
        userAgentBrowserValue: navigator.userAgent
    };

    axios.post(route('cyber.source.enroll.with.transient.token'), {
        clientReferenceInformation:{
            code: orderInformationBill.code,
        },
        orderInformation: {
            amountDetails: {
                currency: orderInformationBill.currency,
                totalAmount: orderInformationBill.totalAmount,
            },
            billTo: {
                firstName: orderInformationBill.firstName,
                lastName: orderInformationBill.lastName,
                phoneNumber: orderInformationBill.phoneNumber,
                email: orderInformationBill.email,
                address1: orderInformationBill.address1,
                address2: orderInformationBill.address2,
                country: orderInformationBill.country,
                locality: orderInformationBill.locality,
                postalCode: orderInformationBill.postalCode,
            }
        },
        browserInfo: browserInfo,
        tokenInformation: tokenInformationMicroForm.value,
        expirationMonth:expirationMicroForm.expirationMonth,
        expirationYear:expirationMicroForm.expirationYear,
        referenceId: referenceIdAuthSetup.value,
    })
    .then((response) => {

        if(response.status === 200) {

            const paresStatus = response.data.data.consumerAuthenticationInformation.paresStatus;
            const veresEnrolled = response.data.data.consumerAuthenticationInformation.veresEnrolled;

            // Successful Frictionless Authentication
            if (paresStatus === 'Y' && veresEnrolled === 'Y') {
                payment(response.data.data);
            }

            // Attempts Processing Frictionless Authentication
            if (paresStatus === 'A' && veresEnrolled === 'Y') {
                payment(response.data.data);
            }

            // Successful Step-Up Authentication
            if (paresStatus === 'C' && veresEnrolled === 'Y') {
                challenge(response.data.data);
            }

            // Unsuccessful Frictionless Authentication
            if (paresStatus === 'N' && veresEnrolled === 'Y') {

                openDialogCyberSource.value = false;


                toast(`Autenticación fallida, por favor intente nuevamente. ${response?.data?.data?.consumerAuthenticationInformation?.cardholderMessage}`, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
                });

                cancelProcess();

                emit('response-payment', {
                    response:response.data.data,
                    status:false
                });
            }
        }
    })
    .catch((error) => {
        if(error.response?.data?.error?.message){
            cancelProcess();
            toast(error.response.data.error.message, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
            });
        }
    }).finally(()=>{
    });
}

const payment = (data) => {
    axios.post(route('cyber.source.payment'), {
        clientReferenceInformation:{
            code: orderInformationBill.code,
        },
        orderInformation: {
            amountDetails: {
                currency: orderInformationBill.currency,
                totalAmount: orderInformationBill.totalAmount,
            },
            billTo: {
                firstName: orderInformationBill.firstName,
                lastName: orderInformationBill.lastName,
                phoneNumber: orderInformationBill.phoneNumber,
                email: orderInformationBill.email,
                address1: orderInformationBill.address1,
                address2: orderInformationBill.address2,
                country: orderInformationBill.country,
                locality: orderInformationBill.locality,
                postalCode: orderInformationBill.postalCode,
            }
        },
        tokenInformation: tokenInformationMicroForm.value,
        expirationMonth:expirationMicroForm.expirationMonth,
        expirationYear:expirationMicroForm.expirationYear,
        response: data
    })
    .then((response) => {
        if(response.status === 201 && response.data.data.status === "AUTHORIZED") {
            setLoadingStep(6, null, null, true);
            setTimeout(() => {
                emit('response-payment', {
                    response:response.data,
                    status:true
                });
            }, 3000);
        }
    })
    .catch((error) => {
        if(error.response?.data?.error?.message){
            cancelProcess();
            toast(error.response.data.error.message, {
                    "theme": "auto",
                    "type": "default",
                    "dangerouslyHTMLString": true
            });
        }
    })
    .finally(()=>{});
}

const challenge = (data) => {

    pushAndPopItemStep('push');
    setLoadingStep(4, null, 3, null);

    const decoded = JSON.parse(atob(data.consumerAuthenticationInformation.pareq));
    windowSizeChallenge.value = windowSizeChallengeList[decoded.challengeWindowSize];

    accessTokenChallenge.value = data.consumerAuthenticationInformation.accessToken;
    stepUpUrlChallenge.value = data.consumerAuthenticationInformation.stepUpUrl;

    setTimeout(() => {
        stepUpIframeChallenge.value.addEventListener('load', () => {

            setLoadingStep(4, false, null, null);

        }, { once: true })
        stepUpFormChallenge.value.submit();
    }, 1000)

}

const completedChallenge = (data) => {
    payment(data);
}



onMounted(() => {
    window.addEventListener("message", function(event) {
        if (event.origin === "https://centinelapistag.cardinalcommerce.com") {
            let data = event.data;

            if (typeof data === "string") {
                try {
                    data = JSON.parse(data);
                } catch (err) {
                    return;
                }
            }

            if (data && data.MessageType === "profile.completed" && !profileCompletedHandled.value) {
                profileCompletedHandled.value = true;
                cyberSourceEnrollWithTransientToken();
            }
        }

        if (event.origin === cybersourceReturnUrl) {
            let data = event.data;
            if (data && data.MessageType === "CHALLENGE_COMPLETED") {
                completedChallenge(data.Response);
            }
        }
    }, false);
})

const searchLocality = (value) => {

    if (rules.postal(value) !== true){
        return
    };

    axios.get(route('get.cp',{cp:value}))
    .then((response) => {
        if(!response.data?.error){
            orderInformationBill.locality = response.data.codigo_postal.municipio;
            colonies.value = response.data.codigo_postal.colonias;
        }
    })
    .catch((error) => {
        toast(error.response.data.message, {
                "theme": "auto",
                "type": "default",
                "dangerouslyHTMLString": true
        });
    })
    .finally(() => {
    });
}

</script>

<template>
    <button type="button" @click="openDialogCyberSource = true"
        class="tw-w-full tw-font-bold tw-text-white tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-500 hover:tw-bg-gradient-to-bl tw-focus:ring-4 tw-focus:outline-none tw-focus:ring-cyan-300 dark:tw-focus:ring-cyan-800 tw-rounded-lg tw-px-5 tw-py-2.5 tw-text-center tw-me-2 tw-mb-2">
        Cybersource
    </button>
    <v-dialog v-model="openDialogCyberSource" min-width="400" max-width="800" persistent>
        <v-card>
            <template #title>
                <div class="tw-text-center tw-w-full tw-text-3xl tw-font-semibold">
                    Cybersource Payment
                </div>
            </template>
            <v-card-text>
                <div v-if="isLoadingView" class="tw-absolute tw-inset-0 tw-bg-white/80 tw-backdrop-blur-sm tw-z-50 tw-flex tw-items-center tw-justify-center tw-flex-col">
                    <v-progress-circular v-if="!isLoadingSuccess" indeterminate color="primary" size="50" />
                    <v-icon v-if="isLoadingSuccess" color="success" size="64">mdi-check-circle</v-icon>
                    <span class="tw-mt-4">{{ messageLoading }}</span>
                </div>
                <v-stepper v-model="step" :items="itemsStep" hide-actions>
                    <template v-slot:item.1>
                        <v-form ref="formRef" @submit.prevent="onSubmit" lazy-validation>
                            <div class="tw-grid tw-gap-4 tw-grid-cols-2">
                                <v-text-field label="Nombre" v-model="orderInformationBill.firstName"
                                    :rules="fieldRules.firstName" />
                                <v-text-field label="Apellidos" v-model="orderInformationBill.lastName"
                                    :rules="fieldRules.lastName" />
                            </div>
                            <div class="tw-grid tw-gap-4 tw-grid-cols-2">
                                <v-text-field label="Número de Teléfono" v-model="orderInformationBill.phoneNumber"
                                    :rules="fieldRules.phoneNumber" />
                                <v-text-field label="Correo Electrónico" v-model="orderInformationBill.email"
                                    :rules="fieldRules.email" />
                            </div>
                            <div class="tw-grid tw-gap-4 tw-grid-cols-2">
                                <v-select label="País" v-model="orderInformationBill.country" :items="countries" item-title="label" item-value="value"
                                    :rules="fieldRules.country"/>

                                <v-text-field label="Código Postal" v-model="orderInformationBill.postalCode"
                                    :rules="fieldRules.postalCode" @update:model-value="searchLocality($event)"/>
                            </div>
                            <div class="tw-grid tw-gap-4 tw-grid-cols-2">
                                <v-text-field label="Ciudad" v-model="orderInformationBill.locality"
                                    :rules="fieldRules.locality" />
                                <v-select label="Colonia" v-model="orderInformationBill.address2" :items="colonies"
                                    :rules="fieldRules.address2"/>
                            </div>
                            <div class="tw-grid tw-gap-4 tw-grid-cols-1">
                                <v-textarea label="Calle y Número" rows="2" variant="filled" auto-grow
                                    v-model="orderInformationBill.address1" :rules="fieldRules.address1" />
                            </div>
                        </v-form>
                    </template>
                    <template v-slot:item.2>
                        <div class="tw-relative">
                            <div class="tw-max-w-3xl tw-mx-auto tw-bg-white tw-p-6 tw-space-y-6 tw-font-sans">
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
                                    <p class="tw-text-lg tw-font-bold">Total a pagar: <span class="tw-text-blue-600">$ {{ orderInformationBill.totalAmount }} MXN</span></p>
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

                                <v-alert v-if="errorsOutMicroForm" type="error"  density="compact" class="" border="start" variant="tonal" prominent>
                                    {{ errorsOutMicroForm }}
                                </v-alert>

                                <div class="tw-mb-4">
                                    <label for="cardNumber-label" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 mb-1">Número de tarjeta</label>
                                    <div ref="numberContainerMicroForm" class="tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-bg-white tw-transition tw-duration-300 tw-h-10"></div>
                                </div>

                                <div class="tw-mb-4">
                                    <label for="securityCode-container" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 mb-1">CVV</label>
                                    <div ref="securityCodeContainerMicroForm" class="tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-bg-white tw-transition tw-duration-300 tw-h-10"></div>
                                </div>
                                <br>
                                <div class="tw-flex tw-gap-4 tw-mb-4">
                                    <div class="tw-w-1/2 tw-relative">
                                        <v-select v-model="expirationMicroForm.expirationMonth" :items="dataMonths" item-title="text" item-value="value" label="Mes de expiración" outlined dense class="tw-w-full" />
                                    </div>

                                    <div class="tw-w-1/2 tw-relative">
                                        <v-select v-model="expirationMicroForm.expirationYear" :items="dataYears" item-title="text" item-value="value" label="Año de expiración" outlined dense class="tw-w-full"/>
                                    </div>
                                </div>


                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-slot:item.3>
                        <div class="tw-relative" v-if="stepUpUrlChallenge && accessTokenChallenge">
                            <iframe ref="stepUpIframeChallenge" style="border: none; margin-left: auto; margin-right: auto; display: block" :width="windowSizeChallenge.width" :height="windowSizeChallenge.height" name="stepUpIframe"></iframe>
                            <form ref="stepUpFormChallenge" method="POST" target="stepUpIframe" :action="stepUpUrlChallenge">
                                <input type="hidden" name="JWT" :value="accessTokenChallenge"/>
                            </form>
                        </div>
                    </template>
                    <v-stepper-actions>
                        <template v-slot:prev>
                            <v-btn color="primary" @click="actionPrevButton">{{ namePrevButton }}</v-btn>
                        </template>
                        <template v-slot:next>
                            <v-btn color="primary" @click="actionNextButton" :disabled="nextButtonDisabled">{{ nameNextButton }}</v-btn>
                        </template>
                    </v-stepper-actions>

                    <div class="tw-text-center tw-mt-3 tw-mb-4">
                        <span @click="cancelProcess" class="tw-cursor-pointer tw-text-red-600 hover:tw-text-red-800 tw-font-medium" style="text-decoration: underline;">
                        Cancelar Proceso
                        </span>
                    </div>
                </v-stepper>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!-- Device Data Collect -->
    <div v-if="deviceDataCollectionUrl && accessTokenCollection">
        <iframe ref="cardinalCollectionIframe" id="cardinalCollectionIframe" name="collectionIframe" width="10px" height="10px" style="display:none;"></iframe>
        <form ref="cardinalCollectionForm" id="cardinalCollectionForm" method="POST" target="collectionIframe" :action="deviceDataCollectionUrl">
            <input id="cardinalCollectionFormInput" type="hidden" name="JWT" :value="accessTokenCollection"/>
        </form>
    </div>

</template>
<style></style>
