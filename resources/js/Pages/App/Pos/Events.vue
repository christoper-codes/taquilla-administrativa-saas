<script setup>
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import useDateFormat from '@/composables/dateFormat';
import useUserPolicy from '@/composables/UserPolicy';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';
import GuestNav from '@/Components/navs/GuestNav.vue';
import GuestNavSocial from '@/Components/navs/GuestNavSocial.vue';
import { menuStateSocialMedia, menuStateApp } from '@/composables/nav/menu-state.js'
import AppNav from '@/Components/navs/AppNav.vue';
import CashRegisterNav from '@/Components/navs/CashRegisterNav.vue';
import Banner from '@/Components/Banner.vue';

const { dateFormat } = useDateFormat();
const loading = ref(false);
const filteredEvents = ref([]);
const { viewVendorTopics, viewAdminTopics } = useUserPolicy();
const showEvent = () => {loading.value = true;}

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
    user_roles: {
        type: Array,
        required: false,
    },
    platform_settings: {
        type: Array,
        required: true,
    },
});

const parallaxY = ref(0);
const isLg = ref(window.innerWidth >= 1024);

const handleScroll = () => {
  if (isLg.value) {
    parallaxY.value = window.scrollY * 0.5;
  } else {
    parallaxY.value = 0;
  }
};

const handleResize = () => {
  isLg.value = window.innerWidth >= 1024;
  if (!isLg.value) {
    parallaxY.value = 0;
  }
};

onMounted(() => {
    filteredEvents.value = props.events.filter((event) => {
        if(event.event_visibility_type.name == 'publico'){
            return true;
        }
        if(props.user_roles){
            if(event.event_visibility_type.name == 'vendedores' && viewVendorTopics(props.user_roles)){
                return true;
            }
        }
        if(event.event_visibility_type.name == 'administradores' && viewAdminTopics(props.user_roles)){
            return true;
        }

        return false;
    });

    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
});


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
</script>

