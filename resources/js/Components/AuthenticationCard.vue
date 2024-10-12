<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';

const slides = ref([
  'La interfaz es extremadamente intuitiva y fácil de usar, lo que hace que el proceso de compra sea rápido y sin complicaciones',
  'Esta aplicación ha revolucionado la forma en que compro boletos para eventos deportivos. Antes, solía ser un proceso tedioso y estresante',
  'Soy un gran aficionado a los deportes y he probado varias aplicaciones para comprar boletos, pero ninguna se compara con esta',
  'La primera vez que usé esta aplicación para comprar boletos deportivos, quedé impresionado por lo fácil y rápido que fue todo el proceso.',
  'Esta aplicación ha hecho que comprar boletos para eventos deportivos sea una experiencia agradable y sin estrés.',
]);

</script>

<template>
   <main class="tw-min-h-screen tw-w-full tw-flex tw-flex-col lg:tw-flex-row lg:tw-justify-between">
    <div class="tw-hidden tw-min-h-screen tw-w-[35%] tw-bg-center tw-bg-no-repeat lg:tw-flex tw-flex-col tw-justify-between tw-gap-10 tw-p-10"
        style="background-image: url('/storage/public/img-login.jpg'); background-size: cover; position: relative;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(169, 0, 254, 0.5);"></div>


            <div class="tw-relative">
                <Link :href="route('welcome')" class="tw-flex tw-flex-col tw-gap-1">
                    <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                    <div class="tw-font-bold tw-text-white tw-text-3xl">
                        <span class="tw-block">Halcones<span class="tw-font-extralight">xalapa</span></span>
                    </div>
                </Link>
            </div>

            <div class="tw-mt-[-50px]">
                <v-carousel
                    height="400"
                    show-arrows="hover"
                    cycle
                    hide-delimiter-background
                >
                    <v-carousel-item
                    v-for="(slide, i) in slides"
                    :key="i"
                    >
                    <v-sheet
                        color="transparent"
                        height="100%"
                    >
                        <div class="d-flex fill-height justify-center align-center">
                            <div>
                                <div class="tw-flex tw-gap-3 tw-items-center tw-text-white">
                                    <img class="tw-size-10 tw-rounded-full" src="https://modernize-nuxt3-main.netlify.app/images/profile/user-1.jpg" alt="avatar">
                                    <div class="tw-flex tw-flex-col">
                                        <h3 class="tw-font-semibold">Usuraio de la aplicacion</h3>
                                        <h4 class="tw-text-xs">@usuario-fan</h4>
                                    </div>
                                </div>
                                <div>
                                    <p class="tw-text-white tw-mt-5 tw-font-medium tw-text-lg">{{ slide }}</p>
                                </div>
                            </div>
                        </div>
                    </v-sheet>
                    </v-carousel-item>
                </v-carousel>
            </div>

            <div>
                <div class="tw-flex tw-items-center tw-justify-center">
                    <div class="tw-flex tw-items-center tw-gap-2 tw-relative">
                        <span class="tw-text-white">¿No tienes una cuenta?</span>
                        <Link :href="route('register')">
                            <v-btn color="white" variant="tonal" class="text-none" rounded="lg">Registrate</v-btn>
                        </Link>
                    </div>
                </div>
            </div>

        </div>
        <div class="tw-min-h-screen tw-w-full lg:tw-w-[65%] tw-bg-white tw-flex tw-items-center tw-justify-center lg:tw-py-20">
            <div class="tw-w-full tw-h-auto">
                <div class="lg:tw-hidden">
                    <GuestLayout/>
                </div>
                <slot />
            </div>
        </div>
    </main>
</template>

