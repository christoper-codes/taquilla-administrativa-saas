<script setup>
import { Head, Link } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

defineProps({

})

</script>

<template>
    <Head title="Taquillas" />
    <GuestLayout />
    <NavigationDrawer />
    <Breadcrumb>
            <template #title>
                <span>Blogs</span>
            </template>
    </Breadcrumb>
    <main class="tw-px-4 lg:tw-px-0 tw-relative">

        <section class="tw-max-w-7xl tw-min-h-screen tw-pt-0 lg:tw-pt-10 tw-mx-auto">
            <!-- Blog Article -->
            <div class="tw-mx-auto">
            <div class="tw-grid lg:tw-grid-cols-3 tw-gap-y-8 lg:tw-gap-y-0 lg:tw-gap-x-6">
                <!-- Content -->
                <div class="lg:tw-col-span-2">
                <div class="tw-py-8 lg:tw-pe-8">
                    <div class="tw-space-y-5 lg:tw-space-y-8">
                    <Link :href="route('welcome')">
                        <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                            <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Regresar al inicio
                        </div>
                    </Link >

                    <h2 class="tw-text-3xl tw-font-bold lg:tw-text-5xl">Los Halcones de Xalapa en la Temporada 2024</h2>

                    <div class="tw-flex tw-items-center tw-gap-x-5">
                        <a class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200" href="#">
                            Notias deportivas
                        </a>
                        <p class="tw-text-xs sm:tw-text-sm tw-text-gray-800">Octubre 10, 2024</p>
                    </div>

                    <p class="tw-text-lg tw-text-gray-800">Xalapa, Veracruz - En un inicio de temporada emocionante, los Halcones de Xalapa han demostrado ser uno de los equipos más competitivos de la liga profesional de baloncesto en México. Con un roster lleno de talento y una estrategia bien definida, el equipo ha capturado la atención de los aficionados y los medios deportivos.</p>

                    <p class="tw-text-lg tw-text-gray-800">El último encuentro, celebrado en el Polideportivo de Xalapa, dejó a los espectadores al borde de sus asientos.</p>

                    <div class="tw-text-center">
                        <div class="tw-grid lg:tw-grid-cols-2 tw-gap-3">
                        <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-1 tw-gap-3">
                            <figure class="tw-relative tw-w-full tw-h-60">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="https://scontent.fjal1-1.fna.fbcdn.net/v/t39.30808-6/458729357_18018868742582807_4334428936066628979_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=127cfc&_nc_ohc=c6FKWxxkZK8Q7kNvgGQp5hq&_nc_ht=scontent.fjal1-1.fna&_nc_gid=AezX47U0yM8IhAiOLmpIn0B&oh=00_AYDcmJ2uzwntPfXESxzJ3VoYBOts6LfYXnyorYDrVV-A0A&oe=6711D902" alt="Blog Image">
                            </figure>
                            <figure class="tw-relative tw-w-full tw-h-60">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="https://scontent.fjal1-1.fna.fbcdn.net/v/t39.30808-6/458215778_848200180742444_352256646316606546_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=cc71e4&_nc_ohc=C4tcJJVEtAgQ7kNvgFef4ZB&_nc_ht=scontent.fjal1-1.fna&_nc_gid=AzNYx7-sU02J1HJdogu8stx&oh=00_AYBTfl76HzyaRbBDRDC3ygkb94_m2CUCL0kgwxuMsQXmeg&oe=6711EEF0" alt="Blog Image">
                            </figure>
                        </div>
                        <figure class="tw-relative tw-w-full tw-h-72 sm:tw-h-96 lg:tw-h-full">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="https://scontent.fjal1-1.fna.fbcdn.net/v/t39.30808-6/458501843_850305840531878_4284700994763982796_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=127cfc&_nc_ohc=TPtNOjC48f0Q7kNvgG9VAOA&_nc_ht=scontent.fjal1-1.fna&_nc_gid=AJnqoxyHmSurxUtqsv-MQmG&oh=00_AYAZoyMS6Cx4C713JDd2sbG4YCVgSyoPccD_x_0oVLBNPg&oe=6711E972" alt="Blog Image">
                        </figure>
                        </div>

                        <span class="tw-mt-3 tw-block tw-text-sm tw-text-center tw-text-gray-500">
                            Trabajando durante el proceso
                        </span>
                    </div>

                    <p class="tw-text-lg tw-text-gray-800">Los Halcones se han enfrento a distintos equipos en partidos vibrantes que han culminado con victorias y derrotas. Los jugadores estrellas del encuentro, los aleros nacionales han anotado puntos y han obtenido una destacada actuación defensiva  y ofenciva que ha sido crucial para asegurar las victorias.</p>

                    <p class="tw-text-lg tw-text-gray-800">El entrenador, de los "Halcones de xalapa", ha elogiado el esfuerzo y la dedicación de su equipo, destacando la química que han desarrollado en la cancha.<span class="tw-text-blue-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline tw-font-medium">"Estamos construyendo un equipo sólido"</span> y la mentalidad de victoria está presente en cada jugador. La afición de Xalapa merece este esfuerzo", declaró el entrenador en la conferencia de prensa pasada.</p>

                    <blockquote class="tw-text-center tw-p-4 sm:tw-px-7">
                        <p class="tw-text-xl tw-font-medium tw-text-gray-800 lg:tw-text-2xl lg:tw-leading-normal xl:tw-text-2xl xl:tw-leading-normal">
                            Los Halcones de Xalapa han comenzado la temporada con un con grandes victorias, colocándose en los primeros lugares de la tabla en temporada la 2024 - 2025.
                        </p>
                        <p class="tw-mt-5 tw-text-gray-800">
                            Directiva Halcones de Xalapa
                        </p>
                    </blockquote>

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
                </div>
                </aside>
            </div>
            </div>

        </section>

    </main>
    <Footer />
</template>

<style scoped>

</style>
