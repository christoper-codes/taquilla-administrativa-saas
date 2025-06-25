<script setup>
import { nextTick, onMounted, ref, onBeforeMount, watch, computed } from 'vue';
import EstadioHdx from '@/Components/SectionsHdx/EstadioHdx.vue';
import ZonaA from '@/Components/SectionsHdx/ZonaA.vue';
import ZonaC from '@/Components/SectionsHdx/ZonaC.vue';
import ZonaB from '@/Components/SectionsHdx/ZonaB.vue';
import ZonaE from '@/Components/SectionsHdx/ZonaE.vue';
import ZonaF from '@/Components/SectionsHdx/ZonaF.vue';
import ZonaH from '@/Components/SectionsHdx/ZonaH.vue';
import panzoom from 'panzoom';
import ErrorSession from '@/Components/ErrorSession.vue';
import Loader from '@/Components/Loader.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import useDateFormat from '@/composables/dateFormat';
import useStringFormat from '@/composables/stringFormat';

const { dateFormat } = useDateFormat();
const { formatFirstLetterUppercase } = useStringFormat();

const props = defineProps({
    event: {
        type: Object,
        required: true,
    }
});

const dataEvent = ref({
    event: null,
    c_zone: null,
    c_rows: null,
    a_zone: null,
    a_rows: null,
    b_zone: null,
    b_rows: null,
    e_zone: null,
    e_rows: null,
    f_zone: null,
    f_rows: null,
    h_zone: null,
    h_rows: null,
});

const promotions = ref([]);
const promotionForSelectedSeats = ref([]);

let panZoomInstance;

function loadSvg(id) {

    setTimeout(() => {
        const zoneId = document.querySelector(`#${id}`);
        if (zoneId) {
            panZoomInstance = panzoom(zoneId);
            if (id != 'zones_hdx') {
                const { x, y } = getCenterCoordinates(id);
                if (window.innerWidth > 1024) {
                    panZoomInstance.smoothZoom(x, y, 2.3);
                } else {
                    panZoomInstance.smoothZoom(x, y, 5);
                }
            }
            if (window.innerWidth > 1024 && id == 'zones_hdx') {
                panZoomInstance.smoothZoom(400, 360, 0.6);
            }
        } else {
            alert('Zona no encontrada');
        }
    }, 300);
}

