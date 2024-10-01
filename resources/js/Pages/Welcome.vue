<script setup>
import { Head, Link } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import ClubDrawer from '@/Components/ClubDrawer.vue';
import { ref } from 'vue';

const activePanel = ref([0]);
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <GuestLayout />
    <NavigationDrawer />
    <ClubDrawer />

    <main class="tw-px-4 lg:tw-px-0 tw-relative tw-overflow-hidden">
        <img class="tw-w-full tw-scale-100 tw-h-auto tw-absolute tw-top-0 tw-right-0 -tw-z-10 " src="https://salient.tailwindui.com/_next/static/media/background-faqs.55d2e36a.jpg" alt="">

        <section class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-10 tw-overflow-hidden tw-min-h-screen tw-pt-28 lg:tw-pt-0">
            <div class="tw-w-full tw-flex tw-items-center tw-justify-center">
                <div class="tw-flex tw-flex-col tw-gap-7 lg:tw-pl-[15%]">
                    <div class="tw-flex tw-gap-3 tw-items-center">
                        <img class="tw-w-20 tw-h-auto" src="https://adminmart.com/wp-content/uploads/2023/01/users.png" alt="users">
                        <p><span class="tw-font-bold">1,903+</span> Fanaticos & publico han usado nuestra plataforma.</p>
                    </div>
                    <div class="tw-font-bold tw-text-4xl md:tw-text-6xl tw-max-w-2xl tw-leading-[50px] md:tw-leading-[70px]">Aplicacion web oficial del equipo Halcones de xalapa!</div>

                    <p class="tw-text-lg">Descubre las <span class="tw-font-bold">nuevas novedades en la plataforma oficial</span> del equipo de baloncesto, como comprar boletos con increibles promociones u ofertas especiales</p>

                  <div class="tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-gap-4 md:tw-gap-5">
                    <div class="tw-w-full lg:tw-w-auto">
                        <v-tooltip color="primary" location="bottom center" origin="auto" no-click-animation>
                        <template v-slot:activator="{ props }">
                            <Link
                                :href="route('eventos.index')"
                            >
                                <v-btn v-bind="props" variant="elevated" class="text-none tw-block tw-w-full lg:tw-w-auto !tw-bg-tw-primary-500 !tw-text-white" size="large" rounded="lg">Proximos partios</v-btn>
                            </Link>
                        </template>
                        <div>Partidos oficiales!</div>
                        </v-tooltip>
                    </div>
                    <div class="tw-w-full lg:tw-w-auto">
                        <v-tooltip color="primary" location="bottom center" origin="auto" no-click-animation>
                        <template v-slot:activator="{ props }">
                            <v-btn v-bind="props" variant="tonal" class="text-none tw-block tw-w-full lg:tw-w-auto !tw-bg-tw-primary-100 !tw-text-tw-primary-600" size="large" rounded="lg">Mis boletos</v-btn>
                        </template>
                        <div>Tickets!</div>
                        </v-tooltip>
                    </div>

                  </div>
                </div>
            </div>
            <div class="tw-w-full tw-bg-transparent tw-flex tw-items-start tw-justify-between tw-gap-5 tw-h-96 lg:tw-h-screen tw-overflow-hidden">
                <div class="tw-w-full tw-h-full slider-up">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                </div>
                <div class="tw-w-full tw-h-full slider-down">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                    <img class="tw-w-full tw-h-full" src="../../../public/img/bannerimg1.svg" alt="">
                </div>
            </div>
        </section>

        <section>
            <!-- Testimonials with Stats -->
            <div class="tw-max-w-[85rem] tw-px-4 tw-py-10 sm:tw-px-6 lg:tw-px-8 lg:tw-py-32 tw-mx-auto">
            <!-- Grid -->
            <div class="tw-flex tw-items-start tw-justify-between tw-gap-20 tw-flex-col md:tw-flex-row">
                <div>
                <!-- Title -->
                <div class="tw-mb-8">
                    <h2 class="tw-mb-2 tw-text-3xl tw-text-gray-800 tw-font-bold lg:tw-text-4xl">
                        Todo con velocidad
                    </h2>
                    <p class="tw-text-gray-600">
                        Nuestro equipo de soporte está listo para ayudarte con cualquier pregunta que tengas.
                    </p>
                </div>
                <!-- End Title -->

                <!-- Blockquote -->
                <blockquote class="tw-relative">
                    <svg class="tw-absolute tw-top-0 tw-start-0 tw-transform -tw-translate-x-6 -tw-translate-y-8 tw-size-16 tw-text-gray-300" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z" fill="currentColor"/>
                    </svg>

                    <div class="tw-relative tw-z-10">
                    <p class="tw-text-xl tw-italic tw-text-gray-800">
                        Los Halcones de Xalapa estan preparados para la nueva temporada
                    </p>
                    </div>

                    <footer class="tw-mt-6">
                    <div class="tw-flex tw-items-center tw-gap-x-4">
                        <div class="tw-shrink-0">
                        <img class="tw-size-8 tw-rounded-full" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="Avatar">
                        </div>
                        <div class="tw-grow">
                        <div class="tw-font-semibold tw-text-gray-800">Josh Grazioso</div>
                        <div class="tw-text-xs tw-text-gray-500">Director & Entrenador | <span class="tw-font-bold">Halcones</span>Xalapa</div>
                        </div>
                    </div>
                    </footer>
                </blockquote>
                <!-- End Blockquote -->
                </div>
                <!-- End Col -->

                <div>
                <div>
                    <!-- List -->
                    <!-- Card Blog -->
                    <div class="tw-max-w-[85rem] md:tw-px-0 tw-mx-auto">
                    <!-- Grid -->
                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start tw-justify-between tw-gap-10">

                        <!-- Card -->
                        <a class="tw-w-full lg:tw-w-1/2 group tw-flex tw-flex-col tw-h-full hover:tw-translate-y-[-5px] focus:tw-outline-none focus:tw-border-transparent focus:tw-shadow-lg tw-transition tw-duration-500 tw-rounded-xl" href="#">
                        <div class="tw-h-60 tw-w-full">
                            <img class="tw-h-full tw-w-full tw-object-cover tw-object-top tw-rounded-xl tw-bg-gray-200" src="" alt="Blog Image">
                        </div>
                        <div class="tw-my-6">
                            <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800">
                                How Google Assistant now helps you record stories for kids
                            </h3>
                            <p class="tw-mt-5 tw-text-gray-600">
                                Google is constantly updating its consumer AI, Google Assistant, with new features.
                            </p>
                        </div>
                        <div class="tw-mt-auto tw-flex tw-items-center tw-gap-x-3">
                            <img class="tw-size-8 tw-rounded-full" src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="Avatar">
                            <div>
                            <h5 class="tw-text-sm tw-text-gray-800">By Aaron Larsson</h5>
                            </div>
                        </div>
                        </a>
                        <!-- End Card -->

                        <!-- Card -->
                        <a class="tw-w-full lg:tw-w-1/2 group tw-flex tw-flex-col tw-h-full hover:tw-translate-y-[-5px] focus:tw-outline-none focus:tw-border-transparent focus:tw-shadow-lg tw-transition tw-duration-500 tw-rounded-xl" href="#">
                        <div class="tw-h-60 tw-w-full">
                            <img class="tw-h-full tw-w-full tw-object-cover tw-object-top tw-rounded-xl tw-bg-gray-200" src="" alt="Blog Image">
                        </div>
                        <div class="tw-my-6">
                            <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800">
                                Front accounts - lets work together
                            </h3>
                            <p class="tw-mt-5 tw-text-gray-600">
                                Are you an accountant? Are you a company formation advisor?
                            </p>
                        </div>
                        <div class="tw-mt-auto tw-flex tw-items-center tw-gap-x-3">
                            <img class="tw-size-8 tw-rounded-full" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="Avatar">
                            <div>
                            <h5 class="tw-text-sm tw-text-gray-800">By Lauren Waller</h5>
                            </div>
                        </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <!-- End Grid -->

                    <!-- Card -->
                    <div class="tw-mt-12 tw-text-center tw-w-full lg:tw-w-1/4 tw-mx-auto">
                        <v-btn  href="#" variant="tonal" class="text-none tw-block tw-w-full lg:tw-w-auto !tw-bg-tw-primary-100 !tw-text-tw-primary-600" size="large" rounded="lg">Leer mas</v-btn>
                    </div>
                    <!-- End Card -->
                    </div>
                    <!-- End Card Blog -->
                </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
            </div>
            <!-- End Testimonials with Stats -->
        </section>

        <section class="tw-pb-0 lg:tw-pb-32 tw-h-[410px] lg:tw-h-[600px] tw-overflow-hidden">
            <v-parallax
               class="tw-rounded-3xl lg:tw-rounded-none tw-bg-purple-500/50" src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg"
            >
                <div class="d-flex flex-column fill-height justify-center align-center text-white tw-bg-purple-500/50">
                    <div class="tw-max-w-5xl tw-bg-white/10 tw-p-16 tw-rounded-3xl tw-text-center tw-backdrop-blur-md ">
                        <h1 class="tw-text-4xl lg:tw-text-6xl tw-font-bold tw-w-full">
                            Halcones de Xalapa
                        </h1>
                        <h4 class="tw-mt-10 tw-text-lg lg:tw-text-xl">
                            Descubre las <span class="tw-font-semibold">ultimas noticias y novedades del </span>equipo de baloncesto Halcones de Xalapa!
                        </h4>
                    </div>
                </div>
            </v-parallax>
        </section>

        <section>

            <!-- FAQ -->
            <div class="tw-max-w-[85rem] sm:tw-px-6 lg:tw-px-8 tw-mx-auto">
            <!-- Grid -->
            <div class="tw-grid md:tw-grid-cols-5 tw-gap-10">
                <div class="md:tw-col-span-2">
                <div class="tw-max-w-xs tw-relative">
                    <h2 class="tw-text-2xl tw-font-bold md:tw-text-4xl md:tw-leading-tight">Preguntas<br>mas solicitadas</h2>
                    <p class="tw-mt-1 tw-hidden md:tw-block tw-text-gray-600">Respuestas a las preguntas más frecuentes de nuestros oficionados.</p>

                    <!-- SVG Element -->
                    <div class="tw-hidden md:tw-block tw-absolute tw-top-0 tw-end-0 -tw-translate-y-5 tw-translate-x-10">
                    <svg class="tw-w-16 tw-h-auto tw-text-red-400" width="121" height="135" viewBox="0 0 121 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 16.4754C11.7688 27.4499 21.2452 57.3224 5 89.0164" stroke="currentColor" stroke-width="10" stroke-linecap="round"/>
                        <path d="M33.6761 112.104C44.6984 98.1239 74.2618 57.6776 83.4821 5" stroke="currentColor" stroke-width="10" stroke-linecap="round"/>
                        <path d="M50.5525 130C68.2064 127.495 110.731 117.541 116 78.0874" stroke="currentColor" stroke-width="10" stroke-linecap="round"/>
                    </svg>
                    </div>
                    <!-- End SVG Element -->

                    <!-- SVG Element -->
                    <div class="tw-hidden md:tw-block tw-absolute tw-bottom-0 tw-start-0 tw-translate-y-24 -tw-translate-x-16">
                    <svg class="tw-w-40 tw-h-auto tw-text-blue-500" width="347" height="188" viewBox="0 0 347 188" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 82.4591C54.7956 92.8751 30.9771 162.782 68.2065 181.385C112.642 203.59 127.943 78.57 122.161 25.5053C120.504 2.2376 93.4028 -8.11128 89.7468 25.5053C85.8633 61.2125 130.186 199.678 180.982 146.248L214.898 107.02C224.322 95.4118 242.9 79.2851 258.6 107.02C274.299 134.754 299.315 125.589 309.861 117.539L343 93.4426" stroke="currentColor" stroke-width="7" stroke-linecap="round"/>
                    </svg>
                    </div>
                    <!-- End SVG Element -->
                </div>
                </div>
                <!-- End Col -->

                <div class="md:tw-col-span-3">
                <!-- Accordion -->
                <div>
                        <v-expansion-panels v-model="activePanel" class="tw-flex tw-flex-col tw-gap-5" variant="popout">
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Cómo puedo saber si los boletos que estoy comprando son legítimos?"
                            text="Para asegurarte de que los boletos son legítimos, compra siempre a través de nuestro sitio web oficial del equipo, ligas o plataformas de venta de boletos reconocidas como Ticketmaster, StubHub, o SeatGeek."
                            ></v-expansion-panel>
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Qué métodos de pago son aceptados al comprar boletos en línea?"
                            text="La mayoría de los sitios de venta de boletos aceptan varias formas de pago, incluyendo tarjetas de crédito y débito (Visa, MasterCard, American Express), PayPal y en algunos casos, métodos de pago móvil como Apple Pay o Google Wallet. Revisa las opciones de pago disponibles en el sitio donde estás comprando."
                            ></v-expansion-panel>
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Puedo elegir mis asientos al comprar boletos en línea?"
                            text="Sí, en la mayoría de los casos, puedes elegir tus asientos al comprar boletos en línea. Las plataformas de venta de boletos suelen mostrar un mapa del estadio donde puedes seleccionar la sección y los asientos específicos que prefieres. Algunas ofertas pueden ser de 'asiento mejor disponible', donde el sistema selecciona los mejores asientos disponibles para ti."
                            ></v-expansion-panel>
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Qué hago si no puedo asistir al juego después de comprar los boletos?"
                            text="Si no puedes asistir al juego, la mayoría de los sitios de venta de boletos ofrecen opciones para revender tus boletos. Puedes listarlos para la reventa directamente en la plataforma donde los compraste o en otras plataformas de reventa como StubHub o Vivid Seats. Asegúrate de revisar la política de reventa y los posibles cargos adicionales."
                            ></v-expansion-panel>
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Cómo recibiré mis boletos después de la compra?"
                            text="Dependiendo de la plataforma y el tipo de boleto, puedes recibir tus boletos de varias maneras: boletos electrónicos enviados a tu correo electrónico para imprimir en casa, boletos móviles que puedes mostrar en tu teléfono al ingresar, o boletos físicos que se envían a tu dirección postal. Verifica el método de entrega durante el proceso de compra."
                            ></v-expansion-panel>
                            <v-expansion-panel
                            rounded="lg"
                            title="¿Qué debo hacer si tengo problemas al intentar comprar boletos en línea?"
                            text="Si encuentras problemas durante el proceso de compra, primero revisa tu conexión a Internet y asegúrate de que tu información de pago esté correcta. Si el problema persiste, contacta al servicio de atención al cliente del sitio de venta de boletos. La mayoría de los sitios tienen opciones de chat en vivo, números de teléfono o correos electrónicos de soporte para ayudarte a resolver cualquier inconveniente."
                            ></v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                <!-- End Accordion -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
            </div>
            <!-- End FAQ -->

        </section>

        <section>

            <div class="lg:tw-py-28 tw-py-10">
                <div class="slider-container lg:tw-bg-slate-200 py-10">
                    <div class="slider-track">
                        <div class="slider-group">
                            <img class="tw-h-[600px]" src="../../../public/img/bannerimg3.svg">
                        </div>
                        <div class="slider-group">
                            <img class="tw-h-[600px]" src="../../../public/img/bannerimg3.svg">
                        </div>
                        <!-- Repite los elementos según sea necesario -->
                    </div>
                </div>
            </div>

        </section>

        <Footer />

    </main>
</template>

<style scoped>
.slider-up {
    animation: slide-up 20s linear infinite;
}

.slider-down {
    animation: slide-down 20s linear infinite;
}

@keyframes slide-up {
    0% {
        transform: translateY(50%);
    }
    100% {
        transform: translateY(-50%);
    }
}

@keyframes slide-down {
    0% {
        transform: translateY(-50%);
    }
    100% {
        transform: translateY(50%);
    }
}

.slider-container {
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    .slider-track {
        display: flex;
        width: calc(100% * 3);
        animation: slide 40s linear infinite;
    }

    .slider-group {
        flex: 0 0 auto;
        width: 100%;
        height: 600px;
    }

    .slider-group img {
        object-fit: cover ;
    }

    @keyframes slide {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    .v-expansion-panel-title {
        background: rgb(247, 249, 250) !important;
        padding: 23px 30px !important;
    }
</style>
