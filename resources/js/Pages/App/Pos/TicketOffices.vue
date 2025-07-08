<script setup>
import { Head, Link } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import useStringFormat from '@/composables/stringFormat';
import AppNav from '@/Components/navs/AppNav.vue';
import GuestNav from '@/Components/navs/GuestNav.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';

const props =  defineProps({
    'ticket_offices': {
        type: Array,
        required: true,
    }
})
console.log(props.ticket_offices);
const currentDate = new Date();
const formattedDate = currentDate.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
});
const { formatFirstLetterUppercase } = useStringFormat();

</script>

<template>
    <Head title="Taquillas" />
    <AppNav/>
    <div class="tw-pt-5">
        <GuestNav/>
    </div>
    <main class="tw-relative tw-overflow-hidden">
        <div class="tw-absolute -tw-right-40 lg:-tw-right-96 tw-bottom-40 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[600px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
        </div>
        <section class="tw-max-w-7xl tw-min-h-screen tw-pt-28 lg:tw-pt-10 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">
            <div class="tw-w-full">
                <div class="tw-space-y-5 lg:tw-space-y-8 tw-max-w-2xl">
                    <Link :href="route('welcome')">
                        <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                            <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Regresar a eventos
                        </div>
                    </Link >

                    <h2 class="tw-text-3xl tw-font-bold lg:tw-text-6xl tw-font-bebas">Taquillas administrables</h2>

                    <div class="tw-flex tw-flex-col tw-gap-3">
                         <div class="tw-flex tw-items-center tw-gap-x-5">
                            <div class="tw-flex tw-items-center tw-gap-x-2">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>El nido del halcón
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-x-2">
                                <span class="material-symbols-outlined tw-text-xl">calendar_today</span>{{formattedDate}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Title -->
                <ErrorSession />

                <div v-if="ticket_offices" class="tw-w-full tw-flex-col lg:tw-flex-row tw-flex tw-items-start tw-justify-between tw-gap-6 tw-mt-7">
                    <!-- Grid -->
                    <div class="tw-grid sm:tw-grid-cols-2 tw-gap-6 tw-w-full lg:tw-w-2/3">
                        <!-- Card -->
                        <div :href="route('ticket-offices.show', ticketOffice.id)" v-for="ticketOffice in ticket_offices" :key="ticketOffice.id" class="tw-group tw-flex tw-flex-col">
                            <div class="tw-relative tw-pt-[50%] sm:tw-pt-[70%] tw-rounded-xl tw-overflow-hidden">
                                <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-group-hover:tw-scale-105 tw-group-focus:tw-scale-105 tw-transition-transform tw-duration-500 tw-ease-in-out tw-rounded-xl" src="https://images.pexels.com/photos/4921264/pexels-photo-4921264.jpeg" alt="Blog Image">
                                 <div class="tw-absolute tw-top-0 tw-w-full tw-h-full tw-z-50 tw-bg-black/60 tw-backdrop-blur-sm tw-flex tw-items-center tw-justify-center tw-text-white">
                                    <div class="tw-font-bold">
                                        Taquilla Número {{ ticketOffice.id }}
                                    </div>
                                </div>
                            </div>

                            <div class="tw-mt-7">
                                <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-group-hover:tw-text-gray-600">
                                    {{ formatFirstLetterUppercase(ticketOffice.name) }}
                                </h3>
                                <p class="tw-mt-3 tw-text-gray-800">
                                    {{ formatFirstLetterUppercase(ticketOffice.description) }}
                                </p>
                                <div class="tw-flex tw-items-center tw-justify-between tw-mt-5">
                                    <Link :href="route('ticket-offices.show', ticketOffice.id)">
                                        <PrimaryButton heightbtn="!tw-h-[70px]" paddingbtn="!tw-px-12">
                                            <span>Administrar taquilla</span>
                                        </PrimaryButton>
                                    </Link>
                                    <Link :href="route('ticket-offices.check')">
                                        <SecondaryButton heightbtn="!tw-h-[70px]" paddingbtn="!tw-px-12">
                                            <span>Verificar</span>
                                        </SecondaryButton>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <Footer />

    </main>
</template>

<style scoped>

</style>