const getCenterCoordinates = (id) => {
    const svgElement = document.querySelector(`#${id}`);
    const { width, height } = svgElement.getBoundingClientRect();
    if (window.innerWidth > 1024) {
        return { x: width / -7, y: height / 2.5 };
    } else {
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

const seatsSelected = ref([]);

const addSeat = (seat) => {

    const seatExist = seatsSelected.value.find((s) => s.seat_catalogue.code === seat.seat_catalogue.code);

    if (!seatExist) {

        seat.select_promotion = seat.promotions.map((promotion) => promotion.id);

        if (promotionForSelectedSeats.value.length) {

            let promotion = promotionForSelectedSeats.value.filter((promotion) => !seat.select_promotion.includes(promotion));

            seat.select_promotion = seat.select_promotion.concat(promotion);
        }

        seatsSelected.value.push(seat);

    } else {
        seatsSelected.value = seatsSelected.value.filter((s) => s.seat_catalogue.code !== seat.seat_catalogue.code);

        const uniqueRows = [...new Set(seatsSelected.value.map((s) => s.seat_catalogue.row))];

        clearRowsSelected(uniqueRows);
    }
}

const updateSeats = (zones) => {

    const updateZone = (currentZone, receivedZone) => {

        const seatsModified = [];

        const newData = currentZone.map((seat) => {

            let seatTemp = receivedZone.find((receivedSeat) => receivedSeat.seat_catalogue.code === seat.seat_catalogue.code);

            if (seatTemp) {
                seat.promotions = seatTemp.promotions;
                seat.select_promotion = seat.promotions.map((promotion) => promotion.id);

                seatsModified.push(seat);
            }

            return seat;
        });


        return {
            newData: newData,
            seatsModified: seatsModified
        }
    };

    ["c_zone","a_zone", "b_zone", "e_zone", "f_zone", "h_zone"].forEach((zone) => {
        if (zones[zone].length) {
            const data = updateZone(dataEvent.value[zone], zones[zone]);

            dataEvent.value[zone] = data.newData;

            data.seatsModified.forEach((seat) => addSeat(seat));

            noReset.value = false;
            selectedRows.value = [];
            promotionForSelectedSeats.value = [];

            setTimeout(() => {
                noReset.value = true;
            }, 1000);
        };
    });
};

const panel = ref([0, 1]);

const isSvgVisible = ref(true);
const selectedSection = ref('');
const viewSelectedSection = ref('Zonas HDX');

const handleSectionClick = (section) => {

    selectedSection.value = section;
    isSvgVisible.value = false;

    const stadiumHdxImgID = '#stadium-hdx-img';

    const zones = {
        zonaC: {
            loadSvg: 'zonaC',
            viewSelectedSection: 'Zona C',
            rows: dataEvent.value.c_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
        zonaA: {
            loadSvg: 'zonaA',
            viewSelectedSection: 'Zona A',
            rows: dataEvent.value.a_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
        zonaB: {
            loadSvg: 'zonaB',
            viewSelectedSection: 'Zona B',
            rows: dataEvent.value.b_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
        zonaE: {
            loadSvg: 'zonaE',
            viewSelectedSection: 'Zona E',
            rows: dataEvent.value.e_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
        zonaF: {
            loadSvg: 'zonaF',
            viewSelectedSection: 'Zona F',
            rows: dataEvent.value.f_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
        zonaH: {
            loadSvg: 'zonaH',
            viewSelectedSection: 'Zona H',
            rows: dataEvent.value.h_rows,
            classListRemove: 'tw-rotate-0',
            classListAdd: 'tw-rotate-90',
        },
    }

    loadSvg(zones[section].loadSvg);
    viewSelectedSection.value = zones[section].viewSelectedSection;
    rows.value = zones[section].rows;
    const stadiumHdxImg = document.querySelector(stadiumHdxImgID);
    stadiumHdxImg.classList.remove(zones[section].classListRemove);
    stadiumHdxImg.classList.add(zones[section].classListAdd);
};

const selectZones = () => {
    loadSvg('zones_hdx');
    isSvgVisible.value = true;
    selectedSection.value = '';
    rows.value = [];
    viewSelectedSection.value = 'Zonas HDX';
    seatsSelected.value = [];
    const stadiumHdxImg = document.querySelector('#stadium-hdx-img');
    stadiumHdxImg.classList.remove('tw-rotate-90');
    stadiumHdxImg.classList.add('tw-rotate-0');
};

const loadSeat = ref(false);
const rows = ref([]);
const selectedRows = ref([]);
const noReset = ref(true);

const loadEvent = () => {

    loadSeat.value = true;

    const getUniqueRows = (seats) => [...new Set(seats.map(seat => seat.seat_catalogue.row))];

    axios.get(route('event.management.showManagement', props.event.id)).then((response) => {

        dataEvent.value.event = response.data.event;
        dataEvent.value.c_zone = response.data.c_zone;
        dataEvent.value.c_rows = getUniqueRows(response.data.c_zone);
        dataEvent.value.a_zone = response.data.a_zone;
        dataEvent.value.a_rows = getUniqueRows(response.data.a_zone);
        dataEvent.value.b_zone = response.data.b_zone;
        dataEvent.value.b_rows = getUniqueRows(response.data.b_zone);
        dataEvent.value.e_zone = response.data.e_zone;
        dataEvent.value.e_rows = getUniqueRows(response.data.e_zone);
        dataEvent.value.f_zone = response.data.f_zone;
        dataEvent.value.f_rows = getUniqueRows(response.data.f_zone);
        dataEvent.value.h_zone = response.data.h_zone;
        dataEvent.value.h_rows = getUniqueRows(response.data.h_zone);
    })
        .catch((error) => {
            console.error('Error:', error);
        })
        .finally(() => {
            loadSeat.value = false;
        });
}

const loadPromotions = () => {

    axios.get(route('promotion.all.by.stadium', props.event.serie.global_season.stadium_id)).then((response) => {

        promotions.value = response.data;
    })
        .catch((error) => {
            console.error('Error:', error);
        })
        .finally(() => {
        });
}

watch(promotionForSelectedSeats, (newPromotions, oldPromotions) => {

    if (noReset.value) {

        if (newPromotions.length > oldPromotions.length) {

            seatsSelected.value = seatsSelected.value.map((seat) => {

                if (seat.select_promotion !== null) {

                    const newPromotionsTemp = newPromotions.filter((value) => !seat.select_promotion.includes(value));

                    seat.select_promotion = seat.select_promotion.concat(newPromotionsTemp);

                } else {

                    seat.select_promotion = newPromotions;

                }

                return seat;
            });

        } else if (oldPromotions.length > newPromotions.length) {

            seatsSelected.value = seatsSelected.value.map((seat) => {

                if (seat.select_promotion !== null) {

                    const promotionsDelete = oldPromotions.filter((value) => !newPromotions.includes(value));

                    seat.select_promotion = seat.select_promotion.filter((value) => !promotionsDelete.includes(value));
                }

                return seat;
            });
        }
    }
})


const seatsAutoClic = ref([]);

const listOfSeatsToSelect = (selectedSection, rows) => {

    let seats = [];

    if (selectedSection === 'zonaC') {

        seats = dataEvent.value.c_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));

    } else if (selectedSection === 'zonaA') {

        seats = dataEvent.value.a_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));

    } else if (selectedSection === 'zonaB') {

        seats = dataEvent.value.b_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));

    } else if (selectedSection === 'zonaE') {

        seats = dataEvent.value.e_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));

    } else if (selectedSection === 'zonaF') {

        seats = dataEvent.value.f_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));

    } else if (selectedSection === 'zonaH') {

        seats = dataEvent.value.h_zone.filter((seat) => rows.includes(seat.seat_catalogue.row));
    }

    seatsAutoClic.value = seats;
}

