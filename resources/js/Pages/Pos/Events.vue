<script setup>
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { onMounted } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';

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

    <section class="tw-overflow-hidden tw-mt-[100px] lg:tw-mt-[111px]">
        <v-parallax
            class="tw-h-96 md:tw-h-auto" src="/storage/public/back-hdx-img.jpg"
        >
            <div class="d-flex flex-column fill-height justify-start align-start text-white tw-bg-purple-500/50">

            </div>
        </v-parallax>
    </section>

    <section class="tw-w-full tw-min-h-screen tw-bg-white tw-mt-[-37px] tw-rounded-[35px] lg:tw-rounded-[60px] tw-relative">
        <div class="tw-absolute -tw-top-16 tw-left-20 lg:tw-block tw-hidden ">
            <div class="tw-flex tw-items-center tw-gap-5 tw-w-36">
                <v-btn color="white" variant="elevated" class="text-none" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl">info</span>Informacion</v-btn>
                <v-btn color="white" variant="elevated" class="text-none" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl">share</span>Compartir</v-btn>
            </div>
        </div>
        <div class="tw-max-w-7xl tw-mx-auto tw-py-7 lg:tw-py-12 tw-px-4 lg:tw-px-0">
            <div class="lg:tw-hidden tw-flex tw-items-center tw-justify-center tw-gap-5 tw-w-full">
                <v-btn color="purple" variant="tonal" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">info</span>Informacion</v-btn>
                <v-btn color="purple" variant="tonal" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">share</span>Compartir</v-btn>
            </div>

            <main class="">

                <div class="tw-mt-5 lg:tw-mt-0">
                    <h3 class="lg:tw-text-4xl tw-text-2xl tw-font-bold tw-text-gray-500">Eventos disponibles</h3>
                </div>

                <div class="tw-mt-5 lg:tw-mt-10">
                    <div class="tw-h-[200px] lg:tw-h-[250px] tw-w-full mt-3 tw-shadow-xl tw-rounded-2xl tw-overflow-hidden">
                        <div id="leaflet-map" class="tw-h-[200px] lg:tw-h-[250px] tw-w-full tw-z-10 tw-rounded-2xl tw-overflow-hidden"></div>
                    </div>
                </div>

                <ErrorSession />

                <div v-if="events" class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-items-center tw-justify-between tw-gap-10 tw-w-full tw-mt-10 lg:tw-mt-16">
                    <div v-for="event in events" :key="event.id">
                        <div class="tw-w-full tw-flex tw-flex-col md:tw-flex-row tw-items-start tw-justify-between tw-gap-5">
                        <div class="tw-w-full md:tw-w-[60%]">
                            <div class="tw-h-[180px] tw-w-full tw-block tw-shadow-xl tw-overflow-hidden tw-rounded-lg hover:tw-translate-y-[-10px] tw-transition-transform tw-duration-500"
                            :style="{ backgroundImage: `url(/storage/${event.global_image.file_path})`, backgroundSize: 'cover' }">
                                <div class="tw-bg-white tw-py-1 tw-px-1.5 tw-rounded-lg tw-inline-flex tw-ml-3 tw-mt-3">
                                    🔥
                                </div>
                            </div>
                            <div class="tw-mt-5">
                                <Link
                                    v-if="$page.props.auth.user"
                                    :href="route('eventos.show', { slug: event.slug, id: event.id } )"
                                    >
                                    <v-btn variant="elevated" class="text-none !tw-bg-tw-secondary-300 !tw-text-tw-secondary-700" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                </Link>
                                <Link
                                    v-else
                                    :href="route('login', { slug: event.slug, id: event.id})"
                                >
                                    <v-btn variant="elevated" class="text-none !tw-bg-tw-secondary-300 !tw-text-tw-secondary-700" rounded="lg" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">shopping_cart</span>Adquirir boletos</v-btn>
                                </Link>
                            </div>
                        </div>
                        <div class="tw-w-full md:tw-w-[40%] tw-text-gray-600">
                           <h4 class="tw-font-bold tw-text-lg">Info</h4>
                            <div class="tw-text-sm tw-flex tw-flex-col tw-gap-2">
                                <div> {{ event.name }} </div>
                                <div>📍 | El nido del halcon. Xalapa Ver.</div>
                                <div>🔥 | 2x1 en boletos para zonas A Y B</div>
                                <div>🎧 | Fan fest en el nido</div>
                                <div>📅 | {{ event.start_date }} </div>
                            </div>
                           <div class="tw-flex tw-gap-2 lg:tw-flex-col tw-w-44 tw-mt-2">
                                <v-btn color="purple" variant="tonal" class="text-none" rounded="lg" size="small"><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">person</span>Edad +3</v-btn>
                                <v-btn color="purple" variant="tonal" class="text-none" rounded="lg" size="small"><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">checkroom</span>Vetimenta | casual</v-btn>
                           </div>
                        </div>
                    </div>
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
