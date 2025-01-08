<script setup>
import { Head, Link } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props =  defineProps({
    'ticket_offices': {
        type: Array,
        required: true,
    }
})

</script>

<template>
    <Head title="Taquillas" />
    <GuestLayout />
    <NavigationDrawer />
    <Breadcrumb>
            <template #title>
                <span>Taquillas disponibles</span>
            </template>
    </Breadcrumb>

    <main class="tw-relative tw-overflow-hidden">
        <section class="tw-max-w-7xl tw-min-h-screen tw-pt-28 lg:tw-pt-10 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">

                <!-- Card Blog -->
                <div class="tw-w-full">
                    <!-- Title -->
                    <div class="tw-space-y-5 lg:tw-space-y-8 tw-max-w-2xl">
                        <Link :href="route('welcome')">
                            <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Regresar al inicio
                            </div>
                        </Link >

                        <h2 class="tw-text-3xl tw-font-bold lg:tw-text-[40px]">Taquillas administrables para el club halcones de xalapa</h2>

                        <div class="tw-flex tw-items-center tw-gap-x-5">
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>El nido del halcon
                            </div>
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">calendar_today</span>000000
                            </div>
                            <Link :href="route('ticket-offices.check')">
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl">Verificar</v-btn>
                            </Link>
                            <Link :href="route('ticket-offices.search')">
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl">Buscar Boletos</v-btn>
                            </Link>
                        </div>
                    </div>
                    <!-- End Title -->
                    <ErrorSession />

                    <div v-if="ticket_offices" class="tw-w-full tw-flex-col lg:tw-flex-row tw-flex tw-items-start tw-justify-between tw-gap-6 tw-mt-7">
                        <!-- Grid -->
                        <div class="tw-grid sm:tw-grid-cols-2 tw-gap-6 tw-w-full lg:tw-w-2/3">
                            <!-- Card -->
                            <Link :href="route('ticket-offices.show', ticketOffice.id)" v-for="ticketOffice in ticket_offices" :key="ticketOffice.id" class="tw-group tw-flex tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500">
                                <div class="tw-relative tw-pt-[50%] sm:tw-pt-[70%] tw-rounded-xl tw-overflow-hidden">
                                    <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-group-hover:tw-scale-105 tw-group-focus:tw-scale-105 tw-transition-transform tw-duration-500 tw-ease-in-out tw-rounded-xl" src="https://i.pinimg.com/564x/4a/04/11/4a04110cc00a352c8c8bc63c4731db1c.jpg" alt="Blog Image">
                                    <span class="tw-absolute tw-top-0 tw-end-0 tw-rounded-se-xl tw-rounded-es-xl tw-text-xs tw-font-medium tw-bg-purple-600 tw-text-white tw-py-1.5 tw-px-3">
                                        {{ ticketOffice.name }}
                                    </span>
                                </div>

                                <div class="tw-mt-7">
                                    <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-group-hover:tw-text-gray-600">
                                        {{ ticketOffice.name }}
                                    </h3>
                                    <p class="tw-mt-3 tw-text-gray-800">
                                        {{ ticketOffice.description }}
                                    </p>
                                    <p class="tw-mt-5 tw-inline-flex tw-items-center tw-gap-x-1 tw-text-sm tw-text-purple-600 tw-decoration-2 tw-group-hover:tw-underline tw-group-focus:tw-underline tw-font-medium">
                                        ver mas
                                    <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                    </p>
                                </div>
                            </Link>
                            <!-- End Card -->
                        </div>
                        <!-- End Grid -->
                        <!-- Card -->
                        <div class="tw-group tw-relative tw-flex tw-flex-col tw-w-full lg:tw-w-1/3 tw-min-h-96  tw-bg-slate-950 tw-rounded-xl tw-overflow-hidden  hover:tw-scale-105 tw-transition-all tw-duration-500">
                            <div class="tw-absolute tw-left-1/2 tw-top-[80%] tw-h-[1280px] tw-w-[800px] tw--translate-x-1/2 tw-rounded-full tw-bg-gradient-to-t tw-blur-[250px] tw-from-tw-primary-800 tw-to-tw-primary-600">
                            </div>
                            <div class="tw-flex-auto tw-p-4 md:tw-p-6 tw-relative tw-z-20">
                                <h3 class="tw-text-lg tw-text-white/90 tw-group-hover:tw-text-white tw-bg-white/10 tw-p-3 tw-rounded-md"><span class="tw-font-bold">Taquillas</span> de el club halcones de xalapa para administrar puntos de venta y usuarios</h3>
                            </div>
                            <div class="tw-pt-0 tw-p-4 md:tw-p-6 tw-relative tw-z-20">
                                <div class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-medium tw-text-white tw-group-hover:tw-text-white/70 tw-group-focus:tw-text-white/70">
                                    <span>Ver mas</span>
                                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                </div>
                <!-- End Card Blog -->
        </section>

        <Footer />

    </main>
</template>

<style scoped>

</style>