watch(selectedRows, (newPromotions = [], oldPromotions = []) => {

    if (noReset.value) {
        let rows = [];

        if (newPromotions.length > oldPromotions.length) {

            rows = newPromotions.filter(item => !oldPromotions.includes(item));

        } else if (oldPromotions.length > newPromotions.length) {

            rows = oldPromotions.filter(item => !newPromotions.includes(item));
        }

        listOfSeatsToSelect(selectedSection.value, rows);
    }
});

const clearRowsSelected =  (rowsDelete) => {

    noReset.value = false;

    selectedRows.value = selectedRows.value.filter((value) => rowsDelete.includes(value))

    setTimeout(() => {
        noReset.value = true;
    }, 1000);
}

onMounted(() => {
    nextTick(() => {
        loadSvg('zones_hdx');
    });

    window.addEventListener('resize', updateWindowWidth);
});

onBeforeMount(() => {
    loadEvent();
    loadPromotions();
});

const windowScreenWidth = ref(window.innerWidth);

const updateWindowWidth = () => {
    windowScreenWidth.value = window.innerWidth;
};

const loadingSavePromotion = ref(false);

const savePromotionSeat = () => {

    loadingSavePromotion.value = true;

    axios.post(route('event.seat.catalog.store'), {
        seats: seatsSelected.value
    }).then((response) => {
        updateSeats(response.data);
    })
        .catch((error) => {
            console.error('Error:', error);
        })
        .finally(() => {

            loadingSavePromotion.value = false;
        });
};

</script>

