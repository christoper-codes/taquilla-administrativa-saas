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
            <span>Vive la emoción en cada partido local</span>
        </template>
    </Breadcrumb>

    <main class="tw-px-4 lg:tw-px-0 tw-relative">

        <section class="tw-max-w-7xl tw-min-h-screen tw-mx-auto">
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

                    <div class="tw-flex tw-items-center tw-gap-x-5">
                        <a class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200" href="#">
                            Notias deportivas
                        </a>
                        <p class="tw-text-xs sm:tw-text-sm tw-text-gray-800">Octubre 10, 2025</p>
                    </div>

                    <p class="tw-text-lg tw-text-gray-800">La temporada de los Halcones de Xalapa no solo se trata de baloncesto; es una fiesta completa que te espera en cada partido en casa. Cada encuentro se convierte en una experiencia inolvidable gracias al emocionante fanfest que acompaña a los juegos.</p>

                    <p class="tw-text-lg tw-text-gray-800">Los aficionados pueden disfrutar de música en vivo antes y después del partido, creando un ambiente vibrante que eleva el ánimo de todos los asistentes. Además, hay dinámicas interactivas que permiten a los seguidores participar y ganar premios, lo que hace que la experiencia sea aún más divertida.</p>

                    <div class="tw-text-center">
                        <div class="tw-grid lg:tw-grid-cols-2 tw-gap-3">
                        <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-1 tw-gap-3">
                            <figure class="tw-relative tw-w-full tw-h-60">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="../../../../../public/img/blog/blog3.jpg" alt="Blog Image">
                            </figure>
                            <figure class="tw-relative tw-w-full tw-h-60">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="../../../../../public/img/blog/blog2.jpg" alt="Blog Image">
                            </figure>
                        </div>
                        <figure class="tw-relative tw-w-full tw-h-72 sm:tw-h-96 lg:tw-h-full">
                            <img class="tw-size-full tw-absolute tw-top-0 tw-start-0 tw-object-cover tw-rounded-xl" src="../../../../../public/img/blog/blog1.jpg" alt="Blog Image">
                        </figure>
                        </div>

                        <span class="tw-mt-3 tw-block tw-text-sm tw-text-center tw-text-gray-500">
                            Trabajando durante el proceso
                        </span>
                    </div>

                    <p class="tw-text-lg tw-text-gray-800">Los fanáticos también tienen la oportunidad de llevarse un pedacito del equipo a casa con una amplia variedad de tiendas de merchandise. Desde camisetas y gorras hasta accesorios únicos, hay algo para todos los gustos.</p>

                    <p class="tw-text-lg tw-text-gray-800">Y no te preocupes por el hambre: <span class="tw-text-blue-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline tw-font-medium">"el fanfest cuenta con un bar y snackbars"</span> donde podrás degustar deliciosas opciones para disfrutar mientras animas a tu equipo. Desde botanas tradicionales hasta refrescos, todo está diseñado para que no te falte nada durante el emocionante partido.</p>

                    <blockquote class="tw-text-center tw-p-4 sm:tw-px-7">
                        <p class="tw-text-xl tw-font-medium tw-text-gray-800 lg:tw-text-2xl lg:tw-leading-normal xl:tw-text-2xl xl:tw-leading-normal">
                            Así que, si aún no has asistido a un juego de los Halcones de Xalapa, ¡no esperes más! Ven y vive la experiencia completa en el fanfest. ¡Te esperamos en la próxima cita en casa para disfrutar del mejor baloncesto y mucho más
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
