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
import ZonaB from '@/Components/SectionsHdx/ZonaB.vue';
import ZonaE from '@/Components/SectionsHdx/ZonaE.vue';
import ZonaH from '@/Components/SectionsHdx/ZonaH.vue';
import useStringFormat from '@/composables/stringFormat';
import GuestNav from '@/Components/navs/GuestNav.vue';
import AppNav from '@/Components/navs/AppNav.vue';
import CashRegisterNav from '@/Components/navs/CashRegisterNav.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';

const { dateFormat } = useDateFormat();
const { formatFirstLetterUppercase } = useStringFormat();
const tab = ref('seats');

const { cashRegisterDataId, sellerUserId, ticketOfficeId } = useTicketOfficeState();
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

const acceptTerms = ref(false);
const totalAmount = ref(0);
const amountReceived = ref(0);
const amountReturned = ref(0);

const showImageModal = ref(false);
const modalImageSrc = ref('');

const openImageModal = (src) => {
    modalImageSrc.value = src;
    showImageModal.value = true;
}
const closeImageModal = () => {
    showImageModal.value = false;
    modalImageSrc.value = '';
}

/*
* |--------------------------------------
* | declare properties
*/
const { formatPrice } = usePriceFormat();
const { viewVendorTopics } = useUserPolicy();
let panZoomInstance;
const paymentSection = ref(null);
const scrollTopaymentSection = () => {
    tab.value = 'payment';
    setTimeout(() => {
        paymentSection.value.scrollIntoView({ behavior: 'smooth' });
    }, 500);
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
                panZoomInstance.smoothZoom(300, 360, 0.6);
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
    if(selectedPromotion.value){
        toast('Una vez selecionada una promoción ya no es posible agregar mas asientos a la compra.', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return
    }
    if(selectedAgreementPromotion.value){
        toast('Una vez selecionada una promoción ya no es posible agregar mas asientos a la compra.', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return
    }

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
        seat.holder_name = '';
        seat.holder_last_name = '';
        seat.holder_middle_name = '';
        seat.is_owner = 'No';
        seat.description = '';
        seat.holder_jersey_type = null;
        seat.holder_jersey_size = null;
        seat.holder_zip_code = '';
        seat.holder_phone = '';
        seat.holder_email = '';
        seat.is_promotion = false;
        seat.promotion_id = '';
        seat.is_gift = false;
        seatsSelected.value.push(seat);
        snackbar.value = true;

        const regularPrice = priceFinal(seat, priceFinalType);
        totalAmount.value = (parseFloat(totalAmount.value || 0) + parseFloat(regularPrice));
    } else {
        seatsSelected.value = seatsSelected.value.filter((s) => s.seat_catalogue.code !== seat.seat_catalogue.code);
        if(seatsSelected.value.length == 0) {
            snackbar.value = false;
        }
        const regularPrice = priceFinal(seat, priceFinalType);
        totalAmount.value = (parseFloat(totalAmount.value || 0) - parseFloat(regularPrice));
    }

    amountToPayCash.value = totalAmount.value;
    amountToPayCard.value = totalAmount.value;
    updateTotal();
}


const getHolderInfo = (field) => {
  const ownerSeat = seatsSelected.value.find(item => item.is_owner === "Si");
  return ownerSeat ? ownerSeat[field] : "No disponible";
};

/*
* handle global payment types
*/
const globalPaymentTypes = ref([]);
const purchaseOnline = ref(true);
const priceTypeId = ref(1);

const panel = ref([0,1]);
const paymentTypesSelected = ref([]); //

const filteredPaymentTypes = computed(() => {
    if (paymentTypesSelected.value.some(type => type.name === 'cortesia')) {
        const newPaymentTypesSelected = paymentTypesSelected.value.filter(type => type.name === 'cortesia');
        paymentTypesSelected.value = newPaymentTypesSelected;
        return paymentTypesSelected.value;
    }
    if (paymentTypesSelected.value.some(type => type.name === 'plazos')) {
        const newPaymentTypesSelected = paymentTypesSelected.value.filter(type => type.name === 'plazos');
        paymentTypesSelected.value = newPaymentTypesSelected;
        return paymentTypesSelected.value;
    }

    return paymentTypesSelected.value;
});

const paymentInInstallments = () => {
    if(installmentSale.value == false){
        paymentTypesSelected.value = props.global_payment_types.filter(item => item.name === 'plazos');
        installmentSale.value = true;
    } else {
        paymentTypesSelected.value = [];
        paymentTypesSelected.value.push(props.global_payment_types.find((item) => item.name === 'tarjeta'));
        installmentSale.value = false;
    }
}

watch(filteredPaymentTypes, updateTotal);

/*
* handle promotions
*/
const promotionTypes = ref([]);
const promotionTypesCo = ref([]);
const selectedPromotion = ref(null);
const selectedAgreementPromotion = ref(null);
const seatsSelectedCopy = ref([]);
const showPromotionToast = ref(false);
const finalPromotion = ref({id: null, quantity:null});

watch(promotionTypes, () => {
    promotionTypesCo.value = []
    promotionTypes.value.forEach(promotionType => {

        if (viewVendorTopics(props.user_roles) &&  (promotionType.availability_sale == 1 || promotionType.availability_sale == 3)) {
            promotionTypesCo.value.push(promotionType);
        }

        if(!viewVendorTopics(props.user_roles) &&  (promotionType.availability_sale == 2 || promotionType.availability_sale == 3)){
            promotionTypesCo.value.push(promotionType);
        }
    });


    if(!showPromotionToast.value){
        promotionTypesCo.value.forEach(promotionType => {
            if(promotionType.quantity > promotionType.generic_seats_allowed || promotionType.percent_allow > 0){
                showPromotionToast.value = true;
                toast('Tienes promociones que puedes aplicar!!', {
                    "theme": "auto",
                    "type": "deafult",
                    "dangerouslyHTMLString": true
                })
            }
        });
    }
});

watch(selectedPromotion, () => {

    finalPromotion.value = {};
    seatsSelected.value = JSON.parse(JSON.stringify(seatsSelectedCopy.value));
    finalPromotion.value.id = selectedPromotion.value.id;
    finalPromotion.value.quantity = 0;

    if(selectedPromotion.value.type == 'descuento_por_compra_multiple') {
        let seatsTopay = selectedPromotion.value.generic_seats_allowed;
        let seatsToGift = selectedPromotion.value.promotional_seats_allowed;
        let applicableIndex = 0;

        seatsSelected.value.forEach((seat) => {
            const promotion = seat.promotions.find((promo) => promo.name === selectedPromotion.value.name);

            if (promotion) {
                if(seat.final_price == selectedPromotion.value.final_price){

                    if(applicableIndex % (seatsTopay + seatsToGift) < seatsTopay){
                        seat.is_promotion = false;
                    } else {
                        finalPromotion.value.quantity++;
                        seat.is_promotion = true;
                        seat.promotion_id = selectedPromotion.value.id;
                        seat.is_gift = true;
                        seat.price_types.forEach(priceType => {

                            if(priceType.name == 'regular'){
                                priceType.pivot.price = parseFloat(0);

                            }else if(priceType.name == 'abonado'){
                                priceType.pivot.price = parseFloat(0);
                            }
                        })
                    }
                    applicableIndex++;
                }
            }
        });

    } else if(selectedPromotion.value.type == 'descuento_por_porcentaje_por_boleto'){
        seatsSelected.value.forEach((seat) => {
            const promotion = seat.promotions.find((promo) => promo.name === selectedPromotion.value.name);

            if (promotion) {

                seat.price_types.forEach(priceType => {
                    if(seat.final_price == selectedPromotion.value.final_price){
                        finalPromotion.value.quantity++;
                        seat.promotion_id = selectedPromotion.value.id;

                        if(priceType.name == 'regular'){
                            const discount = priceType.pivot.price * (selectedPromotion.value.percent_allow / 100);
                            priceType.pivot.price = priceType.pivot.price - discount;

                        }else if(priceType.name == 'abonado'){
                            const discount = priceType.pivot.price * (selectedPromotion.value.percent_allow / 100);
                            priceType.pivot.price = priceType.pivot.price - discount;
                        }
                    }
                })
            }
        });
    }

    updateTotal();

    toast('La promoción ha sido aplicada', {
        "theme": "auto",
        "type": "success",
        "dangerouslyHTMLString": true
    })

});

watch(selectedAgreementPromotion, () => {
    finalPromotion.value = {};
    seatsSelected.value = JSON.parse(JSON.stringify(seatsSelectedCopy.value));
    finalPromotion.value.id = selectedAgreementPromotion.value.id;
    finalPromotion.value.quantity = 0;

    const promoType = selectedAgreementPromotion.value.promotion_type.name;


    if(selectedAgreementPromotion.value.promotion_type.name == 'descuento_por_compra_multiple') {
        let seatsTopay = selectedAgreementPromotion.value.generic_seats_allowed;
        let seatsToGift = selectedAgreementPromotion.value.promotional_seats_allowed;
        let applicableIndex = 0;

        seatsSelected.value.forEach((seat) => {

            if(applicableIndex % (seatsTopay + seatsToGift) < seatsTopay){
                seat.is_promotion = false;
            } else {
                finalPromotion.value.quantity++;
                seat.is_promotion = true;
                seat.promotion_id = selectedAgreementPromotion.value.id;
                seat.agreement_promotion_id = selectedAgreementPromotion.value.pivot.id;
                seat.is_gift = true;
                seat.price_types.forEach(priceType => {
                    if(priceType.name == 'regular'){
                        priceType.pivot.price = parseFloat(0);
                    }
                })
            }
            applicableIndex++;
        });
    } else if (promoType === 'descuento_por_porcentaje_por_boleto') {
            seatsSelected.value.forEach((seat) => {
                const promotion = seat.promotions?.find(
                    (promo) => promo.name === selectedAgreementPromotion.value.name
                );

                if (promotion) {
                    seat.price_types.forEach((priceType) => {
                        finalPromotion.value.quantity++;
                        seat.promotion_id = selectedAgreementPromotion.value.id;
                        seat.agreement_promotion_id = selectedAgreementPromotion.value.pivot.id;
                        if (priceType.name === 'regular') {
                            const discount =
                                priceType.pivot.price *
                                (selectedAgreementPromotion.value.percent_allow / 100);
                            priceType.pivot.price = priceType.pivot.price - discount;
                        }
                    });
                }
            });
        }else if(selectedAgreementPromotion.value.promotion_type.name == 'descuento_por_porcentaje_por_compra'){
            seatsSelected.value.forEach((seat) => {
                seat.price_types.forEach(priceType => {
                    finalPromotion.value.quantity++;
                    seat.promotion_id = selectedAgreementPromotion.value.id;
                    seat.agreement_promotion_id = selectedAgreementPromotion.value.pivot.id;
                    if(priceType.name == 'regular'){
                        const discount = priceType.pivot.price * (selectedAgreementPromotion.value.percent_allow / 100);
                        priceType.pivot.price = priceType.pivot.price - discount;
                    }

                })
            });
        }

    updateTotal();

    toast('La promoción ha sido aplicada', {
        theme: 'auto',
        type: 'success',
        dangerouslyHTMLString: true,
    });
});

function updateTotal() {

    amountReceived.value = 0;
    amountReturned.value = 0;
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
                } else if(purchaseType.value == 'abonado'){
                    price = priceFinal(seat, 'abonado');
                } else {
                    price = priceFinal(seat, 'regular');
                }
                seat.final_price = price;
                totalAmount.value = (parseFloat(totalAmount.value || 0) + parseFloat(price));
                processedSeats.add(seat.seat_catalogue.code);
            }
        });
    });

    if (purchaseType.value === 'serie') {
        totalAmount.value = totalAmount.value * 2;
    }

    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        amountToPayCash.value = 0;
        amountReceived.value = totalAmount.value;
        amountReceivedCash.value = 0;
    } else if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')){
        amountToPayCard.value = 0;
    }

    if(!selectedPromotion.value && !selectedAgreementPromotion.value){
        seatsSelectedCopy.value = JSON.parse(JSON.stringify(seatsSelected.value));
    }

    promotionTypes.value = [];

    seatsSelectedCopy.value.forEach(seat => {
        if(seat.promotions.length > 0) {
            seat.promotions.forEach(promotion => {
                const actualPromotionExist = promotionTypes.value.find(promo => promo.name === promotion.name && promo.final_price === seat.final_price);
                if(actualPromotionExist) {
                    promotionTypes.value.forEach(promotionType => {
                        if (promotionType.name === promotion.name && promotionType.final_price === seat.final_price) {
                            promotionType.quantity++;
                        }
                    });
                } else {
                    promotionTypes.value.push({
                        'id': promotion.id,
                        'name': promotion.name,
                        'type': promotion.promotion_type.name,
                        'quantity': 1,
                        'final_price': seat.final_price,
                        'description': promotion.description,
                        'generic_seats_allowed': promotion.generic_seats_allowed,
                        'maximun_promotions_allowed': promotion.maximun_promotions_allowed,
                        'promotional_seats_allowed': promotion.promotional_seats_allowed,
                        'percent_allow': promotion.percent_allow,
                        'availability_sale': promotion.availability_sale
                    });
                }
            });
        }
    });

    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
        amountToPayCard.value = totalAmount.value;
    }

    /*
    * reason agreement section
    */
    if(paymentTypesSelected.value.some(type => type.name != 'cortesia')){
        reasonAgreementSelected.value = null;
        reasonAgreementDescription.value = null;
        agreementSection.value = {};
    }

}

const globalPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const reasonAgreementsProps = (item) => {
    return {
        title: item.name,
        subtitle: item.description,
    }
}

const institutionsProps = (item) => {
    return {
        title: item.name,
        subtitle: item.description,
    }
}

const institutionAgreementsProps = (item) => {
    return {
        title: item.name,
        subtitle: item.description,
    }
}

const globalCardPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const isSvgVisible = ref(true);
const selectedSection = ref('');
const viewSelectedSection = ref('Zonas HDX');
const seatsASection = ref([]);
const seatsBSection = ref([]);
const seatsCSection = ref([]);
const seatsESection = ref([]);
const seatsFSection = ref([]);
const seatsHSection = ref([]);
const loadingSectionDialog = ref(false);
const seatAvailability = ref([]);

const handleSectionClick = (section) => {
    const actualSection = section.split('');

    const data = {
        zone: actualSection[actualSection.length -1],
        event_id: props.event.id
    }

    loadingSectionDialog.value = true;

    axios.post(route('event.get.seat-catalogues'), data)
        .then(success => {
            loadingSectionDialog.value = false;
            selectedSection.value = section;
            isSvgVisible.value = false;

            if(section == 'zonaC'){
                seatsCSection.value = success.data.data;
                loadSvg('zonaC');
                viewSelectedSection.value = 'Zona C';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
            if(section == 'zonaA'){
                seatsASection.value = success.data.data;
                loadSvg('zonaA');
                viewSelectedSection.value = 'Zona A';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
            if(section == 'zonaB'){
                seatsBSection.value = success.data.data;
                loadSvg('zonaB');
                viewSelectedSection.value = 'Zona B';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
            if(section == 'zonaE'){
                seatsESection.value = success.data.data;
                loadSvg('zonaE');
                viewSelectedSection.value = 'Zona E';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
            if(section == 'zonaF'){
                seatsFSection.value = success.data.data;
                loadSvg('zonaF');
                viewSelectedSection.value = 'Zona F';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
            if(section == 'zonaH'){
                seatsHSection.value = success.data.data;
                loadSvg('zonaH');
                viewSelectedSection.value = 'Zona H';
                const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
                stadiumHdxImg.classList.remove('tw-rotate-0');
                stadiumHdxImg.classList.add('tw-rotate-90');
            }
        })
        .catch(error => {
            console.log(error)
        })

    return

};

const selectZones = () => {
    loadSvg('zones_hdx');
    showButtonPayment.value = false;
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
    reasonAgreementSelected.value = null;
    institutionSelected.value = null;
    agreementsByInstitutionSelected.value = null;
    agreementSelected.value = null;
    reasonAgreementDescription.value = null;
    agreementSection.value = {};
    valid.value = true;
    purchaseStatus.value = 'process';
    viewSelectedSection.value = 'Zonas HDX';
    purchaseType.value = props.event.enabled_for_season_tickets ? 'abonado' : 'partido';
    loadingg.value = false;
    loading.value = false;
    userToTransfer.value = null;
    saleDeptorSelected.value = null;
    installmentSale.value = false;
    firstNameSaleDeptor.value = '';
    lastNameSaleDeptor.value = '';
    phoneSaleDeptor.value = '';
    saleDebtorData.value = {};
    seatsSelected.value = [];
    const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
    stadiumHdxImg.classList.remove('tw-rotate-90');
    stadiumHdxImg.classList.add('tw-rotate-0');
    selectedPromotion.value = null;
    selectedAgreementPromotion.value = null;
    seatsSelectedCopy.value = [];
    showPromotionToast.value = false;
    seatAvailability.value = [];
    paymentInstallmentSelected.value = null;
    getSeatAvailability();
    tab.value = 'seats';
    acceptTerms.value = viewVendorTopics(props.user_roles);
};

/*
* declare props
*/
const props = defineProps({
    isEventsShow: {
        type: Boolean,
        required: false,
    },
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
    payment_installments: {
        type: Object,
        required: false
    },
    reason_agreements: {
        type: Array,
        required: false
    },
    institutions: {
        type: Array,
        required: false
    },
    sale_debtors: {
        type: Array,
        required: false
    },
});

const users_list = [];
const sale_debtors_list = [];
const userToTransfer = ref(null);
const saleDeptorSelected = ref(null);
const purchaseType = props.event.enabled_for_season_tickets ? ref('abonado') : ref('partido');

if(viewVendorTopics(props.user_roles)) {
    purchaseOnline.value = false;
}

props.users.forEach(element => {
    users_list.push(
        {
            name: `${element['first_name']} ${element['last_name']} (${element['email']})`,
            value: element['id']
        }
    )
});

props.sale_debtors.forEach(element => {
    sale_debtors_list.push(
        {
            name: `${element['first_name']} ${element['last_name']} (${element['phone_number']})`,
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
    getSeatAvailability();
    globalPaymentTypesOnlyCard.value = props.global_payment_types.filter(item => item.name === 'tarjeta');
    acceptTerms.value = viewVendorTopics(props.user_roles);
});

const getSeatAvailability = () => {
    const data = {event_id: props.event.id};
    axios.get(route('events.availability'), { params: data })
        .then(response => {
            seatAvailability.value = response.data.data;
        })
        .catch(error => {
            console.log(error)
        })
}

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
const reasonAgreementSelected = ref(null);
const reasonAgreementDescription = ref(null);
const agreementSection = ref({});
const institutionSelected = ref(null);
const agreementsByInstitutionSelected = ref(null);
const agreementSelected = ref(null);
const amountToPayCash = ref(0);
const amountReceivedCash = ref(0);
const purchaseStatus = ref('process');
const originalTotalAmount = ref(0);
const totalAttempts = ref(0);
const memberUserId = ref(1);

/*
* installment sale module
*/
const installmentSale = ref(false);
const firstNameSaleDeptor = ref('');
const lastNameSaleDeptor = ref('');
const phoneSaleDeptor = ref('');
const saleDebtorData = ref({});

watch(() => institutionSelected.value, () => {
    agreementsByInstitutionSelected.value = null;
    agreementSelected.value = null;
    selectedAgreementPromotion.value = null;
    if(institutionSelected.value){
        agreementsByInstitutionSelected.value = institutionSelected.value.agreements;
    }
});

watch(() => amountReceived.value, (newValue) => {
    if(!installmentSale.value){
        amountReturned.value = parseFloat(amountReceived.value) - parseFloat(totalAmount.value)
    }else{
        if(amountReceived.value == 0){
            amountReturned.value = 0;

        }else{
            if( paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')){
                amountReturned.value = parseFloat(amountReceived.value) - parseFloat(amountToPayCash.value);
            }else if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')){
                amountReturned.value = parseFloat(amountReceived.value) - parseFloat(amountToPayCard.value);
            } else {
                amountReturned.value = parseFloat(totalAmount.value) - parseFloat(amountReceived.value);
            }
        }
    }
});

watch(() => amountToPayCard.value, (newValue) => {
     amountReceived.value = parseFloat(amountToPayCard.value) + parseFloat(amountReceivedCash.value);
});

watch(() => amountToPayCash.value, (newValue) => {
     if(installmentSale.value){
        if( paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')){
            amountReturned.value = parseFloat(amountReceived.value) - parseFloat(amountToPayCash.value);
        }
    } else {
        amountReturned.value = parseFloat(amountReceived.value) - parseFloat(totalAmount.value);
    }
});

watch(() => amountReceivedCash.value, (newValue) => {
     amountReceived.value = parseFloat(amountToPayCard.value) + parseFloat(amountReceivedCash.value);
});

watch(() => purchaseType.value, (newValue) => {

    if(newValue == 'abonado' && selectedPromotion.value) {
        toast('Una vez seleccionada una promoción no sera posible la compra de abonos', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return
    }
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

watch(() => installmentSale.value, () => {
    if(!installmentSale.value){
        saleDeptorSelected.value = null;
        firstNameSaleDeptor.value = '';
        lastNameSaleDeptor.value = '';
        phoneSaleDeptor.value = '';
    }else{
        if(amountReceived.value == 0){
            amountReturned.value = 0;
        }else{
            if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')){
                amountReturned.value = parseFloat(amountReceived.value) - parseFloat(amountReceivedCash.value);
            }else {
                amountReturned.value = parseFloat(totalAmount.value) - parseFloat(amountReceived.value);
            }
        }
        saleDeptorSelected.value = 1;

        if(purchaseType.value == 'abonado'){
            const owner = seatsSelected.value.find(seat => seat.is_owner == 'Si');
            firstNameSaleDeptor.value = owner.holder_name;
            lastNameSaleDeptor.value = owner.holder_last_name;
            phoneSaleDeptor.value = owner.holder_phone;
        } else {
            amountToPayCard.value = 0;
            amountToPayCash.value = 0;
        }

        if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta')) {
            amountReturned.value = 0;
        }
        if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'efectivo')) {
            if(amountReceivedCash.value){
                amountReceived.value = parseFloat(amountToPayCard.value) + parseFloat(amountReceivedCash.value);
                amountReturned.value = parseFloat(amountReceived.value) - parseFloat(amountToPayCash.value);
            }
        }
    }
});


const onSubmit = () => {

    if(installmentSale.value){

        if(saleDeptorSelected.value ){
            if(saleDeptorSelected.value == 1){
                if(!firstNameSaleDeptor.value || !lastNameSaleDeptor.value || !phoneSaleDeptor.value){
                    valid.value = false;
                    error.value = 'Debe de seleccionar un deudor para la venta a crédito o llenar los campos de nombre, apellido y teléfono';
                    return;
                }
            }
        } else{
            valid.value = false;
            error.value = 'Debe de seleccionar un deudor para la venta a crédito';
            return;
        }
        if(paymentTypesSelected.value.some(type => type.name === 'cortesia')){
            valid.value = false;
            error.value = 'No se puede realizar una venta a crédito con cortesía';
            return;
        }
        if(paymentTypesSelected.value.length > 1){
            valid.value = false;
            error.value = 'Por el momento no se puede realizar una venta a crédito con mas de un tipo de pago';
            return;
        }
        form.value = true;
    }



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

    if(purchaseType.value == 'abonado' && !seasonTicktesData.value){
        valid.value = false;
        error.value = 'Los datos de los abonado deben ser confirmados';
        return;
    }

    if(paymentTypesSelected.value.some(type => type.name === 'efectivo' )){
        //validar que el monto recibido para efectivo sea igual o mayor al monto a pagar para efectivo
        if(parseFloat(amountReceivedCash.value) < parseFloat(amountToPayCash.value) && !installmentSale.value) {
            valid.value = false;
            error.value = 'El monto recibido en efectivo debe ser igual o mayor al monto a pagar en efectivo';
            return;
        }
    }

    if(paymentTypesSelected.value.length == 1 && paymentTypesSelected.value.some(type => type.name === 'tarjeta') && !installmentSale.value) {
        amountToPayCard.value = totalAmount.value;
    }

    if(paymentTypesSelected.value.some(type => type.name === 'tarjeta') && installmentSale.value){
        if(!cardPaymentTypesSelected.value){
            valid.value = false;
            error.value = 'Debe seleccionar un tipo de tarjeta para la venta a crédito';
            return;
        }
    }

    globalPaymentTypes.value = paymentTypesSelected.value.map((item) => {
        if(item.name === 'tarjeta') {
            return {
                id: item.id,
                global_card_payment_type_id: cardPaymentTypesSelected.value.id,
                amount: amountToPayCard.value,
                name: item.name,
            }
        }
        if(item.name === 'efectivo') {
            return {
                id: item.id,
                global_card_payment_type_id: null,
                amount: amountToPayCash.value,
                name: item.name,
            }
        }
        if(item.name === 'plazos') {
            return {
                id: item.id,
                global_card_payment_type_id: null,
                amount: 0,
                name: item.name,
            }
        }
        if(item.name === 'cortesia') {
            if(reasonAgreementSelected.value){
                if(reasonAgreementSelected.value.name != 'otro'){
                    reasonAgreementDescription.value = reasonAgreementSelected.value.name;
                }
                return {
                    id: item.id,
                    global_card_payment_type_id: null,
                    amount: 0,
                    reason_agreement_id:  reasonAgreementSelected.value.id,
                    reason_agreement: reasonAgreementDescription.value,
                    name: item.name,

                }
            }else{
                return {
                    id: item.id,
                    global_card_payment_type_id: null,
                    amount: 0,
                    name: item.name,
                }
            }
        }
    });
    // validar que al recorrer globalPaymentTypes.amount sea igual al totalamount
    let totalFinal = 0;
    globalPaymentTypes.value.forEach((item) => {
        totalFinal += parseFloat(item.amount);
    });

    if(totalFinal != totalAmount.value && !installmentSale.value) {
        valid.value = false;
        error.value = 'El monto total no coincide con el monto a pagar de los tipos de pago seleccionados';
        return;
    }

    if(purchaseStatus.value == 'retry') {
        valid.value = false;
        error.value = 'Estas en el proceso final de compra, si se require agregar otro asiento cancele la seleccion actual y reintente.';
        return;
    }

    if(purchaseType.value == 'abonado'){
        const atLeastOneHolder = seatsSelected.value.filter(seat => seat.is_owner == 'Si').length;

        if(atLeastOneHolder != 1){
            valid.value = false;
            error.value = 'Debe de haber un titular de compra en abonados';
            return;
        }
    }

    if(installmentSale.value && purchaseType.value == 'abonado') {
        const amountToPay = amountReceived.value - amountReturned.value;
        const seatsTotal = seatsSelected.value.length;
        if((seatsTotal * 500) > amountToPay) {
            toast('El monto a pagar no puede ser menor a 500 por asiento', {
                "theme": "auto",
                "type": "error",
                "dangerouslyHTMLString": true
            })
            valid.value = false;
            error.value = 'El monto a pagar no puede ser menor a 500 por asiento';
            return;
        }
    }

    const onSubmitConfirmDialog = document.getElementById('on-submit-confirm');
    onSubmitConfirmDialog.click();

}

// Crear referencias reactivas para las zonas
const aZone = ref([...props.a_zone]);
const bZone = ref([...props.b_zone]);
const cZone = ref([...props.c_zone]);

// Watcher para actualizar las referencias reactivas cuando los props cambian
watch(() => props.a_zone, (newVal) => {
    aZone.value = [...newVal];
});
watch(() => props.b_zone, (newVal) => {
    bZone.value = [...newVal];
});
watch(() => props.c_zone, (newVal) => {
    cZone.value = [...newVal];
});

// Función para actualizar los estados de los asientos
const updateZones = (seatsSelectedData) => {

    seatsSelectedData.seats.forEach(seat => {
        const zone = seat.seat_catalogue.zone;
        const seatCatalogueId = seat.seat_catalogue_id;

        if (zone === 'A') {
            const seatToUpdate = aZone.value.find(s => s.seat_catalogue_id === seatCatalogueId);
            if (seatToUpdate) {
                seatToUpdate.seat_catalogue_status.name = 'vendido';
            }
        } else if (zone === 'B') {
            const seatToUpdate = bZone.value.find(s => s.seat_catalogue_id === seatCatalogueId);
            if (seatToUpdate) {
                seatToUpdate.seat_catalogue_status.name = 'vendido';
            }
        } else if (zone === 'C') {
            const seatToUpdate = cZone.value.find(s => s.seat_catalogue_id === seatCatalogueId);
            if (seatToUpdate) {
                seatToUpdate.seat_catalogue_status.name = 'vendido';
            }
        }
    });

    // Actualizar las referencias reactivas
    aZone.value = [...aZone.value];
    bZone.value = [...bZone.value];
    cZone.value = [...cZone.value];
};

const showButtonPayment = ref(false);

const showPaymentDrawer = () => {
    drawerPaymentState.value = true;
};

const onSubmitConfirm = (isActive) => {

    if(!acceptTerms.value) {
        toast('Debe aceptar los términos y condiciones para continuar', {
            "theme": "auto",
            "type": "error",
            "autoClose": 10000,
            "dangerouslyHTMLString": true
        })
        return;
    }

    loadingg.value = true;
    loading.value = true;
    const isTransfer = userToTransfer.value ? true : false;
    saleDebtorData.value = {
        id: saleDeptorSelected.value,
        first_name: firstNameSaleDeptor.value,
        last_name: lastNameSaleDeptor.value,
        phone_number: phoneSaleDeptor.value,
        stadium_id: props.event.stadium_id,
    }

    const seatsSelectedData = {
        purchase_type: purchaseType.value,
        stadium_id: props.event.stadium_id,
        ticket_office_id: ticketOfficeId.value,
        event_id: props.event.id,
        cash_register_id: cashRegisterDataId.value,
        member_user_id: purchaseOnline.value ? props.user.id : (isTransfer ? userToTransfer.value : null),
        seller_user_id: sellerUserId.value,
        price_type_id: priceTypeId.value,
        seats: seatsSelected.value,
        amount_received: amountReceived.value,
        total_amount: totalAmount.value,
        total_returned: amountReturned.value,
        payment_in_installments: paymentInstallmentSelected.value,
        global_payment_types: globalPaymentTypes.value,
        is_online: purchaseOnline.value,
        serie_id: props.event.serie_id,
        is_transfer: isTransfer,
        user_to_transfer: userToTransfer.value,
        final_promotion: finalPromotion.value,
        sale_debtor: saleDebtorData.value,
    };

    /*console.log(seatsSelectedData);
    return*/

axios.post(route('events.reserve-seats-to-buy'), seatsSelectedData)
    .then(response => {

        if (response.data.success && purchaseOnline.value) {
            showButtonPayment.value = true;
            drawerPaymentState.value = true;
        }
        toast(response.data.message, {
            "theme": "auto",
            "type": "success",
            "dangerouslyHTMLString": true
        })

        // Actualiza el estado de los asientos comprados
        updateZones(seatsSelectedData);


        if(response.data.pdf) {
            const pdfContent = atob(response.data.pdf);
            const pdfBlob = new Blob([new Uint8Array([...pdfContent].map(char => char.charCodeAt(0)))], { type: 'application/pdf' });
            const pdfUrl = window.URL.createObjectURL(pdfBlob);
            printInKioskMode(pdfUrl, purchaseType.value);
            selectZones();
            setTimeout(() => {
                selectZones();
            }, 100);
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
        isActive.value = false;
        loading.value = false;
        loadingg.value = false;
        if(viewVendorTopics(props.user_roles)) {
            tab.value = 'seats';
        }
    });


}

const rules = {
    required: value => !!value || 'Campo requerido',
    isNumber: value => !isNaN(value) || 'Debe ser un número',
    isEmail: value => {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(value) || 'Correo electrónico inválido';
    },
    minChar: value => value.length >= 3 || 'Debe tener un mínimo de 3 caracteres',
    phoneNumber: value => {
        const phoneNumber = value.replace(/\D/g, '');
        return phoneNumber.length === 10 || 'Número de teléfono inválido (10 dígitos)';
    },
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

function printInKioskMode(url, purchaseType) {
    const ventana = window.open(url, '_blank', 'fullscreen=yes,kiosk=yes');
    // ventana.onload = () => {
    //     ventana.print();
    //     if(purchaseType != 'abonado') {
    //         setTimeout(() => {
    //             ventana.close();
    //         }, 4000);
    //     }
    // };
}

/*
* Season tickets
*/
const paymentInstallmentSelected = ref(null);
const seasonTicketsDialog = ref(false);
const seasonTicktesData = ref(false);
const seasonTicketsForm = ref(false);

const updateHolder = (index) => {
    seatsSelected.value.forEach((seat, i) => {
        if(i !== index){
            seat.is_owner = 'No';
        }
    });
}

const seasonTicketsDialogOpen = () => {
    seasonTicketsDialog.value = true;
}

watch(purchaseType, () => {
    if(purchaseType.value == 'abonado' && selectedPromotion.value) {
        return
    }
    if(purchaseType.value == 'abonado'){
        seasonTicketsDialog.value = true;
    }
    amountToPayCash.value = 0;
    amountReceivedCash.value = 0;
    amountToPayCard.value = 0;
    updateTotal();

})

watch(seatsSelected, (newSeats) => {
    newSeats.forEach((seat, index) => {
        if(seat.is_owner === 'Si'){
            updateHolder(index);
        }
    });
}, { deep:true })

const seasonTicktesDataConfirm = () => {
    if(!seasonTicketsForm.value) return
    seasonTicketsDialog.value = false;
    seasonTicktesData.value = true;
    toast('Los datos de los abonos han sido guardados', {
        "theme": "auto",
        "type": "success",
        "dangerouslyHTMLString": true
    })
}

const cardPaymentTypeError = computed(() => {
    return rules.required(cardPaymentTypesSelected.value) !== true;
});

const globalPaymentTypesOnlyCard = ref([]);
watch(() => paymentInstallmentSelected.value, () => {
    if(paymentInstallmentSelected.value){
        paymentTypesSelected.value = globalPaymentTypesOnlyCard.value;
        installmentSale.value = false;
    }
})

watch(() => agreementSelected.value, () => {
    console.log(agreementSelected.value);
});
</script>

<template>
    <Head title="Evento" />
    <!-- <GuestLayout  v-bind:user_roles="user_roles" v-bind:isEventsShow="isEventsShow"/> -->
   <!--  <NavigationDrawer v-bind:user_roles="user_roles"/> -->
   <!--  <SuccessSession /> -->
    <AppNav/>
    <CashRegisterNav v-bind:user_roles="user_roles"/>
    <transition name="fade">
        <div
            v-if="showImageModal"
            class="tw-fixed tw-inset-0 !tw-z-50 tw-flex tw-items-center tw-justify-center tw-bg-black/50 tw-backdrop-blur-[7px] tw-transition-all tw-duration-500"
            @click.self="closeImageModal"
        >
            <div class="tw-bg-gradient-to-tr tw-from-primary tw-to-yellow-300 tw-shadow-2xl tw-rounded tw-p-3 tw-relative tw-max-w-full tw-w-[90vw] md:tw-w-[520px] tw-flex tw-flex-col tw-items-center tw-transition-all tw-duration-500">
                <img class="tw-w-full tw-h-auto" :src="modalImageSrc" alt="Imagen ampliada" />
            </div>
        </div>
    </transition>
    <v-dialog max-width="500" max-height="300">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn id="seller-dialog" v-bind="activatorProps" variant="elevated" class="!tw-hidden" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
        </template>
        <template v-slot:default="{ isActive }">
            <v-card>
            <v-card-text class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-text-center tw-mt-1">
                <h1 class="tw-text-xl">Se debe abrir una caja para usar esta seccion como taquilla.</h1>
            </v-card-text>
                <Link
                    :href="route('ticket-offices.index')"
                    >
                    <div class="tw-w-full tw-flex tw-items-center tw-justify-center tw-mb-5">
                        <PrimaryButton>
                            <div class="tw-flex tw-items-center tw-justify-center tw-gap-1">
                                <p>Abrir caja</p>
                            </div>
                        </PrimaryButton>
                    </div>
                </Link>
            </v-card>
        </template>
    </v-dialog>

    <transition name="slide">
        <div  v-if="seatsSelected.length > 0 && tab == 'seats'" class="tw-hidden tw-fixed lg:tw-top-7 tw-rounded-lg tw-shadow-xl tw-p-2 tw-right-3 tw-max-h-60 tw-overflow-y-auto tw-w-60 tw-bg-white tw-z-[60] lg:tw-flex tw-items-center tw-justify-center">
            <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                <thead>
                    <tr>
                    <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                        <span class="tw-text-xs tw-uppercase">
                            asiento
                        </span>
                    </th>

                    <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                        <span class="tw-text-xs tw-uppercase">
                        precio
                        </span>
                    </th>
                    <th scope="col" class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                        <span class="tw-text-xs tw-uppercase">
                            Acción
                        </span>
                    </th>
                    </tr>
                </thead>

                <tbody class="tw-divide-y tw-divide-gray-200">
                    <tr v-for="seat in seatsSelected" :key="seat.seat_catalogue.code">
                    <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                        <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.zone }}{{ seat.seat_catalogue.row }}{{ seat.seat_catalogue.seat }}</span>
                    </td>
                    <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                        <span class="tw-text-sm tw-text-green-600">
                            <div v-for="priceType in seat.price_types" :key="priceType.id">
                                <div>
                                    <span v-if="priceType.name === (purchaseType == 'abonado' ? 'abonado' : 'regular')">
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
    </transition>
    <transition name="slide">
        <div  v-if="seatsSelected.length > 0 && tab == 'seats'" @click="scrollTopaymentSection" class="tw-fixed tw-bottom-5 lg:tw-bottom-16 tw-right-3 tw-z-[60]">
            <div class="tw-flex tw-items-center tw-text-xs lg:tw-text-base tw-gap-2 tw-justify-center tw-bg-gradient-to-r tw-from-green-500 tw-to-cyan-500 tw-text-white tw-cursor-pointer hover:tw-scale-105 tw-transition-transform tw-duration-500 tw-px-4 lg:tw-px-6 tw-py-3 lg:tw-py-4 tw-rounded-2xl">
                <span class="material-symbols-outlined tw-z-20 tw-text-xl lg:tw-text-xl">arrow_forward</span>Procesar Compra
            </div>
        </div>
    </transition>
    <div v-if="tab == 'payment'" @click="tab = 'seats'" class="tw-fixed tw-bottom-5 lg:tw-bottom-16 tw-right-3 tw-z-[60]">
        <div class="tw-flex tw-items-center tw-text-xs lg:tw-text-base tw-gap-2 tw-justify-center tw-bg-gradient-to-r tw-from-primary tw-to-cyan-500 tw-text-white tw-cursor-pointer hover:tw-scale-105 tw-transition-transform tw-duration-500 tw-px-4 lg:tw-px-6 tw-py-3 lg:tw-py-4 tw-rounded-2xl">
            <span class="material-symbols-outlined tw-z-20 tw-text-xl lg:tw-text-xl">arrow_back</span>Seguir comprando
        </div>
    </div>

    <div>
        <PaymentDrawer
            v-bind:purchaseType="purchaseType"
            v-bind:stadiumId="event.stadium_id"
            v-bind:ticketOfficeId="ticketOfficeId"
            v-bind:eventId="event.id"
            v-bind:cashRegisterId="cashRegisterDataId"
            v-bind:memberUserId="user.id"
            v-bind:sellerUserId="sellerUserId"
            v-bind:priceTypeId="priceTypeId"
            v-bind:seats="seatsSelected"
            v-bind:amountReceived="amountReceived"
            v-bind:totalAmount="totalAmount"
            v-bind:amountReturned="amountReturned"
            v-bind:paymentInInstallments="paymentInstallmentSelected"
            v-bind:globalPaymentTypes="globalPaymentTypes"
            v-bind:isOnline="purchaseOnline"
            v-bind:serieId="event.serie_id"
            v-bind:finalPromotion="finalPromotion"
            v-bind:saleDebtorData="saleDebtorData"
        />
    </div>

    <div v-if="showPromotionToast" class="tw-fixed tw-bottom-36 tw-right-3 tw-z-[60]">
        <v-bottom-sheet>
            <template v-slot:activator="{ props }">
                <div v-bind="props" class="tw-relative">
                    <div class="tw-flex tw-items-center tw-justify-center -tw-rotate-45 tw-cursor-pointer hover:tw-scale-105 tw-transition-transform tw-duration-700">
                        <div class="tw-bg-gradient-to-r tw-from-purple-500 tw-to-purple-400 tw-w-12 tw-h-12 tw-rounded-full tw-flex tw-items-center tw-justify-center">
                            <span class="material-symbols-outlined tw-z-20 tw-rotate-45 tw-text-white tw-text-xl lg:tw-text-2xl">featured_seasonal_and_gifts</span>
                        </div>
                        <div class="tw-z-10 tw-absolute tw-bottom-0 tw-left-1/2 tw-transform -tw-translate-x-1/2 tw-translate-y-[20%] tw-w-6 tw-h-6 tw-bg-purple-500 tw-rotate-45 tw-rounded-[4px]"></div>
                    </div>
                    <div class="tw-absolute tw-animate-bounce tw-bottom-full tw-right-0 tw-transform tw-w-[100px] tw-text-center -tw-translate-x-1/2 tw-mb-1 tw-px-2 tw-flex tw-items-center tw-justify-center tw-py-1 tw-shadow-xl tw-bg-gradient-to-r tw-from-purple-500 tw-to-yellow-500 tw-text-white tw-rounded-full">
                        <span class="tw-text-[10px] tw-block">Abrir promociones</span>
                        <div class="tw-absolute tw-bottom-[-5px] tw-left-1/2 tw-transform tw-border-l-[6px] tw-border-l-transparent tw-border-r-[6px] tw-border-r-transparent tw-border-t-[6px] tw-border-t-purple-500"></div>
                    </div>
                </div>
            </template>

            <v-card>
                <v-col v-if="showPromotionToast" cols="12">
                    <h3 class="tw-text-center tw-font-bold tw-text-lg tw-mb-3 tw-text-gray-700">Selecciona una promoción:</h3>
                    <v-radio-group v-model="selectedPromotion" inline>
                        <div v-for="(promotion, index) in promotionTypesCo" :key="index">
                                <div v-if="promotion.percent_allow > 0">
                                    <div class="tw-border-4 tw-border-yellow-500 tw-rounded-xl tw-bg-white tw-p-2 tw-m-3">
                                        <v-radio color="yellow" :key="index" :value="promotion">
                                            <template v-slot:label>
                                                <div>{{ promotion.description }} de {{ promotion.percent_allow }}% ({{ formatFirstLetterUppercase(promotion.type) }})<strong class="tw-text-yellow-700">. Asientos de {{ formatPrice(promotion.final_price) }}</strong></div>
                                            </template>
                                        </v-radio>
                                    </div>
                                </div>
                                <div v-else-if="promotion.quantity > promotion.generic_seats_allowed">
                                    <div class="tw-border-4 tw-border-purple-500 tw-rounded-xl tw-bg-white tw-p-2 tw-m-3">
                                        <v-radio color="purple" :key="index" :value="promotion">
                                            <template v-slot:label>
                                                <div>{{ promotion.description }}<strong class="tw-text-purple-700">. Para asientos con precio de {{ formatPrice(promotion.final_price) }}</strong></div>
                                            </template>
                                        </v-radio>
                                    </div>
                                </div>
                        </div>
                    </v-radio-group>
                </v-col>
            </v-card>
        </v-bottom-sheet>
    </div>

    <div
        class="tw-flex tw-bg-cover tw-relative tw-min-h-screen lg:tw-min-h-[700px] tw-aspect-3/4 tw-object-cover tw-bg-center tw-w-full tw-p-4 lg:tw-p-7 tw-shadow-xl tw-overflow-hidden tw-transition-all tw-duration-500"
        :style="`background-image: url(/storage/${event.global_image.file_path})`"
        >
        <div class="tw-absolute tw-top-10 tw-mx-auto tw-w-full tw-text-white tw-z-20 tw-px-1">
            <GuestNav/>
        </div>
        <div class="tw-max-w-7xl tw-mx-auto tw-z-10 tw-flex tw-flex-col tw-flex-1 tw-justify-end tw-text-white">
            <div class="tw-flex-col tw-gap-4 tw-justify-end tw-w-full">
                <div data-aos="fade-left" data-aos-duration="1300" data-aos-once="true" class="tw-flex lg:tw-items-center tw-flex-col lg:tw-flex-row tw-justify-between tw-gap-5 lg:tw-gap-10">
                    <div>
                        <Link :href="route('events.index')">
                            <div class="tw-size-10 tw-inline-flex tw-shadow-md tw-rounded-full tw-bg-primary tw-p-2 tw-items-center tw-justify-center tw-mb-3">
                                <span class="material-symbols-outlined tw-text-white">arrow_back</span>
                            </div>
                        </Link>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-center tw-gap-2">
                            <p class="lg:tw-text-lg">{{ event.description }}</p>
                            <v-rating
                                readonly
                                :length="5"
                                :size="26"
                                :model-value="5"
                                active-color="yellow"
                            />
                        </div>
                        <h2 class="tw-inline-block tw-font-bebas tw-pr-1 tw-mt-1 tw-text-6xl lg:tw-text-8xl tw-font-bold tw-bg-clip-text tw-bg-gradient-to-r tw-from-primary tw-to-secondary tw-text-transparent">
                            {{ event.name }}
                        </h2>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-center tw-gap-4 tw-mt-3 lg:tw-mt-2">
                            <div class="tw-flex tw-items-center tw-gap-1">
                                <span class="material-symbols-outlined lg:tw-text-2xl tw-block">calendar_today</span>
                                <h3 class="lg:tw-text-lg">{{ event.serie.global_season.name }}</h3>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-1">
                                <span class="material-symbols-outlined lg:tw-text-2xl tw-block">access_time</span>
                                <h3  class="lg:tw-text-lg">{{ dateFormat(event.start_date) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="tw-flex tw-items-center tw-gap-6 tw-mb-10 lg:tw-mb-0">
                        <div class="tw-border-2 tw-cursor-pointer tw-h-[150px] tw-w-[100px] lg:tw-h-[200px] lg:tw-w-[150px] tw-object-cover tw-bg-center tw-bg-cover tw-relative tw-overflow-hidden tw-rounded" @click="openImageModal(`/storage/${event.global_image.file_path}`)"  :style="`background-image: url(/storage/${event.global_image.file_path})`">
                             <div class="tw-absolute tw-inset-0 tw-bg-black/40 tw-opacity-100 tw-z-20 tw-flex tw-items-center tw-justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-10 tw-h-10 tw-text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2"/><line x1="11" y1="8" x2="11" y2="14" stroke="currentColor" stroke-width="2"/><line x1="8" y1="11" x2="14" y2="11" stroke="currentColor" stroke-width="2"/></svg>
                            </div>
                        </div>
                        <iframe class="tw-rounded tw-h-[150px] tw-w-[100px] lg:tw-h-[200px] lg:tw-w-[150px]" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.653263134143!2d-96.91874712501097!3d19.51354808178317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85db320be3350bd1%3A0xba83c38e6e168a4!2sGimnasio%20Nido%20del%20Halc%C3%B3n%20UV!5e0!3m2!1ses-419!2smx!4v1735482228924!5m2!1ses-419!2smx" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="tw-z-0 tw-absolute tw-backdrop-blur-md tw-bottom-0 tw-left-0 tw-right-0 tw-h-full !tw-bg-[linear-gradient(180deg,rgba(0,0,0,0)_-30%,#000_90%)] tw-block"></div>
    </div>

    <div ref="paymentSection"></div>

    <div class="tw-bg-white tw-w-full tw-overflow-hidden">
        <main  class="tw-min-h-screen tw-max-w-7xl tw-mx-auto tw-pt-20 tw-relative">
            <div class="tw-absolute -tw-right-40 lg:-tw-right-96 -tw-top-52 lg:-tw-top-52 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[500px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
            </div>
            <v-tabs
                class="!tw-bg-white"
                color="deep-purple-accent-4"
                fixed-tabs
                v-model="tab"
            >
                <v-tab value="seats" class="!tw-text-2xl !tw-font-bold !tw-font-bebas">Selección de asientos</v-tab>
                <v-tab value="payment" class="!tw-text-2xl !tw-font-bold !tw-font-bebas">Procesar compra</v-tab>
            </v-tabs>
            <v-card-text class="!tw-mt-10">
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="seats">
                        <div class="tw-w-full">
                            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start tw-justify-center tw-gap-10 lg:tw-gap-16">
                                <div class="tw-grid tw-grid-cols-3 tw-items-start tw-justify-center tw-gap-5 tw-w-full lg:tw-w-auto">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-yellow-500"></div>
                                        <p class="tw-text-center tw-text-sm">Disponible</p>
                                    </div>
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-purple-500"></div>
                                        <p class="tw-text-center tw-text-sm">Vendido</p>
                                    </div>
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-green-500"></div>
                                        <p class="tw-text-center tw-text-sm">Seleccionado</p>
                                    </div>
                                    <div v-if="viewVendorTopics(props.user_roles)" class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-pink-600"></div>
                                        <p class="tw-text-center tw-text-sm">Reservado <br> para abonado</p>
                                    </div>
                                    <div v-if="viewVendorTopics(props.user_roles)" class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-gray-600"></div>
                                        <p class="tw-text-center tw-text-sm">Inhabilitado</p>
                                    </div>
                                    <div v-if="viewVendorTopics(props.user_roles)" class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-col">
                                        <div class="tw-size-14 tw-rounded-t-2xl tw-rounded-b-md tw-bg-cyan-500"></div>
                                        <p class="tw-text-center tw-text-sm">En transito</p>
                                    </div>
                                </div>
                                <div v-if="seatAvailability.length > 0" class="tw-w-full lg:tw-w-auto tw-grid tw-grid-cols-3 tw-gap-5 tw-items-center tw-justify-center">
                                    <div v-for="(availability, index) in seatAvailability" :key="index">
                                        <div class="tw-p-3 tw-border-2 tw-rounded-lg tw-bg-white tw-text-center">
                                            <p class="tw-text-[10px] lg:tw-text-xs tw-font-bold">Zona {{ availability.zone }}</p>
                                            <p class="tw-text-[10px] lg:tw-text-xs">{{ availability.available_seats }} <br> asientos libres</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="tw-w-full lg:tw-w-auto tw-grid tw-grid-cols-3 tw-gap-5 tw-items-center tw-justify-center">
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                    <div class="tw-h-[72px] tw-w-[100px] tw-rounded-lg tw-bg-gray-300 tw-animate-pulse"></div>
                                </div>
                            </div>
                            <div class="tw-my-10">
                                <div class="tw-w-full tw-relative">
                                    <div class="tw-mt-7 tw-w-full">
                                        <div class="tw-flex tw-flex-col tw-gap-3 tw-justify-between mb-4 tw-w-full">
                                            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-w-full tw-gap-3 tw-my-3">
                                                <div class="tw-flex tw-items-center tw-gap-3 tw-flex-col md:tw-flex-row">
                                                    <div class="tw-flex tw-items-center tw-gap-3">
                                                        <v-btn @click="zoomIn" variant="tonal" class="!tw-h-[50px] lg:!tw-h-[60px] !tw-px-9 lg:!tw-px-12 !tw-bg-white !tw-border-2 !tw-border-neutral-300 !tw-rounded-2xl !tw-text-neutral-700">
                                                            <span class="material-symbols-outlined tw-text-2xl">add</span>zoom
                                                        </v-btn>
                                                        <v-btn @click="zoomOut" variant="tonal" class="!tw-h-[50px] lg:!tw-h-[60px] !tw-px-9 lg:!tw-px-12 !tw-bg-white !tw-border-2 !tw-border-neutral-300 !tw-rounded-2xl !tw-text-neutral-700">
                                                            <span class="material-symbols-outlined tw-text-2xl">remove</span>zoom
                                                        </v-btn>
                                                    </div>
                                                </div>
                                                <div class="tw-items-center tw-flex-col tw-gap-1 tw-hidden lg:tw-flex tw-relative">
                                                    <h3 class="tw-font-bold tw-font-bebas tw-text-4xl">{{ viewSelectedSection}}</h3>
                                                    <div class="tw-border-2 tw-cursor-pointer tw-h-[50px] tw-w-[100px] tw-object-cover tw-bg-center tw-bg-cover tw-relative tw-overflow-hidden tw-rounded" @click="openImageModal(`/storage/public/show-hdx.jpg`)"  :style="`background-image: url(/storage/public/show-hdx.jpg)`">
                                                        <div class="tw-absolute tw-inset-0 tw-bg-black/50 tw-opacity-100 tw-z-20 tw-flex tw-items-center tw-justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-7 tw-h-7 tw-text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2"/><line x1="11" y1="8" x2="11" y2="14" stroke="currentColor" stroke-width="2"/><line x1="8" y1="11" x2="14" y2="11" stroke="currentColor" stroke-width="2"/></svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tw-flex tw-items-center tw-gap-3">
                                                    <v-btn @click="resetZoom" variant="tonal" class="!tw-h-[50px] lg:!tw-h-[60px] !tw-px-9 lg:!tw-px-12 !tw-bg-white !tw-border-2 !tw-border-neutral-300 !tw-rounded-2xl !tw-text-neutral-700">
                                                        <span class="material-symbols-outlined tw-text-2xl">my_location</span>reset
                                                    </v-btn>
                                                    <v-btn @click="selectZones" variant="tonal" class="!tw-h-[50px] lg:!tw-h-[60px] !tw-px-9 lg:!tw-px-12 !tw-bg-white !tw-border-2 !tw-border-neutral-300 !tw-rounded-2xl !tw-text-neutral-700">
                                                        <span class="material-symbols-outlined tw-text-2xl">location_on</span>zonas
                                                    </v-btn>
                                                </div>
                                            </div>

                                            <div class="tw-flex tw-h-[400px] tw-cursor-grab lg:tw-h-[500px] tw-items-center tw-justify-center tw-overflow-hidden tw-bordertw-mt-5 tw-gap-3 tw-relative">
                                                <div class="tw-size-[100px] lg:tw-size-36 tw-border tw-border-gray-300 tw-absolute tw-top-0 tw-left-0 tw-z-20 tw-bg-white tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                                    <img id="stadium-hdx-img" class="tw-size-20 lg:tw-size-32 tw-rotate-0 tw-transition-all tw-duration-1000" src="../../../../../public/img/stadium-hdx-img.svg" alt="">
                                                </div>
                                                <div v-if="isSvgVisible">
                                                    <EstadioHdx  @handle-section-click="handleSectionClick"/>
                                                </div>
                                                <div v-if="selectedSection == 'zonaA'" class="">
                                                    <ZonaA v-bind:purchaseOnline="purchaseOnline" @add-seat="addSeat" v-bind:seats="seatsASection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                                <div v-if="selectedSection == 'zonaB'" class="">
                                                    <ZonaB @add-seat="addSeat" v-bind:purchaseOnline="purchaseOnline" v-bind:seats="seatsBSection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                                <div v-if="selectedSection == 'zonaC'" class="">
                                                    <ZonaC @add-seat="addSeat" v-bind:purchaseOnline="purchaseOnline" v-bind:seats="seatsCSection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                                <div v-if="selectedSection == 'zonaE'" class="">
                                                    <ZonaE @add-seat="addSeat" v-bind:purchaseOnline="purchaseOnline" v-bind:seats="seatsESection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                                <div v-if="selectedSection == 'zonaF'" class="">
                                                    <ZonaF @add-seat="addSeat" v-bind:purchaseOnline="purchaseOnline" v-bind:seats="seatsFSection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                                <div v-if="selectedSection == 'zonaH'" class="">
                                                    <ZonaH @add-seat="addSeat" v-bind:purchaseOnline="purchaseOnline" v-bind:seats="seatsHSection" v-bind:seatsSelected="seatsSelected" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="loading-section-dialog">
                                        <v-dialog fullscreen v-model="loadingSectionDialog" transition="dialog-bottom-transition">
                                            <template v-slot:activator="{ props: activatorProps }">
                                                <v-btn v-bind="activatorProps" variant="elevated" class="!tw-hidden text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                            </template>
                                            <template v-slot:default="{ isActive }">
                                                <div class="tw-w-full tw-h-full">
                                                    <div class="tw-h-screen tw-flex tw-items-center tw-justify-center tw-w-full">
                                                        <div class="tw-p-3 tw-animate-spin tw-drop-shadow-2xl tw-bg-gradient-to-bl tw-from-pink-400 tw-via-purple-400 tw-to-indigo-600 tw-md:w-48 tw-md:h-48 tw-h-32 tw-w-32 tw-aspect-square tw-rounded-full">
                                                            <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full tw-h-full tw-w-full tw-bg-white tw-dark:bg-zinc-900 tw-background-blur-md">
                                                                <img class="tw-w-14 tw-h-auto" src="https://halconesdexalapa.com.mx/wp-content/uploads/2024/01/cropped-SIMBOLO-HDX-2023-e1705427673690-1.png" alt="img logo">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <v-card>
                                                    <v-card-actions>
                                                            <v-btn @click="isActive.value = false"></v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </template>
                                        </v-dialog>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-tabs-window-item>

                    <v-tabs-window-item value="payment">
                        <div class="tw-w-full tw-max-w-4xl tw-mx-auto">
                            <div class="tw-w-full">
                                <div class="tw-w-full">
                                    <div class="lg:tw-px-5 tw-relative tw-flex tw-flex-col-reverse">
                                        <div v-if="seatsSelected.length == 0" class="tw-flex tw-flex-col tw-items-center tw-gap-10 tw-justify-center">
                                            <h3>No hay asientos seleccionados</h3>
                                            <img class="tw-w-40 lg:tw-w-96 tw-h-auto" src="/storage/public/emty-cart.webp" alt="">
                                        </div>
                                        <div v-if="seatsSelected.length > 0" class="payment-secction">
                                            <div class="tw-w-full ">
                                                <v-expansion-panels v-model="panel" multiple>
                                                    <v-expansion-panel class="!tw-px-3 lg:!tw-px-10 !tw-rounded-2xl !tw-py-2 !tw-border tw-shadow-lg">
                                                        <v-expansion-panel-title expand-icon="mdi-menu-down">
                                                            Asientos seleccionados
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
                                                                                    <div>
                                                                                        <span v-if="priceType.name === (purchaseType == 'abonado' ? 'abonado' : 'regular')">
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

                                                    <v-expansion-panel class="lg:!tw-px-10 !tw-rounded-2xl !tw-py-2 !tw-bg-transparent !tw-mt-9">
                                                        <v-expansion-panel-title expand-icon="mdi-menu-down">
                                                            Proceso de compra
                                                        </v-expansion-panel-title>
                                                        <v-form v-model="form" @submit.prevent="onSubmit" lazy-validation>
                                                            <v-expansion-panel-text>
                                                            <div v-if="viewVendorTopics(user_roles) && purchaseType != 'abonado'">
                                                               <div>
                                                                    <v-switch inset  label="¿Se requiere venta a cuotas?" color="purple" class="!tw-inline-flex !tw-mb-4" @click="paymentInInstallments"></v-switch>
                                                                </div>
                                                            </div>

                                                            <v-select
                                                                v-if="viewVendorTopics(user_roles) && !paymentInstallmentSelected"
                                                                color="purple"
                                                                label="selecciona el tipo de pago"
                                                                :item-props="globalPayementTypeProps"
                                                                :items="global_payment_types.filter(type => type.name !== 'plazos')"
                                                                chips
                                                                multiple
                                                                clearable
                                                                variant="outlined"
                                                                v-model="paymentTypesSelected"
                                                                :rules="[rules.required]"
                                                            ></v-select>
                                                            <v-select
                                                                v-if="viewVendorTopics(user_roles) && paymentInstallmentSelected"
                                                                color="purple"
                                                                label="selecciona el tipo de pago"
                                                                :item-props="globalPayementTypeProps"
                                                                :items="globalPaymentTypesOnlyCard"
                                                                chips
                                                                multiple
                                                                variant="outlined"
                                                                clearable
                                                                v-model="paymentTypesSelected"
                                                                :rules="[rules.required]"
                                                            ></v-select>

                                                            <div v-if="paymentTypesSelected.some(type => type.name === 'tarjeta')">
                                                                <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full tw-font-bold tw-text-purple-600 tw-text-center tw-mb-2">
                                                                    Complemento para pago con tarjeta
                                                                </h4>
                                                                <v-select
                                                                    color="purple"
                                                                    variant="outlined"
                                                                    clearable
                                                                    label="Selecciona el tipo de tarjeta"
                                                                    :item-props="globalCardPayementTypeProps"
                                                                    :items="global_card_payment_types"
                                                                    v-model="cardPaymentTypesSelected"
                                                                    :rules="[rules.required]"
                                                                    :error="cardPaymentTypeError"
                                                                    :error-messages="cardPaymentTypeError ? ['Este campo es obligatorio'] : []"
                                                                ></v-select>
                                                                <div v-if="!installmentSale && viewVendorTopics(user_roles)">
                                                                    <v-text-field
                                                                        label="Monto a pagar con tarjeta"
                                                                        class="!tw-mt-2"
                                                                        variant="outlined"
                                                                        color="purple"
                                                                        clearable
                                                                        v-model="amountToPayCard"
                                                                        :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                                    ></v-text-field>
                                                                </div>
                                                                <div v-else-if="installmentSale && viewVendorTopics(user_roles)">
                                                                    <v-text-field
                                                                        label="Monto a pagar con tarjeta"
                                                                        class="!tw-mt-2"
                                                                        variant="outlined"
                                                                        color="purple"
                                                                        clearable
                                                                        v-model="amountToPayCard"
                                                                        :rules="[rules.required, rules.isNumber]"
                                                                    ></v-text-field>
                                                                </div>
                                                                <v-text-field
                                                                    v-else
                                                                    label="Monto a pagar con tarjeta"
                                                                    class="!tw-mt-2"
                                                                    color="purple"
                                                                    variant="outlined"
                                                                    readonly
                                                                    v-model="amountToPayCard"
                                                                    :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                                ></v-text-field>
                                                            </div>

                                                            <div v-if="paymentTypesSelected.some(type => type.name === 'efectivo')">
                                                                <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full  tw-font-bold tw-text-green-600 tw-text-center tw-mb-2">
                                                                    Complemento para pago con efectivo
                                                                </h4>

                                                                <v-text-field
                                                                    label="Monto recibido para efectivo"
                                                                    color="purple"
                                                                    clearable
                                                                    variant="outlined"
                                                                    hint="Monto recibido por el cliente"
                                                                    v-model="amountReceivedCash"
                                                                    :rules="[rules.required, rules.isNumber]"
                                                                ></v-text-field>

                                                                <div v-if="!installmentSale && viewVendorTopics(user_roles)">
                                                                    <v-text-field
                                                                        label="Monto a pagar para efectivo"
                                                                        color="purple"
                                                                        clearable
                                                                        variant="outlined"
                                                                        hint="Monto a pagar por el cliente"
                                                                        v-model="amountToPayCash"
                                                                        :rules="[rules.required, rules.isNumber, rules.isAmountToPay]"
                                                                        ></v-text-field>
                                                                </div>
                                                                <div v-else-if="installmentSale && viewVendorTopics(user_roles)">
                                                                    <v-text-field
                                                                        label="Monto a pagar para efectivo"
                                                                        color="purple"
                                                                        clearable
                                                                        variant="outlined"
                                                                        hint="Monto a pagar por el cliente"
                                                                        v-model="amountToPayCash"
                                                                        :rules="[rules.required, rules.isNumber]"
                                                                        ></v-text-field>
                                                                </div>
                                                            </div>

                                                            <div v-if="paymentTypesSelected.some(type => type.name === 'cortesia')">
                                                                <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full  tw-font-bold tw-text-purple-600 tw-text-center tw-mb-2">
                                                                    Complemento para pago en cortesía
                                                                </h4>
                                                                <v-select
                                                                    v-if="viewVendorTopics(user_roles)"
                                                                    color="purple"
                                                                    label="selecciona el complemento a cortesía"
                                                                    hint="Rason de la cortesía"
                                                                    :item-props="reasonAgreementsProps"
                                                                    :items="reason_agreements"
                                                                    v-model="reasonAgreementSelected"
                                                                    variant="outlined"
                                                                    chips
                                                                    :rules="[rules.required]"
                                                                ></v-select>
                                                                <div v-if="reasonAgreementSelected && reasonAgreementSelected.name === 'otro'">
                                                                    <v-textarea
                                                                        class="tw-w-full"
                                                                        append-inner-icon="mdi-file-document"
                                                                        label="Rason especial de la cortesía"
                                                                        row-height="10"
                                                                        color="purple"
                                                                        variant="outlined"
                                                                        clearable
                                                                        rows="3"
                                                                        auto-grow
                                                                        v-model="reasonAgreementDescription"
                                                                        :rules="[rules.required, rules.minChar]"
                                                                ></v-textarea>
                                                                </div>
                                                            </div>
                                                            <div v-if="viewVendorTopics(user_roles)">
                                                                <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full tw-font-bold tw-text-green-600 tw-text-center tw-mb-2">
                                                                    Complemento para convenios
                                                                </h4>
                                                                <v-select
                                                                    color="purple"
                                                                    label="selecciona una instutucion"
                                                                    :item-props="institutionsProps"
                                                                    :items="institutions"
                                                                    v-model="institutionSelected"
                                                                    variant="outlined"
                                                                    clearable
                                                                    chips
                                                                ></v-select>
                                                                <div v-if="institutionSelected">
                                                                    <v-select
                                                                        v-if="viewVendorTopics(user_roles)"
                                                                        color="purple"
                                                                        label="selecciona un convenio"
                                                                        :item-props="institutionAgreementsProps"
                                                                        :items="agreementsByInstitutionSelected"
                                                                        chips
                                                                        v-model="agreementSelected"
                                                                        clearable
                                                                        variant="outlined"
                                                                        :rules="[rules.required]"
                                                                    ></v-select>
                                                                </div>
                                                                <div v-if="agreementSelected">
                                                                    <v-radio-group v-model="selectedAgreementPromotion">
                                                                        <div v-for="(promotion, index) in agreementSelected.promotions" :key="index">
                                                                            <div v-if="promotion.generic_seats_allowed ">
                                                                                <div class="tw-border-4 tw-border-yellow-500 tw-rounded-xl tw-bg-white tw-p-2 tw-m-3">
                                                                                    <v-radio color="yellow" :key="index" :value="promotion">
                                                                                        <template v-slot:label>
                                                                                            <div>{{ promotion.name }}<strong class="tw-text-yellow-700">. Promocion por asientos</strong></div>
                                                                                        </template>
                                                                                    </v-radio>
                                                                                </div>
                                                                            </div>
                                                                            <div v-if="promotion.percent_allow > 0 && promotion.promotion_type.name == 'descuento_por_porcentaje_por_compra'">
                                                                                <div class="tw-border-4 tw-border-blue-500 tw-rounded-xl tw-bg-white tw-p-2 tw-m-3">
                                                                                    <v-radio color="blue" :key="index" :value="promotion">
                                                                                        <template v-slot:label>
                                                                                            <div>{{ promotion.name }}<strong class="tw-text-blue-700">. Descuento por porcentaje por compra total</strong></div>
                                                                                        </template>
                                                                                    </v-radio>
                                                                                </div>
                                                                            </div>
                                                                            <div v-if="promotion.percent_allow > 0 && promotion.promotion_type.name == 'descuento_por_porcentaje_por_boleto'">
                                                                                <div class="tw-border-4 tw-border-purple-500 tw-rounded-xl tw-bg-white tw-p-2 tw-m-3">
                                                                                    <v-radio color="purple" :key="index" :value="promotion">
                                                                                        <template v-slot:label>
                                                                                            <div>{{ promotion.name }}<strong class="tw-text-purple-700">. Descuento por porcentaje por boleto</strong></div>
                                                                                        </template>
                                                                                    </v-radio>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </v-radio-group>
                                                                </div>

                                                            </div>

                                                            <p v-if="!valid" class="tw-py-4 tw-px-4 tw-rounded-lg tw-bg-red-100 tw-border-l-[6px] tw-border-l-red-500 tw-text-red-600 tw-text-xs tw-my-4">{{ error }}</p>

                                                            <div class="tw-mt-5 tw-text-gray-700 "> <!-- :disabled="!form" -->
                                                                <div v-if="!viewVendorTopics(user_roles)" class="tw-flex tw-items-center tw-justify-between ">
                                                                    <v-radio-group inline label="Tipo de compra a realizar" v-model="purchaseType">
                                                                        <v-radio
                                                                            v-for="(option, index) in purchase_types"
                                                                            :disabled="(!!(event.enabled_for_season_tickets && option == 'partido') || option == 'serie')"
                                                                            :key="index"
                                                                            :color="'purple'"
                                                                            :label="option"
                                                                            :value="option"
                                                                        ></v-radio>
                                                                    </v-radio-group>
                                                                    <v-btn v-if="purchaseType == 'abonado'" @click="seasonTicketsDialogOpen" class="!tw-mt-2 !tw-px-8 !tw-h-[50px] !tw-rounded-xl" color="purple" variant="tonal">Tomar datos</v-btn>
                                                                </div>
                                                                <div v-if="viewVendorTopics(user_roles)" class="tw-flex tw-items-center tw-justify-between">
                                                                    <v-radio-group inline label="Tipo de compra a realizar" v-model="purchaseType">
                                                                        <v-radio
                                                                            :color="'purple'"
                                                                            :label="purchaseType"
                                                                            :value="purchaseType"
                                                                        ></v-radio>
                                                                    </v-radio-group>
                                                                    <v-btn v-if="purchaseType == 'abonado'" @click="seasonTicketsDialogOpen" class="!tw-mt-2 !tw-px-8 !tw-h-[50px] !tw-rounded-xl" color="purple" variant="tonal">Tomar datos</v-btn>
                                                                </div>
                                                                <div v-if="viewVendorTopics(user_roles)">
                                                                    <div v-if="seatsSelected.filter(seat => seat.is_owner == 'Si').length > 0">
                                                                        <v-switch inset v-if="!paymentInstallmentSelected" label="¿Se requiere venta a plazos?" color="purple" value="1" v-model="installmentSale"></v-switch>
                                                                    </div>

                                                                    <div v-if="installmentSale">
                                                                        <h4 class="tw-text-xs tw-px-4 tw-py-1 tw-rounded-full  tw-font-bold tw-text-purple-600 tw-text-center tw-mb-2">
                                                                            Complemento para venta a plazos
                                                                        </h4>

                                                                        <!-- <v-autocomplete
                                                                            v-model="saleDeptorSelected"
                                                                            clearable
                                                                            color="purple"
                                                                            chips
                                                                            label="Buscar usuario para asignar la compra"
                                                                            hint="El usuario que se seleccione sera el responsable de la compra"
                                                                            persistent-hint=""
                                                                            :items="sale_debtors_list"
                                                                            variant="solo-filled"
                                                                            item-title="name"
                                                                            item-value="value"
                                                                            :rules="[rules.required]"
                                                                        ></v-autocomplete> -->
                                                                        <div v-if="saleDeptorSelected && saleDeptorSelected === 1">

                                                                            <v-text-field
                                                                                class="tw-w-full"
                                                                                append-inner-icon="mdi-account"
                                                                                label="Nombre"
                                                                                color="purple"
                                                                                v-model="firstNameSaleDeptor"
                                                                                hint="Nombre de para el abonado"
                                                                                :rules="[rules.required]"
                                                                                variant="outlined"
                                                                            ></v-text-field>
                                                                            <v-text-field
                                                                                class="tw-w-full"
                                                                                append-inner-icon="mdi-account"
                                                                                label="Apellido paterno"
                                                                                color="purple"
                                                                                v-model="lastNameSaleDeptor"
                                                                                hint="Apellido paterno de para el abonado"
                                                                                :rules="[rules.required]"
                                                                                variant="outlined"
                                                                            ></v-text-field>
                                                                            <v-text-field
                                                                                class="tw-w-full"
                                                                                append-inner-icon="mdi-phone"
                                                                                label="Numero de teléfono"
                                                                                color="purple"
                                                                                v-model="phoneSaleDeptor"
                                                                                hint="Numero de teléfono para el pago a plazos"
                                                                                :rules="[rules.required, rules.isNumber, rules.phoneNumber]"
                                                                                variant="outlined"
                                                                            ></v-text-field>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div v-if="purchaseStatus == 'retry'">
                                                                    <p class="tw-py-4 tw-px-4 tw-rounded-lg tw-bg-red-100 tw-border-l-[6px] tw-border-l-red-500 tw-text-red-600 tw-text-xs tw-my-4">Estas en el proceso final de compra, si se require agregar otro asiento cancele la seleccion actual y reintente.</p>
                                                                </div>
                                                                <div v-if="purchaseType == 'partido'">
                                                                    <p class="tw-py-4 tw-px-4 tw-rounded-lg tw-bg-green-100 tw-border-l-[6px] tw-border-l-green-500 tw-text-green-600 tw-text-xs tw-my-4">Los boletos adquiridos seran validos solo para un partido.</p>
                                                                </div>
                                                                <div v-else-if="purchaseType == 'serie'">
                                                                    <p class="tw-py-4 tw-px-4 tw-rounded-lg tw-bg-purple-100 tw-border-l-[6px] tw-border-l-purple-500 tw-text-purple-600 tw-text-xs tw-my-4">Los boletos adquiridos seran validos solo para dos partidos del mismo evento.</p>
                                                                </div>
                                                                <div v-else-if="purchaseType == 'abonado'">
                                                                    <p class="tw-py-4 tw-px-4 tw-rounded-lg tw-bg-yellow-100 tw-border-l-[6px] tw-border-l-yellow-500 tw-text-yellow-600 tw-text-xs tw-my-4">Los boletos adquiridos seran validos solo para la temporada a la que pertenece este evento.</p>
                                                                </div>

                                                                <p class="tw-opacity-50 tw-text-right tw-mb-3">Subtotal (tipos de precios selecionados): {{ formatPrice(totalAmount) }}</p>
                                                                <p class="tw-font-bold tw-text-3xl lg:tw-text-4xl tw-text-right tw-mb-3 tw-font-bebas">Total: {{ formatPrice(totalAmount) }}</p>
                                                                <v-btn
                                                                    v-if="showButtonPayment"
                                                                    @click="showPaymentDrawer"
                                                                    size="large" block
                                                                    class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-rounded-2xl !tw-h-[60px] !tw-from-purple-600 !tw-to-pink-400"
                                                                >
                                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos
                                                                </v-btn>
                                                                <v-btn
                                                                    v-else-if="!installmentSale && viewVendorTopics(user_roles)"
                                                                    :disabled="!form"
                                                                    :loading="loadingg"
                                                                    type="submit"
                                                                    size="large" block
                                                                    class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-rounded-2xl !tw-h-[60px] !tw-from-purple-600 !tw-to-pink-400"
                                                                >
                                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos
                                                                </v-btn>
                                                                <v-btn
                                                                    v-else-if="installmentSale && viewVendorTopics(user_roles)"
                                                                    :loading="loadingg"
                                                                    type="submit"
                                                                    size="large" block
                                                                    class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-rounded-2xl !tw-h-[60px] !tw-from-purple-600 !tw-to-pink-400"
                                                                >
                                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos
                                                                </v-btn>
                                                                <v-btn
                                                                    v-else
                                                                    :loading="loadingg"
                                                                    :disabled="!form"
                                                                    type="submit"
                                                                    size="large" block
                                                                    class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-rounded-2xl !tw-h-[60px] !tw-from-purple-600 !tw-to-pink-400"
                                                                >
                                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos
                                                                </v-btn>
                                                                <v-btn
                                                                    @click="selectZones"
                                                                    size="large" block
                                                                    class="text-none !tw-text-white !tw-bg-gradient-to-b !tw-rounded-2xl !tw-h-[60px] !tw-from-red-600 !tw-to-red-400 tw-mt-5 tw-mb-20"
                                                                >
                                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">delete</span>Cancelar seleccion
                                                                </v-btn>

                                                                <v-dialog fullscreen v-model="seasonTicketsDialog" transition="dialog-bottom-transition">
                                                                    <template v-slot:activator="{ props: activatorProps }">
                                                                        <v-btn v-bind="activatorProps" variant="elevated" class="!tw-hidden text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                                    </template>
                                                                    <template v-slot:default="{ isActive }">
                                                                        <v-card>
                                                                            <v-toolbar class="!tw-bg-gradient-to-r !tw-from-slate-950 !tw-via-purple-950 !tw-to-slate-950">
                                                                                <v-btn
                                                                                class="!tw-text-white"
                                                                                icon="mdi-close"
                                                                                @click="seasonTicketsDialog = false"
                                                                                ></v-btn>
                                                                                <v-spacer></v-spacer>

                                                                                <v-toolbar-items>
                                                                                <v-btn
                                                                                    color="white"
                                                                                    text="Aceptar"
                                                                                    variant="tonal"
                                                                                    @click="seasonTicketsDialog = false"
                                                                                ></v-btn>
                                                                                </v-toolbar-items>
                                                                            </v-toolbar>
                                                                            <v-form v-model="seasonTicketsForm" @submit.prevent="seasonTicktesDataConfirm" lazy-validation>
                                                                                <v-card-text>
                                                                                    <div class="tw-w-full tw-max-w-[90%] tw-mx-auto">
                                                                                        <p class="tw-font-bold tw-font-bebas tw-text-sm lg:tw-text-4xl tw-text-gray-700 tw-text-center">Registra y confirma los abonos</p>

                                                                                        <div v-if="seatsSelected.length > 0 && purchaseType == 'abonado'">
                                                                                                <div class="" v-for="(seat, index) in seatsSelected" :key="seat.seat_catalogue.code">
                                                                                                    <div>
                                                                                                        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-mt-10">
                                                                                                            <thead class="tw-bg-gray-100 tw-text-center">
                                                                                                                <tr>
                                                                                                                    <th scope="col" class=" tw-p-2 tw-text-center tw-whitespace-nowrap">
                                                                                                                        <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                                                                            zona
                                                                                                                        </span>
                                                                                                                    </th>

                                                                                                                    <th scope="col" class=" tw-p-2 tw-text-center tw-whitespace-nowrap">
                                                                                                                        <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                                                                            Fila
                                                                                                                        </span>
                                                                                                                    </th>

                                                                                                                    <th scope="col" class=" tw-p-2 tw-text-center tw-whitespace-nowrap">
                                                                                                                        <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                                                                            asiento
                                                                                                                        </span>
                                                                                                                    </th>

                                                                                                                    <th scope="col" class=" tw-p-2 tw-text-center tw-whitespace-nowrap">
                                                                                                                        <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                                                                        precio
                                                                                                                        </span>
                                                                                                                    </th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody class="tw-divide-y tw-divide-gray-200">
                                                                                                                <tr>
                                                                                                                    <td class="tw-size-px tw-whitespace-nowrap tw-p-2 tw-text-center">
                                                                                                                        <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.zone }}</span>
                                                                                                                    </td>
                                                                                                                    <td class="tw-size-px tw-whitespace-nowrap tw-p-2 tw-text-center">
                                                                                                                        <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.row }}</span>
                                                                                                                    </td>
                                                                                                                    <td class="tw-size-px tw-whitespace-nowrap tw-p-2 tw-text-center">
                                                                                                                        <span class="tw-text-sm tw-text-gray-800">{{ seat.seat_catalogue.seat }}</span>
                                                                                                                    </td>
                                                                                                                    <td class="tw-size-px tw-whitespace-nowrap tw-p-2 tw-text-center">
                                                                                                                        <span class="tw-text-sm tw-text-green-600">
                                                                                                                            <div v-for="priceType in seat.price_types" :key="priceType.id">
                                                                                                                                <div v-if="priceType.name === 'abonado'">
                                                                                                                                        {{ formatPrice(priceType.pivot.price) }}
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </span>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>

                                                                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between lg:tw-gap-10">
                                                                                                            <v-text-field
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-account"
                                                                                                                label="Nombre"
                                                                                                                color="purple"
                                                                                                                clearable
                                                                                                                hint="Nombre de para el abonado"
                                                                                                                :rules="[rules.required]"
                                                                                                                v-model="seatsSelected[index].holder_name"
                                                                                                                variant="outlined"
                                                                                                            ></v-text-field>
                                                                                                            <v-text-field
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-account"
                                                                                                                label="Apellido paterno"
                                                                                                                color="purple"
                                                                                                                clearable
                                                                                                                hint="Apellido paterno de para el abonado"
                                                                                                                :rules="[rules.required]"
                                                                                                                v-model="seatsSelected[index].holder_last_name"
                                                                                                                variant="outlined"
                                                                                                            ></v-text-field>
                                                                                                        </div>
                                                                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between lg:tw-gap-10">
                                                                                                            <v-text-field
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-account"
                                                                                                                label="Apellido materno"
                                                                                                                color="purple"
                                                                                                                clearable
                                                                                                                hint="Apellido materno de para el abonado"
                                                                                                                :rules="[rules.required]"
                                                                                                                variant="outlined"
                                                                                                                v-model="seatsSelected[index].holder_middle_name"
                                                                                                            ></v-text-field>
                                                                                                            <v-select
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-file-document-check-outline"
                                                                                                                color="purple"
                                                                                                                label="¿Es titular?"
                                                                                                                hint="Titular de la compra"
                                                                                                                clearable
                                                                                                                :items="['No', 'Si']"
                                                                                                                :rules="[rules.required]"
                                                                                                                v-model="seatsSelected[index].is_owner"
                                                                                                                variant="outlined"
                                                                                                            ></v-select>
                                                                                                        </div>
                                                                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between lg:tw-gap-10">
                                                                                                            <v-select
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-file-document-check-outline"
                                                                                                                color="purple"
                                                                                                                label="Tipo de jersey"
                                                                                                                hint="Tipo de jersey del abonado"
                                                                                                                clearable
                                                                                                                :items="['Femenino', 'Masculino', 'Unisex']"
                                                                                                                :rules="[rules.required]"
                                                                                                                v-model="seatsSelected[index].holder_jersey_type"
                                                                                                                variant="outlined"
                                                                                                            ></v-select>
                                                                                                            <v-select
                                                                                                                class="tw-w-full"
                                                                                                                append-inner-icon="mdi-file-document-check-outline"
                                                                                                                color="purple"
                                                                                                                label="Talla de jersey"
                                                                                                                hint="Talla de jersey del abonado"
                                                                                                                clearable
                                                                                                                :items="['S', 'M', 'L', 'XL', 'XXL']"
                                                                                                                :rules="[rules.required]"
                                                                                                                v-model="seatsSelected[index].holder_jersey_size"
                                                                                                                variant="outlined"
                                                                                                            ></v-select>
                                                                                                        </div>
                                                                                                        <v-textarea
                                                                                                            class="tw-w-full"
                                                                                                            append-inner-icon="mdi-file-document"
                                                                                                            label="Descripcion adicional"
                                                                                                            row-height="30"
                                                                                                            color="purple"
                                                                                                            clearable
                                                                                                            rows="3"
                                                                                                            auto-grow
                                                                                                            v-model="seatsSelected[index].description"
                                                                                                            variant="outlined"
                                                                                                        ></v-textarea>
                                                                                                        <div v-if="seatsSelected[index].is_owner == 'Si'">
                                                                                                            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between lg:tw-gap-10">
                                                                                                                <v-text-field
                                                                                                                    class="tw-w-full"
                                                                                                                    append-inner-icon="mdi-qrcode"
                                                                                                                    label="Codigo postal"
                                                                                                                    color="purple"
                                                                                                                    clearable
                                                                                                                    hint="Ingresa el codigo postal del titular"
                                                                                                                    :rules="[rules.required, rules.isNumber]"
                                                                                                                    v-model="seatsSelected[index].holder_zip_code"
                                                                                                                    variant="outlined"
                                                                                                                    ></v-text-field>
                                                                                                                <v-text-field
                                                                                                                    class="tw-w-full"
                                                                                                                    append-inner-icon="mdi-phone"
                                                                                                                    label="Numero de teléfono"
                                                                                                                    color="purple"
                                                                                                                    clearable
                                                                                                                    hint="Ingresa el numero de teléfono del titular"
                                                                                                                    :rules="[rules.required, rules.isNumber]"
                                                                                                                    v-model="seatsSelected[index].holder_phone"
                                                                                                                    variant="outlined"
                                                                                                                ></v-text-field>
                                                                                                            </div>
                                                                                                            <div class="tw-flex tw-items-start tw-justify-between tw-gap-5">
                                                                                                                <v-select
                                                                                                                    v-if="viewVendorTopics(user_roles)"
                                                                                                                    append-inner-icon="mdi-cash"
                                                                                                                    color="purple"
                                                                                                                    label="¿Pago a meses?"
                                                                                                                    hint="Meses a intereses"
                                                                                                                    clearable
                                                                                                                    variant="outlined"
                                                                                                                    :items="payment_installments"
                                                                                                                    v-model="paymentInstallmentSelected"
                                                                                                                ></v-select>
                                                                                                                <v-text-field
                                                                                                                    append-inner-icon="mdi-email"
                                                                                                                    label="Email"
                                                                                                                    color="purple"
                                                                                                                    autocomplete="email"
                                                                                                                    clearable
                                                                                                                    hint="Ingresa el email del titular"
                                                                                                                    :rules="[rules.required, rules.isEmail]"
                                                                                                                    v-model="seatsSelected[index].holder_email"
                                                                                                                    variant="outlined"
                                                                                                                ></v-text-field>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </v-card-text>

                                                                                <v-card-actions class="tw-w-full tw-max-w-[90%] tw-mx-auto tw-mb-16">
                                                                                    <v-spacer></v-spacer>
                                                                                    <v-btn color="red" size="large" variant="tonal" class="text-none !tw-px-8 !tw-h-[70px] !tw-rounded-2xl !tw-mr-2" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                                    <div v-if="seatsSelected.filter(seat => seat.is_owner == 'Si').length != 1">
                                                                                        <v-btn disabled type="submit" size="large" variant="elevated" class="text-none !tw-bg-green-500 !tw-text-white !tw-px-8 !tw-h-[70px] !tw-rounded-2xl" text="Confirmar datos"></v-btn>
                                                                                    </div>
                                                                                    <div v-else>
                                                                                        <v-btn :disabled="!seasonTicketsForm" type="submit" size="large" variant="elevated" class="text-none !tw-bg-green-500 !tw-text-white !tw-px-8 !tw-h-[70px] !tw-rounded-2xl" text="Confirmar datos"></v-btn>
                                                                                    </div>
                                                                                </v-card-actions>
                                                                        </v-form>
                                                                        </v-card>
                                                                    </template>
                                                                </v-dialog>
                                                                <v-dialog max-width="700">
                                                                    <template v-slot:activator="{ props: activatorProps }">
                                                                        <v-btn id="on-submit-confirm" v-bind="activatorProps" variant="elevated" class="!tw-hidden text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                                    </template>
                                                                    <template v-slot:default="{ isActive }">
                                                                    <!-- <v-container v-if="viewVendorTopics(user_roles)">
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
                                                                        </v-container> -->
                                                                        <v-card class="!tw-relative">
                                                                            <div class="tw-p-7 tw-relative tw-overflow-y-auto tw-text-gray-700 ">
                                                                                <h2 class="tw-font-bebas tw-font-bold tw-text-3xl">Resumen de compra</h2>
                                                                                <h2 class="tw-font-bebas tw-font-bold tw-text-2xl tw-mt-5">Total: {{ formatPrice(totalAmount) }}</h2>
                                                                                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-3">
                                                                                    <v-switch inset color="purple" v-model="acceptTerms"></v-switch>
                                                                                    <a href="/politicas-de-privacidad" target="_blank" class="-tw-mt-10 lg:tw-mt-3">Acepto <span class="tw-underline tw-text-primary">terminos y condiciones</span></a>
                                                                                </div>
                                                                                <!-- Tabla de asientos seleccionados -->
                                                                                <v-data-table :items="seatsSelected" class="" hide-default-footer items-per-page="-1">
                                                                                    <template v-slot:headers>
                                                                                    <tr>
                                                                                        <th>Asiento</th>
                                                                                        <th>Tipo</th>
                                                                                        <th>Precio</th>
                                                                                        <th v-if="seatsSelected.some(item => item.promotion_id)">Promoción</th>
                                                                                        <th v-if="purchaseType == 'abonado'">Abonado</th>
                                                                                        <th v-if="purchaseType == 'abonado'">Titular</th>
                                                                                        <th v-if="purchaseType == 'abonado'">Género</th>
                                                                                        <th v-if="purchaseType == 'abonado'">Talla</th>
                                                                                    </tr>
                                                                                    </template>
                                                                                    <template v-slot:item="{ item }">
                                                                                    <tr>
                                                                                        <td>{{ item.seat_catalogue.code }}</td>
                                                                                        <td>{{ item.seat_catalogue.seat_type.name }}</td>
                                                                                        <td>{{ formatPrice(item.final_price) }}</td>
                                                                                        <td v-if="item.promotion_id">{{ item.promotion_id ? "Aplicada": '' }}</td>
                                                                                        <td v-if="purchaseType == 'abonado'">{{ `${item.holder_name} ${item.holder_last_name} ${item.holder_middle_name}` }}</td>
                                                                                        <td v-if="purchaseType == 'abonado'">{{ item.is_owner }}</td>
                                                                                        <td v-if="purchaseType == 'abonado'">{{ item.holder_jersey_type }}</td>
                                                                                        <td v-if="purchaseType == 'abonado'">{{ item.holder_jersey_size }}</td>
                                                                                    </tr>
                                                                                    </template>
                                                                                </v-data-table>
                                                                                <!-- Información del titular antes de la tabla -->
                                                                                <div v-if="seatsSelected.some(seat => seat.is_owner === 'Si')" class="tw-mt-4">
                                                                                    <h2 class="tw-font-bebas tw-font-bold tw-text-2xl tw-text-right lg:tw-text-left">Titular</h2>
                                                                                    <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-center lg:tw-justify-between tw-gap-4 tw-mt-2 tw-text-right">
                                                                                        <div>Código Postal: <span>{{ getHolderInfo('holder_zip_code') }}</span></div>
                                                                                        <div>Teléfono: <span>{{ getHolderInfo('holder_phone') }}</span></div>
                                                                                        <div>Email: <span>{{ getHolderInfo('holder_email') }}</span></div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="tw-flex tw-flex-col tw-items-end tw-gap-4 tw-mt-5 tw-opacity-60 tw-text-right tw-pb-20">
                                                                                    <div>Metodos de Pago: <span>{{ paymentTypesSelected.map(payment => formatFirstLetterUppercase(payment.name)).join(', ') }}</span></div>
                                                                                    <div>Tipo de Compra: <span>{{ installmentSale ? "Pago a plazos" : "Pago al contado" }}</span></div>
                                                                                    <div v-if="seatsSelected.some(item => item.promotion_id)">Promoción: <span>{{ selectedPromotion ? `${selectedPromotion.name} (${formatFirstLetterUppercase(selectedPromotion.type)})` :'' }}</span></div>
                                                                                    <div v-if="installmentSale">Pago Inicial: <span>{{ formatPrice((parseFloat(amountToPayCard) || 0) + (parseFloat(amountToPayCash) || 0)) }}</span></div>
                                                                                    <div v-if="installmentSale">Total restante : <span>{{ formatPrice( parseFloat(totalAmount) - ((parseFloat(amountToPayCard) || 0) + (parseFloat(amountToPayCash) || 0))  ) }}</span></div>
                                                                                </div>
                                                                            </div>
                                                                              <div class="tw-flex tw-items-center tw-justify-end md:tw-gap-3 tw-absolute tw-bottom-5 tw-pt-5 tw-right-5 tw-bg-white">
                                                                                    <v-btn color="red" variant="tonal" class="text-none !tw-px-4 lg:!tw-px-8 tw-mr-2 !tw-h-[60px] lg:!tw-h-[70px] !tw-rounded-2xl" text="Cancelar" @click="isActive.value = false"></v-btn>
                                                                                    <v-btn :loading="loading" variant="elevated" class="text-none !tw-px-4 lg:!tw-px-8 !tw-h-[60px] lg:!tw-h-[70px] !tw-rounded-2xl !tw-bg-green-500 !tw-text-white" text="Reservar y comprar" @click="onSubmitConfirm(isActive)"></v-btn>
                                                                                </div>
                                                                        </v-card>
                                                                    </template>
                                                                </v-dialog>

                                                            </div>
                                                        </v-expansion-panel-text>


                                                        </v-form>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>

                                                <div class="tw-my-5">
                                                    <div v-if="viewVendorTopics(user_roles) && tab == 'payment'" class="text-center">
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
                                                        </v-snackbar>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card-text>
        </main>
    </div>

    <Footer />

</template>

<style scoped>
.v-parallax {
    z-index: -10;
}

.tw-animate-spin {
    animation: tw-spin 2s linear infinite;
}
.tw-animate-ping {
    animation:  tw-ping 3s linear infinite;
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
  animation: tw-bounce 1.5s infinite;
}
@media (min-width: 1024px) {
    .tw-animate-bounce {
    animation: tw-bounce 1.5s infinite;
    }
}

.v-dialog--fullscreen > .v-overlay__content > .v-card, .v-dialog--fullscreen > .v-overlay__content > .v-sheet, .v-dialog--fullscreen > .v-overlay__content > form > .v-card, .v-dialog--fullscreen > .v-overlay__content > form > .v-sheet {
    min-height: 100%;
    min-width: 100%;
    border-radius: 0px !important;
}
</style>
