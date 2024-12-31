<script setup>
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import useDateFormat from '@/composables/dateFormat';

const { dateFormat } = useDateFormat();
const loading = ref(false);

const showEvent = () => {
    loading.value = true;
};
const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
});

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


</script>

<template>
    <Head title="Welcome" />
    <GuestLayout />
    <NavigationDrawer />

    <section class="tw-overflow-hidden">
        <v-parallax
            class="tw-h-96 md:tw-h-auto" src="/storage/public/back-hdx-img.jpg"
        >
            <div class="d-flex flex-column fill-height justify-start align-start text-white tw-bg-purple-500/50">

            </div>
        </v-parallax>
    </section>

    <section class="tw-w-full tw-min-h-screen tw-bg-white tw-mt-[-37px] tw-rounded-[35px] lg:tw-rounded-[60px] tw-relative">
        <div class="tw-max-w-7xl tw-mx-auto tw-py-3 lg:tw-py-12 tw-px-4 lg:tw-px-0">
            <main class="">
                <ErrorSession />
                <section class="tw-max-w-7xl tw-min-h-screen tw-pt-0 tw-mx-auto">
                    <!-- Blog Article -->
                    <div class="tw-mx-auto">
                    <div class="tw-grid lg:tw-grid-cols-3 tw-gap-y-8 lg:tw-gap-y-0 lg:tw-gap-x-6">
                        <!-- Content -->
                        <div class="lg:tw-col-span-2">
                            <div v-if="events" class="tw-py-8 lg:tw-pe-8">
                                <div v-for="event in events" :key="event.id"  class="tw-space-y-5 lg:tw-space-y-8">
                                    <Link :href="route('welcome')">
                                        <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                            <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                            Regresar al inicio
                                        </div>
                                    </Link >

                                    <h2 class="tw-text-2xl tw-font-bold lg:tw-text-5xl">{{ event.name }}</h2>

                                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start lg:tw-items-center tw-gap-2 lg:tw-gap-5">
                                        <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                            📍 | Evento deportivo
                                        </div>
                                        <p class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-text-gray-800 tw-bg-gray-100 hover:tw-bg-gray-200">📅 | {{ dateFormat(event.start_date) }} </p>
                                    </div>

                                    <p class="tw-text-lg tw-text-gray-800 tw-hidden lg:tw-block">Xalapa, Veracruz - En un inicio de temporada emocionante, los Halcones de Xalapa han demostrado ser uno de los equipos más competitivos de la liga profesional de baloncesto en México</p>

                                    <p class="lg:tw-text-lg tw-text-gray-800">{{ event.description }}</p>

                                    <div class="">
                                        <div class="tw-relative tw-h-44 lg:tw-h-96 tw-w-full tw-block tw-shadow-xl tw-overflow-hidden tw-rounded-2xl lg:tw-rounded-3xl hover:tw-scale-105 tw-transition-transform tw-duration-500"
                                            :style="{ backgroundImage: `url(/storage/${event.global_image.file_path})`, backgroundSize: 'cover' }">
                                            <div class="tw-absolute tw-bottom-0 tw-w-[100%] tw-rounded-b-2xl lg:tw-rounded-b-3xl tw-bg-black/10 tw-p-2 lg:tw-p-5 tw-backdrop-blur-md tw-backdrop-brightness-150 tw-text-white tw-font-bold tw-text-center">
                                                {{ dateFormat(event.start_date) }}
                                            </div>
                                        </div>
                                        <span class="tw-mt-3 tw-block tw-text-sm tw-text-center tw-text-gray-500">
                                            Imagen de referencia del evento | Halcones
                                        </span>
                                        <div class="tw-mt-3">
                                            <Link
                                                v-if="$page.props.auth.user"
                                                :href="route('events.show', { slug: event.slug, id: event.id } )"
                                                >
                                                <v-btn @click="showEvent" :loading="loading" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 lg:!tw-hidden" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                <v-btn @click="showEvent" :loading="loading" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-hidden lg:!tw-flex" rounded="xl" size="x-large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                <div v-if="loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-5 tw-animate-pulse">
                                                    <p class="tw-font-bold tw-text-xs lg:tw-text-base">Preparando las zonas para el evento...</p>
                                                    <iframe class="tw-size-20 lg:tw-size-40 tw-rotate-45" src="https://lottie.host/embed/bf6d5e1b-537a-436b-8464-3d074f070d76/SAdIq1oqT7.json"></iframe>
                                                </div>
                                            </Link>
                                            <Link
                                                v-else
                                                :href="route('login', { slug: event.slug, id: event.id})"
                                            >
                                                <v-btn variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 lg:!tw-hidden" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                                <v-btn variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-hidden lg:!tw-flex" rounded="xl" size="x-large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                        </Link>
                                        </div>
                                    </div>

                                    <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-justify-between lg:tw-items-center tw-gap-y-5 lg:tw-gap-y-0">
                                        <!-- Badges/Tags -->
                                        <div>
                                            <a class="tw-m-0.5 tw-inline-flex tw-items-center tw-gap-1.5 tw-py-2 tw-px-3 tw-rounded-full tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200" href="#">
                                                Deportes
                                            </a>
                                            <a class="tw-m-0.5 tw-inline-flex tw-items-center tw-gap-1.5 tw-py-2 tw-px-3 tw-rounded-full tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200" href="#">
                                                Baloncesto
                                            </a>
                                        </div>

                                        <!-- Author -->
                                        <div class="tw-flex tw-items-center tw-gap-x-3">
                                            <img class="tw-h-10 tw-w-10 tw-rounded-full" src="../../../../../public/img/user-img.svg" alt="Author Image">
                                            <div>
                                                <p class="tw-text-sm tw-font-medium tw-text-gray-800">Directiva Halcones</p>
                                                <p class="tw-text-xs tw-text-gray-500">Autor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tw-mb-10"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <aside class="lg:tw-col-span-1">
                        <div class="tw-sticky tw-top-28 tw-hidden lg:tw-block">
                            <div class="tw-p-4 tw-border tw-border-gray-200 tw-rounded-xl tw-shadow-sm">
                                <h3 class="tw-text-lg tw-font-semibold">Subscribete a nuestra seccion</h3>
                                <p class="tw-text-sm tw-text-gray-500"> Obten las ulimas y mas recientes actualizaciones de equipo de halcones de xalapa. </p>
                                <form class="tw-mt-4">

                                    <div>
                                        <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Correo electronico</p>
                                        <v-text-field
                                            color="primary"
                                            label="E-mail"
                                            placeholder="user@gmail.com"
                                            hint="Ingresa tu correo electronico"
                                            ></v-text-field>
                                    </div>
                                    <v-btn variant="elevated" class="text-none !tw-bg-tw-secondary-500 !tw-text-white tw-w-full lg:tw-w-auto" size="large" rounded="xl" block>Subscribirse</v-btn>
                                </form>
                            </div>
                            <div class="tw-h-auto tw-w-full tw-rounded-lg tw-overflow-hidden tw-mt-5 tw-flex tw-items-center tw-justify-center">
                                <!-- <div id="leaflet-map" class="tw-h-[200px] tw-w-full tw-z-10"></div> -->
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.653263134143!2d-96.91874712501097!3d19.51354808178317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85db320be3350bd1%3A0xba83c38e6e168a4!2sGimnasio%20Nido%20del%20Halc%C3%B3n%20UV!5e0!3m2!1ses-419!2smx!4v1735482228924!5m2!1ses-419!2smx" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        </aside>
                    </div>
                    </div>

                </section>

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