<template>

    <Loader :activate="loadSeat" />

    <SuccessSession />
    <ErrorSession />

    <section class="tw-max-w-full tw-h-full tw-bg-white">
        <div class="tw-flex tw-w-full tw-h-full tw-min-h-screen tw-flex-col sm:tw-flex-row">
            <div class="tw-flex tw-justify-start tw-items-start 2xl:tw-w-[70%] lg:tw-w-[70%] md:tw-w-full">
                <div class="tw-grid tw-gap-10 md:tw-gap-8 lg:tw-gap-5 tw-w-full tw-p-4">
                    <div class="tw-h-[2vh] tw-flex tw-justify-start tw-items-center">
                        <p class="tw-font-bold tw-text-xl">Mapa de asignación de promociones</p>
                    </div>
                    <div class="tw-h-[10vh] tw-flex tw-justify-start tw-items-center">
                        <div
                            class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-6 tw-items-center tw-w-full tw-gap-2 tw-mt-7">
                            <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                <div
                                    class="tw-h-7 lg:tw-h-9 tw-w-full tw-bg-yellow-500 tw-flex tw-items-center tw-justify-center tw-rounded-md">
                                    <span class="material-symbols-outlined tw-text-sm tw-text-white">done_outline</span>
                                </div>
                                <p class="tw-text-xs lg:tw-text-base">Disponible</p>
                            </div>
                            <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                <div
                                    class="tw-h-7 lg:tw-h-9 tw-w-full tw-bg-purple-500 tw-flex tw-items-center tw-justify-center tw-rounded-md">
                                    <span class="material-symbols-outlined tw-text-sm tw-text-white">star</span>
                                </div>
                                <p class="tw-text-xs lg:tw-text-base">Con Promoción</p>
                            </div>
                            <div class="tw-flex tw-items-center tw-flex-col tw-gap-2">
                                <div
                                    class="tw-h-7 lg:tw-h-9 tw-w-full tw-bg-green-500 tw-flex tw-items-center tw-justify-center tw-rounded-md">
                                    <span class="material-symbols-outlined tw-text-sm tw-text-white">web_traffic</span>
                                </div>
                                <p class="tw-text-xs lg:tw-text-base">Seleccionado</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-w-full tw-gap-3 tw-my-3">
                        <div class="tw-flex tw-items-center tw-gap-3 tw-flex-col md:tw-flex-row">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <v-btn @click="zoomIn" color="purple" variant="tonal" class="text-none" rounded="xl"
                                    size="large"><span
                                        class="material-symbols-outlined tw-text-2xl">add</span>zoom</v-btn>
                                <v-btn @click="zoomOut" color="purple" variant="tonal" class="text-none" rounded="xl"
                                    size="large"><span
                                        class="material-symbols-outlined tw-text-2xl">remove</span>zoom</v-btn>
                            </div>
                        </div>
                        <div class="tw-items-center tw-gap-2 tw-hidden lg:tw-flex tw-relative">
                            <div class="tw-font-bold tw-text-3xl tw-text-center">
                                {{ viewSelectedSection }}
                            </div>
                            <v-dialog max-width="800">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <div v-bind="activatorProps" class="!tw-absolute -tw-top-4 -tw-right-6 ">
                                        <span
                                            class="material-symbols-outlined tw-text-2xl tw-text-purple-600 tw-animate-bounce tw-cursor-pointer">photo_library</span>
                                    </div>
                                </template>
                                <template v-slot:default="{ isActive }">
                                    <v-card :title="'Imagen de referencia para la ' + viewSelectedSection">
                                        <v-card-text>
                                            <img class="tw-w-full tw-h-auto tw-rounded-xl"
                                                src="../../../../../../public/img/zonashdx/zona-a-img.jpg"
                                                alt="zona hdx">
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="purple" rounded="xl" variant="tonal"
                                                class="text-none !tw-px-6" text="Cerrar"
                                                @click="isActive.value = false"></v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </template>
                            </v-dialog>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <v-btn @click="resetZoom" color="purple" variant="tonal" class="text-none" rounded="xl"
                                size="large"><span
                                    class="material-symbols-outlined tw-text-2xl">my_location</span>reset</v-btn>

                            <v-btn @click="selectZones" color="purple" variant="tonal" class="text-none" rounded="xl"
                                size="large"><span
                                    class="material-symbols-outlined tw-text-2xl">location_on</span>zonas</v-btn>
                        </div>
                    </div>
                    <div
                        class="tw-h-[60vh] tw-flex tw-justify-center tw-items-start tw-cursor-grab tw-overflow-hidden tw-relative">
                        <div
                            class="tw-size-[100px] lg:tw-size-36 tw-border tw-border-gray-300 tw-absolute tw-top-0 tw-left-0 tw-z-20 tw-bg-white tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                            <img id="stadium-hdx-img"
                                class="tw-size-20 lg:tw-size-32 tw-rotate-0 tw-transition-all tw-duration-1000"
                                src="../../../../../../public/img/stadium-hdx-img.svg" alt="Webiste image">
                        </div>
                        <div v-if="isSvgVisible">
                            <EstadioHdx @handle-section-click="handleSectionClick" />
                        </div>
                        <div v-if="selectedSection == 'zonaC'" class="">
                            <ZonaC @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.c_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                        <div v-if="selectedSection == 'zonaA'" class="">
                            <ZonaA @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.a_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                        <div v-if="selectedSection == 'zonaB'" class="">
                            <ZonaB @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.b_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                        <div v-if="selectedSection == 'zonaE'" class="">
                            <ZonaE @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.e_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                        <div v-if="selectedSection == 'zonaF'" class="">
                            <ZonaF @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.f_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                        <div v-if="selectedSection == 'zonaH'" class="">
                            <ZonaH @add-seat="addSeat" v-bind:action="'promotion'" v-bind:seats="dataEvent.h_zone"
                                v-bind:seatsSelected="seatsSelected" v-bind:seatsAutoClic="seatsAutoClic"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tw-flex tw-justify-start tw-items-start tw-shadow-lg 2xl:tw-w-[30%] lg:tw-w-[30%] md:tw-w-full">
                <div class="tw-grid tw-gap-1 tw-w-full tw-py-10 tw-px-3">
                    <div class="">
                        <div class="tw-py-2">
                            <h2 class="tw-text-2xl tw-font-bold lg:tw-block">{{
                                formatFirstLetterUppercase(event.name) }}
                            </h2>
                        </div>
                        <div
                            class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start lg:tw-items-center tw-gap-2 tw-py-6">
                            <div
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>
                                {{ formatFirstLetterUppercase(event.serie.global_season.stadium.club_name) }}
                            </div>
                            <p
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs tw-text-gray-800 tw-bg-gray-100 hover:tw-bg-gray-200">
                                📅 | {{ dateFormat(event.start_date) }}
                            </p>
                        </div>
                    </div>
                    <div class="tw-h-full tw-flex tw-justify-start tw-items-start">
                        <div class="tw-grid tw-gap-2 tw-w-full tw-p-1">
                            <div
                                class="tw-relative tw-overflow-hidden tw-min-h-28 tw-bg-gray-800 tw-text-center tw-rounded-xl lg:tw-rounded-tr-none">
                                <div class="tw-mt-10 tw-text-white tw-text-xl">
                                    Asientos seleccionados
                                </div>

                                <figure class="tw-absolute tw-inset-x-0 tw-bottom-0 -tw-mb-px">
                                    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                        viewBox="0 0 1920 100.1">
                                        <path fill="currentColor" class="tw-fill-white"
                                            d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z">
                                        </path>
                                    </svg>
                                </figure>
                            </div>
                            <div class="tw-relative tw-z-10 -tw-mt-12">
                                <span
                                    class="tw-mx-auto tw-flex tw-justify-center tw-items-center tw-size-[62px] tw-rounded-full tw-border tw-border-gray-200 tw-bg-white tw-text-gray-700 tw-shadow-sm">
                                    <svg class="tw-shrink-0 tw-size-6" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z">
                                        </path>
                                        <path
                                            d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z">
                                        </path>
                                    </svg>
                                </span>
                            </div>

                            <div v-if="!isSvgVisible" class="tw-w-full">
                                <v-select v-model="selectedRows" item-value="id" :items="rows" density="compact"
                                    :item-title="(item) => formatFirstLetterUppercase(item)"
                                    label="Selecciona las filas" chips multiple></v-select>
                            </div>

                            <div v-if="seatsSelected.length == 0"
                                class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-7">
                                <p
                                    class="tw-w-full tw-text-center tw-text-xs tw-p-3 tw-rounded-full tw-bg-gray-100 tw-mt-5">
                                    No se han seleccionado asientos</p>
                                <img class="tw-w-40 tw-h-auto"
                                    src="../../../../../../public/img/seats-no-selected-img.svg" alt="Webiste image">
                            </div>

                            <div v-if="seatsSelected.length > 0"
                                class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-7 tw-my-4">
                                <div class="text-center">
                                    <v-btn @click="savePromotionSeat" :loading="loadingSavePromotion">
                                        Guardar Promociones
                                    </v-btn>
                                </div>
                            </div>

                            <div v-if="seatsSelected.length > 0" class="">
                                <div class="tw-w-full ">

                                    <v-select v-model="promotionForSelectedSeats" item-value="id" :items="promotions"
                                        density="compact" :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                        label="Promoción aplicada a los asientos seleccionados" chips
                                        multiple></v-select>

                                    <v-expansion-panels v-model="panel" class="" multiple>
                                        <v-expansion-panel>
                                            <v-expansion-panel-title expand-icon="mdi-menu-down">
                                                Asientos seleccionados #{{ seatsSelected.length }}
                                            </v-expansion-panel-title>
                                            <v-expansion-panel-text>
                                                <v-table class="tw-min-w-full tw-divide-y tw-divide-gray-200"
                                                    height="400px" fixed-header>
                                                    <thead class="tw-bg-gray-100">
                                                        <tr>
                                                            <th scope="col"
                                                                class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                <span
                                                                    class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    Z.
                                                                </span>
                                                            </th>
                                                            <th scope="col"
                                                                class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                <span
                                                                    class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    F.
                                                                </span>
                                                            </th>
                                                            <th scope="col"
                                                                class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                <span
                                                                    class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    A.
                                                                </span>
                                                            </th>
                                                            <th scope="col"
                                                                class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                <span
                                                                    class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    Promos.
                                                                </span>
                                                            </th>
                                                            <th scope="col"
                                                                class=" tw-p-2 tw-text-start tw-whitespace-nowrap">
                                                                <span
                                                                    class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800">
                                                                    Acc.
                                                                </span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tw-divide-y tw-divide-gray-200">
                                                        <tr v-for="seat in seatsSelected"
                                                            :key="seat.seat_catalogue.code">
                                                            <td class="tw-size-px tw-whitespace-nowrap tw-p-2">
                                                                <span class="tw-text-sm tw-text-gray-800">{{
                                                                    seat.seat_catalogue.zone }}</span>
                                                            </td>
                                                            <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                <span class="tw-text-sm tw-text-gray-800">{{
                                                                    seat.seat_catalogue.row
                                                                    }}</span>
                                                            </td>
                                                            <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                <span class="tw-text-sm tw-text-gray-800">{{
                                                                    seat.seat_catalogue.seat }}</span>
                                                            </td>

                                                            <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                <v-select v-model="seat.select_promotion"
                                                                    density="compact" item-value="id"
                                                                    :items="promotions"
                                                                    :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                    label="Promoción" chips multiple></v-select>
                                                            </td>
                                                            <td class="tw-size-px tw-whitespace-nowrap  tw-p-2">
                                                                <span @click="addSeat(seat)"
                                                                    class="material-symbols-outlined tw-text-xl tw-text-red-500 tw-cursor-pointer">delete</span>
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </v-table>
                                            </v-expansion-panel-text>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<style scoped></style>
