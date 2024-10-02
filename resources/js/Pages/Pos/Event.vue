<script setup>
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import { SvgPanZoom } from 'vue-svg-pan-zoom';
import StadiumSVG from '@/Components/StadiumSVG.vue';

const svgPanZoom = ref(null);

const registerSvgPanZoom = (instance) => {
  svgPanZoom.value = instance;
};

const zoomIn = () => {
  if (svgPanZoom.value) {
    svgPanZoom.value.zoomIn();
  }
};

const zoomOut = () => {
  if (svgPanZoom.value) {
    svgPanZoom.value.zoomOut();
  }
};

const handleClick = (event) => {
  console.log('SVG clicked at', event);
};

const handleSectionClick = (event) => {
  console.log('Section clicked', event);
};

onMounted(() => {
  if(document.querySelector('#leaflet-map')) {
    var map = L.map('leaflet-map').setView([19.513615, -96.916121], 17);
    if(map) {
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([19.513615, -96.916121]).addTo(map)
            .bindPopup('El nido del halcon | Xalapa Ver.')
            .openPopup();
    }
}
});

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});


</script>

<template>
    <Head title="Welcome" />
    <GuestLayout />
    <NavigationDrawer />

    <section class="tw-overflow-hidden tw-mt-[100px] lg:tw-mt-[111px]">
        <v-parallax
            class="tw-h-60 md:tw-h-[350px]" :src="`/storage/${event.global_image.file_path}`"
        >
            <div class="d-flex flex-column fill-height justify-start align-start text-white tw-bg-purple-900/50">

            </div>
        </v-parallax>
    </section>

    <section class="tw-w-full tw-min-h-screen tw-bg-white tw-mt-[-37px] tw-rounded-[35px] lg:tw-rounded-[60px] tw-relative">
        <div class="tw-absolute -tw-top-16 tw-left-20 lg:tw-block tw-hidden ">
            <div class="tw-flex tw-items-center tw-gap-5">
                <v-btn color="white" variant="elevated" class="text-none" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl">deployed_code</span>Halcones de xalapa vs puebla</v-btn>
            </div>
        </div>
        <div class="tw-max-w-7xl tw-mx-auto tw-py-7 lg:tw-py-12 tw-px-4 lg:tw-px-0">
            <div class="lg:tw-hidden tw-flex tw-items-center tw-gap-5 tw-w-full">
                <v-btn color="purple" variant="tonal" class="text-none" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl">deployed_code</span>Halcones de xalapa vs puebla</v-btn>
            </div>

            <main class="">

                <div class="tw-mt-5 lg:tw-mt-0 tw-inline-flex">
                    <h3 class="lg:tw-text-2xl tw-text-xs lg:tw-font-bold tw-text-gray-500 tw-px-5 tw-py-3 tw-bg-gray-50 md:tw-bg-gray-200 tw-rounded-xl">Selecione una zona y sus asientos</h3>
                </div>

                <div class="tw-mt-10 tw-w-full tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-between tw-gap-7 lg:tw-gap-16">

                    <div class="tw-w-full lg:tw-w-[30%]">
                        <h3 class="tw-text-2xl tw-font-bold">Locacion del estadio</h3>
                        <h4 class="tw-text-sm mt-1">📍 El nido del halcon | Xalapa Ver.</h4>
                        <div class="tw-h-[300px] tw-w-full mt-3 tw-shadow-xl tw-rounded-2xl tw-overflow-hidden">
                            <div id="leaflet-map" class="tw-h-[300px] tw-w-full tw-z-10 tw-rounded-2xl"></div>
                        </div>
                    </div>

                    <div class="tw-w-full lg:tw-w-[70%]">
                       <div class="tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-gap-3 tw-justify-between mb-4">
                            <div>
                                <p class="tw-font-bold tw-text-2xl">Zonas<span class="tw-bg-clip-text tw-bg-gradient-to-r tw-from-orange-400 tw-to-tw-primary-600 tw-text-transparent"> disponibles</span> en el estadio.</p>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <v-btn @click="zoomIn" color="purple" variant="tonal" class="text-none" rounded="lg" size="large"><span class="material-symbols-outlined tw-text-2xl">add</span></v-btn>
                                <v-btn @click="zoomOut" color="purple" variant="tonal" class="text-none" rounded="lg" size="large"><span class="material-symbols-outlined tw-text-2xl">remove</span></v-btn>
                            </div>
                       </div>
                        <SvgPanZoom
                            ref="svgPanZoom"
                            class="tw-w-full tw-h-[475px] tw-border-2 tw-border-gray-200 tw-rounded-2xl tw-shadow-lg tw-cursor-grab"
                            :zoomEnabled="true"
                            :controlIconsEnabled="false"
                            :fit="true"
                            :center="true"
                            @created="registerSvgPanZoom"
                        >
                            <StadiumSVG @click="handleClick" @section-click="handleSectionClick" />
                        </SvgPanZoom>
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
</style>