<template>
    <Head title="Eventos" />
    <CashRegisterNav v-bind:user_roles="user_roles"/>
    <GuestNavSocial />
    <AppNav />
    <Banner :banner="platform_settings[0].settings.banner"/>
    <transition name="fade">
        <div v-if="showImageModal"
            class="tw-fixed tw-inset-0 !tw-z-50 tw-flex tw-items-center tw-justify-center tw-bg-black/50 tw-backdrop-blur-[7px] tw-transition-all tw-duration-500"
            @click.self="closeImageModal"
        >
            <div class="tw-bg-gradient-to-tr tw-from-white tw-to-primary tw-shadow-2xl tw-rounded tw-p-3 tw-relative tw-max-w-full tw-w-[90vw] md:tw-w-[520px] tw-flex tw-flex-col tw-items-center tw-transition-all tw-duration-500">
                <img class="tw-w-full tw-h-auto" :src="modalImageSrc" alt="Imagen ampliada" />
            </div>
        </div>
    </transition>
    <section class="lg:tw-h-screen !tw-p-4 sm:!tw-p-8 tw-flex tw-w-full tw-relative">
        <div class="tw-rounded-3xl tw-w-full tw-overflow-hidden tw-relative tw-z-10 tw-shadow-xl tw-shadow-black/15">
            <div class="tw-relative tw-h-full">
                <div class="tw-relative tw-isolate tw-overflow-hidden tw-w-full tw-h-full">
                    <div class="tw-block tw-absolute tw-left-[-40%] md:tw-left-[43%] tw-top-0 tw-right-0 tw-bottom-0 tw-bg-cover tw-z-0"
                        :style="{backgroundImage: `url('/storage/public/hero-img.jpg')`, transform: isLg ? `translateY(${parallaxY}px)` : 'none', willChange: 'transform',}">
                    </div>
                    <div class="tw-absolute tw-left-[-40%] md:tw-left-[43%] tw-top-0 tw-right-0 tw-bottom-0 tw-bg-cover tw-bg-black/30 tw-z-10"></div>
                    <div class="tw-absolute !tw-inset-y-0 !tw-right-1/2 tw-z-10 !tw-w-[200%] !tw-origin-top-right !tw-skew-x-[-30deg] tw-bg-white tw-ring-1 tw-shadow-xl !tw-shadow-purple-500 !tw-ring-purple-500/10 tw--mr-80 lg:!tw--mr-96" aria-hidden="true">
                    </div>

                    <div class="tw-hidden lg:tw-block tw-pt-3">
                        <GuestNav/>
                    </div>
                    <div class="tw-flex tw-items-center tw-justify-between lg:tw-hidden tw-relative tw-z-10 tw-px-5 tw-pt-7">
                        <div class="tw-flex tw-items-center tw-relative">
                            <h2 class="tw-font-extrabold tw-font-bebas tw-text-2xl !tw-mt-1">HALCONES</h2>
                            <img class="tw-w-10 tw-h-auto tw-absolute -tw-top-5 -tw-left-3" src="/storage/public/hdx-logo.png" alt="hdx logo">
                        </div>
                        <div @click="menuStateApp = !menuStateApp">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon" class="tw-size-8 tw-mb-1"><path fill-rule="evenodd" d="M3 9a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9Zm0 6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>

                    <div class="tw-flex lg:tw-items-center tw-h-full !tw-mb-12 lg:!tw-mb-0 tw-z-20 tw-relative">
                        <div class="!tw-mx-auto !tw-max-w-7xl !tw-px-6 lg:!tw-px-0 !tw-w-full !tw-py-32 lg:!tw-py-0 lg:!tw--mt-40">
                            <div>
                                <h2 data-aos="fade-down" data-aos-duration="1300" data-aos-once="true" class="tw-font-metal tw-pr-1 tw-italic tw-text-2xl tw-inline-block tw-bg-clip-text tw-bg-gradient-to-r tw-from-primary tw-to-secondary/60 tw-text-transparent">Proximo partido</h2>
                                <h1 data-aos="fade-down" data-aos-duration="1300" data-aos-once="true" class="lg:tw-text-[70px] !tw-max-w-2xl tw-text-4xl md:tw-text-5xl tw-font-bold tw-font-bebas tw-mt-3">
                                    {{ filteredEvents[0]?.name || 'Nuevos partidos próximamente' }}
                                </h1>

                                <p data-aos="fade-down" data-aos-duration="1300" data-aos-once="true" class="tw-font-medium tw-text-balance sm:!tw-text-xl tw-max-w-[900px] !tw-mt-7">
                                    {{ filteredEvents[0]?.description || 'El nido del halcon' }} | {{ filteredEvents[0]?.start_date ? dateFormat(filteredEvents[0]?.start_date) : 'Nuevas fechas' }}.
                                    <br>
                                    Vive la emoción del baloncesto con los <span class="tw-font-bold">Halcones de Xalapa</span>.
                                </p>

                                <div data-aos="fade-left" data-aos-duration="1300" data-aos-once="true" class="tw-flex tw-flex-col md:tw-flex-row md:tw-items-center tw-gap-5 !tw-mt-10">
                                    <Link v-if="$page.props.auth.user" :href="route('events.show', { slug: filteredEvents[0]?.slug || '', id: filteredEvents[0]?.id || '' })">
                                        <PrimaryButton @click="showEvent" heightbtn="!tw-h-[70px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-14" :loading="loading">
                                            <span class="material-symbols-outlined tw-text-lg">shopping_cart</span>Comprar boletos
                                        </PrimaryButton>
                                    </Link>
                                    <Link v-else :href="route('login', { slug: filteredEvents[0]?.slug || '', id: filteredEvents[0]?.id || '' })">
                                        <PrimaryButton heightbtn="!tw-h-[70px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-14">
                                           <span class="material-symbols-outlined tw-text-lg">shopping_cart</span>Comprar boletos
                                        </PrimaryButton>
                                    </Link>

                                    <SecondaryButton
                                        heightbtn="!tw-h-[70px] !tw-text-base !tw-w-full md:!tw-w-auto"
                                        paddingbtn="!tw-px-14"
                                        @click="openImageModal('/storage/public/calendar-hdx-2025.jpg')"
                                    >
                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-1">
                                            <p>Ver calendario</p>
                                        </div>
                                    </SecondaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="tw-max-w-7xl tw-mx-auto tw-px-4 lg:tw-px-0 tw-relative">
        <div class="tw-absolute -tw-right-40 lg:-tw-right-96 -tw-top-52 lg:-tw-top-52 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[500px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
        </div>
        <div class="tw-hidden lg:tw-block tw-absolute -tw-bottom-60 -tw-left-72 tw-h-[1300px] tw-w-[1500px] tw-rounded-full tw-blur-[100px] tw-bg-white">
        </div>
        <ErrorSession />
        <div class="tw-max-w-7xl tw-min-h-screen tw-pt-0 tw-mx-auto tw-z-20 tw-relative">
            <div class="lg:tw-grid lg:tw-grid-cols-3 tw-gap-y-8 lg:tw-gap-y-0 lg:tw-gap-x-6">
                <div class="lg:tw-col-span-2">
                    <div v-if="events.length > 0" class="tw-py-8 lg:tw-pe-8">
                        <div v-for="event in filteredEvents" :key="event.id"  class="tw-space-y-5 lg:tw-space-y-8 tw-pb-10 tw-mt-16 tw-border-b-4 lg:tw-border-b-8 tw-border-neutral-300 tw-border-dashed">
                            <div>
                                <h2 class="tw-font-bebas tw-text-4xl tw-font-bold lg:tw-text-6xl">{{ event.name }}</h2>
                                <h3 class="tw-mt-5 tw-inline-block tw-font-metal tw-pr-1 tw-italic tw-text-2xl tw-bg-clip-text tw-bg-gradient-to-r tw-from-primary tw-to-secondary/60 tw-text-transparent">{{ dateFormat(event.start_date) }}</h3>
                                <p class="tw-mt-3">{{ event.description }}</p>
                                <p class="tw-mt-3">Gimnasio Nido del Halcón UV | 91094 Xalapa-Enríquez, Ver.</p>
                                <div class="tw-flex tw-flex-col md:tw-flex-row md:tw-items-center tw-gap-5 tw-mt-4">
                                    <Link v-if="$page.props.auth.user" :href="route('events.show', { slug: event.slug, id: event.id } )">
                                        <PrimaryButton @click="showEvent" heightbtn="!tw-h-[65px] !tw-px-14 !tw-w-full md:!tw-w-auto" :loading="loading">
                                            <span class="material-symbols-outlined tw-text-lg">shopping_cart</span>Comprar boletos
                                        </PrimaryButton>
                                    </Link>
                                    <Link v-else :href="route('login', { slug: event.slug, id: event.id})">
                                        <PrimaryButton  heightbtn="!tw-h-[65px] !tw-px-14">
                                            <span class="material-symbols-outlined tw-text-lg">shopping_cart</span>Comprar boletos
                                        </PrimaryButton>
                                    </Link>
                                </div>
                            </div>

                            <div
                                @click="openImageModal(`/storage/${event.global_image.file_path}`)"
                                class="tw-group tw-flex tw-bg-cover tw-relative tw-h-[400px] tw-aspect-3/4 tw-object-cover tw-bg-center tw-w-full tw-p-4 lg:tw-p-7 tw-rounded-xl tw-border tw-shadow-xl tw-overflow-hidden tw-cursor-pointer tw-transition-all tw-duration-500"
                                :style="`background-image: url(/storage/${event.global_image.file_path})`"
                                >
                                <div class="tw-absolute tw-inset-0 tw-bg-black/60 tw-opacity-0 group-hover:tw-opacity-100 tw-transition-all tw-duration-500 tw-z-40 tw-flex tw-items-center tw-justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-10 tw-h-10 tw-text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2"/><line x1="11" y1="8" x2="11" y2="14" stroke="currentColor" stroke-width="2"/><line x1="8" y1="11" x2="14" y2="11" stroke="currentColor" stroke-width="2"/></svg>
                                </div>

                                <div class="tw-z-50 tw-flex tw-flex-col tw-flex-1 tw-justify-end tw-text-white">
                                    <div class="tw-flex-col tw-gap-4 tw-justify-end">
                                    <h2 class="tw-inline-block tw-font-metal tw-pr-1 tw-italic tw-text-5xl tw-bg-clip-text tw-bg-gradient-to-r tw-from-primary tw-to-secondary tw-text-transparent !tw-underline">
                                        Ampliar imagen
                                    </h2>
                                    </div>
                                </div>
                                <div class="tw-z-0 tw-absolute tw-bottom-0 tw-left-0 tw-right-0 tw-h-[350px] lg:tw-h-[300px] !tw-bg-[linear-gradient(180deg,rgba(0,0,0,0)_0%,#000_90%)] tw-block"></div>
                            </div>

                            <div class="tw-flex tw-items-center tw-justify-end tw-mt-5">
                                <div class="tw-flex tw-items-center tw-gap-x-3">
                                    <img class="tw-h-14 tw-w-14 tw-rounded-full" src="../../../../../public/img/user-img.svg" alt="Author Image">
                                    <div>
                                        <p class="tw-text-base tw-font-medium tw-text-gray-800">Directiva Halcones</p>
                                        <p class="tw-text-sm tw-text-gray-500">Autor</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <aside :class="events.length > 0 ? 'lg:tw-col-span-1 tw-hidden lg:tw-block' : 'tw-col-span-1 lg:tw-col-span-3'">
                    <div v-if="events.length > 0" :class="events.length > 0 ? 'tw-sticky tw-top-10 tw-mb-20' : 'tw-top-10 tw-mb-20'">
                        <div class="tw-h-auto tw-w-full tw-rounded-lg tw-border-8 tw-border-neutral-200 tw-overflow-hidden tw-mt-10 tw-flex tw-items-center tw-justify-center">
                            <iframe v-if="events.length > 0" class="tw-rounded-lg" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.653263134143!2d-96.91874712501097!3d19.51354808178317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85db320be3350bd1%3A0xba83c38e6e168a4!2sGimnasio%20Nido%20del%20Halc%C3%B3n%20UV!5e0!3m2!1ses-419!2smx!4v1735482228924!5m2!1ses-419!2smx" width="400" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <iframe v-else class="tw-w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.653263134143!2d-96.91874712501097!3d19.51354808178317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85db320be3350bd1%3A0xba83c38e6e168a4!2sGimnasio%20Nido%20del%20Halc%C3%B3n%20UV!5e0!3m2!1ses-419!2smx!4v1735482228924!5m2!1ses-419!2smx" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div v-else class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-5 tw-pt-60">
                        <div class="tw-text-center tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-5">
                            <img class="tw-w-40 lg:tw-w-72 tw-h-auto" src="/storage/public/empty-cart.webp" alt="Webiste image">
                            <span>Nuevos partidos próximamente</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
    <Footer />
</template>
