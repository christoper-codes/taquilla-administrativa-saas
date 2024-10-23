<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { drawerNavState } from '@/composables/drawersStates';
import useTicketOfficeState from '@/composables/TicketOfficeState';
import ErrorSession from '@/Components/ErrorSession.vue';
import GuestNavLink from '@/Components/GuestNavLink.vue';

const { cashRegisterPresent } = useTicketOfficeState();

const fav = ref(true);
const menu = ref(false);
const message = ref(false);
const hints = ref(true);
const toggleFav = () => {
  fav.value = !fav.value;
};
</script>

<template>
    <div class="tw-bg-slate-950 tw-relative tw-overflow-hidden">
        <div class="tw-hidden lg:tw-block tw-absolute tw-left-1/2 tw-top-0 tw-h-[280px] tw-w-[500px] tw--translate-x-1/2 tw-rounded-full tw-bg-gradient-to-t tw-blur-[150px] tw-from-tw-primary-800 tw-to-tw-primary-600">
        </div>
        <div class="tw-w-full tw-max-w-7xl tw-mx-auto tw-py-3 lg:tw-py-5 tw-px-4 lg:tw-px-0 tw-flex tw-items-center tw-justify-between tw-relative">
            <div class="tw-flex tw-items-center tw-gap-3">
            <Link :href="route('welcome')" class="lg:tw-block tw-hidden">
                <ApplicationLogo class="tw-w-16 tw-h-auto tw-fill-current"/>
            </Link>
            <div class="tw-flex tw-gap-1 tw-flex-col">
                <h1 class="tw-bg-clip-text tw-bg-gradient-to-r tw-from-purple-600 tw-to-yellow-300 tw-text-transparent tw-text-lg md:tw-text-2xl tw-font-bold">Halcones de xalapa</h1>
                <p class="tw-text-gray-50 tw-text-xs tw-flex tw-gap-1"><span class="tw-hidden lg:tw-block">Club de baloncesto | </span> Temporada 2024 - 2025</p>
            </div>
        </div>
        <div v-if="cashRegisterPresent" class="tw-text-white tw-font-semibold  lg:tw-flex tw-items-center tw-gap-3">
            <p class="tw-text-3xl tw-font-bold">Caja <span class="tw-font-thin tw-text-yellow-400">{{ cashRegisterPresent }}</span> activa</p>
        </div>
        <div v-else class="tw-flex tw-items-center tw-gap-4 lg:tw-gap-10 tw-text-white">
            <div class="tw-items-center tw-gap-2 lg:tw-gap-4 tw-hidden md:tw-flex ">
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h8.615v-6.96h-2.338v-2.725h2.338v-2c0-2.325 1.42-3.592 3.5-3.592.699-.002 1.399.034 2.095.107v2.42h-1.435c-1.128 0-1.348.538-1.348 1.325v1.735h2.697l-.35 2.725h-2.348V21H20a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1z"></path></svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M20.947 8.305a6.53 6.53 0 0 0-.419-2.216 4.61 4.61 0 0 0-2.633-2.633 6.606 6.606 0 0 0-2.186-.42c-.962-.043-1.267-.055-3.709-.055s-2.755 0-3.71.055a6.606 6.606 0 0 0-2.185.42 4.607 4.607 0 0 0-2.633 2.633 6.554 6.554 0 0 0-.419 2.185c-.043.963-.056 1.268-.056 3.71s0 2.754.056 3.71c.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.043 1.268.056 3.71.056s2.755 0 3.71-.056a6.59 6.59 0 0 0 2.186-.419 4.615 4.615 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.187.043-.962.056-1.267.056-3.71-.002-2.442-.002-2.752-.058-3.709zm-8.953 8.297c-2.554 0-4.623-2.069-4.623-4.623s2.069-4.623 4.623-4.623a4.623 4.623 0 0 1 0 9.246zm4.807-8.339a1.077 1.077 0 0 1-1.078-1.078 1.077 1.077 0 1 1 2.155 0c0 .596-.482 1.078-1.077 1.078z"></path><circle cx="11.994" cy="11.979" r="3.003"></circle></svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M19.633 7.997c.013.175.013.349.013.523 0 5.325-4.053 11.461-11.46 11.461-2.282 0-4.402-.661-6.186-1.809.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721 4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973 4.02 4.02 0 0 1-1.771 2.22 8.073 8.073 0 0 0 2.319-.624 8.645 8.645 0 0 1-2.019 2.083z"></path></svg>
            </div>
            <div class="tw-h-5 tw-w-[1px] tw-bg-gray-600 tw-hidden md:tw-flex"></div>
           <div class="tw-items-center tw-gap-2 lg:tw-gap-4 tw-flex">
            <div v-if="route().current('events.show')" class="tw-bg-gradient-to-r tw-from-purple-400 tw-via-orange-400 tw-to-pink-500 tw-p-[2px]">
                <Link :href="route('dashboard')" >
                    <div class="tw-inline-flex tw-h-full tw-w-full tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-slate-900 tw-text-white tw-transition-background tw-px-3 tw-py-2 tw-text-sm tw-font-medium">
                        Mis boletos
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="tw-outline-none tw-transition-transform group-hover:tw-translate-x-0.5 [&>path]:tw-stroke-[2px]" width="16" height="16" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6"></path>
                        </svg>
                    </div>
                </Link>
            </div>
            <div v-else class="tw-flex tw-group tw-items-center tw-font-semibold tw-text-foreground tw-shadow-sm tw-gap-1.5 tw-relative tw-overflow-hidden tw-rounded-sm tw-p-[2px] focus-visible:tw-outline focus-visible:tw-outline-2 focus-visible:tw-outline-offset-2 focus-visible:tw-outline-primary">
                <span class="tw-absolute tw-inset-[-1000%] tw-animate-[spin_3s_linear_infinite] tw-bg-[conic-gradient(from_90deg_at_50%_50%,#F54180_0%,#338EF7_50%,#F54180_100%)]"></span>
                <Link :href="route('dashboard')" >
                    <div class="tw-inline-flex tw-h-full tw-w-full tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-slate-900 tw-text-white tw-transition-background tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-foreground tw-backdrop-blur-3xl">
                        Mis boletos
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="tw-outline-none tw-transition-transform group-hover:tw-translate-x-0.5 [&>path]:tw-stroke-[2px]" width="16" height="16" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6"></path>
                        </svg>
                    </div>
                </Link>
            </div>
           </div>
        </div>
        </div>
    </div>
   <div class="tw-bg-white/60 tw-sticky tw-w-full tw-z-50 tw-top-0 tw-left-0 tw-overflow-hidden tw-backdrop-filter tw-backdrop-blur-md tw-shadow-xl">
    <div class="tw-w-full tw-h-1 tw-bg-gradient-to-r tw-from-tw-primary-600 tw-via-purple-400 tw-to-tw-secondary-400">
    </div>
    <div class="tw-px-4 lg:tw-px-0 tw-py-2 lg:tw-py-0">
            <div class="tw-flex tw-items-center tw-justify-between tw-max-w-7xl tw-mx-auto ">
                <div class="lg:tw-flex tw-items-center tw-gap-10 tw-hidden tw-justify-between tw-w-full">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <GuestNavLink :href="route('welcome')" :active="route().current('welcome')">
                            <span class="material-symbols-outlined tw-text-lg">home</span>Inicio
                        </GuestNavLink>
                        <div class="text-center">
                            <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            location="bottom start" origin="top center"
                            >
                            <template v-slot:activator="{ props }">
                                <v-btn v-bind="props" variant="text" class="text-none !tw-h-[80px] !tw-w-28 !tw-text-gray-600 !tw-bg-transparent" rounded="0"><span class="material-symbols-outlined tw-text-lg">settings</span>Servicios</v-btn>
                            </template>

                            <v-card min-width="500" rounded="lg" class="!tw-bg-white tw-backdrop-blur-sm">
                                <v-list class="!tw-bg-transparent">
                                <v-list-item
                                    prepend-avatar="https://img.icons8.com/?size=100&id=m0c1h1XS3gNM&format=png&color=000000"
                                    title="Usuario de la app"
                                    subtitle="@usuario-fan"
                                >
                                    <template v-slot:append>
                                    <v-btn
                                        :class="fav ? 'text-red' : ''"
                                        icon="mdi-heart"
                                        variant="tonal"
                                        @click="fav = !fav"
                                    ></v-btn>
                                    </template>
                                </v-list-item>
                                </v-list>

                                <v-divider></v-divider>

                                <v-list class="!tw-bg-transparent">
                                <v-list-item>
                                    <v-switch
                                    v-model="message"
                                    color="purple"
                                    label="Ver boeltos solo en web"
                                    hide-details
                                    ></v-switch>
                                </v-list-item>

                                <v-list-item>
                                    <v-switch
                                    v-model="hints"
                                    color="purple"
                                    label="Ver boletos en la app y web"
                                    hide-details
                                    ></v-switch>
                                </v-list-item>
                                </v-list>

                                <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn
                                    color="red"
                                    variant="tonal"
                                    class="text-none" rounded="lg"
                                    @click="menu = false"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="primary"
                                    class="text-none" rounded="lg"
                                    variant="tonal"
                                    @click="menu = false"
                                >
                                    Save
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-menu>
                        </div>
                        <GuestNavLink :href="route('events.index')" :active="route().current('events.index') || route().current('events.show')">
                            <span class="material-symbols-outlined tw-text-lg">note_stack</span>Eventos
                        </GuestNavLink>
                        <GuestNavLink :href="route('blogs.show', 1)" :active="route().current('blogs.show')">
                            <span class="material-symbols-outlined tw-text-lg">bookmark</span>Blogs
                        </GuestNavLink>
                    </div>
                    <ErrorSession />
                    <div v-if="$page.props.auth.user" class="tw-flex tw-items-center tw-gap-3">
                        <Link
                            :href="route('dashboard')"
                            >
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl">Dashboard</v-btn>
                        </Link>
                        <Link
                            :href="route('ticket-offices.index')"
                            >
                            <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600 !tw-px-7" size="large" rounded="xl">Taquillas</v-btn>
                        </Link>
                    </div>
                    <div v-else class="tw-flex tw-items-center tw-gap-3">
                        <Link
                            :href="route('register')"
                        >
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl">Registrarse</v-btn>
                        </Link>
                        <Link
                            :href="route('login')"
                        >
                        <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600 !tw-px-7" size="large" rounded="xl">Iniciar sesion</v-btn>
                        </Link>
                    </div>
                </div>
                    <Link :href="route('welcome')" class="lg:tw-hidden tw-block">
                    <ApplicationLogo class="tw-w-12 tw-h-auto tw-fill-current"/>
                </Link>
                <div class="tw-flex lg:tw-hidden">
                    <div class="container">
                        <label @click.stop>
                        <input type="checkbox" @click="drawerNavState = !drawerNavState">
                        <div class="checkmark">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        </label>
                    </div>
                </div>
            </div>
       </div>
   </div>
</template>


<style>
.v-btn__prepend {
    margin-right: 3px;
}
.v-btn__content {
    gap: 5px;
}
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.container {
  display: block;
  position: relative;
  cursor: pointer;
  font-size: 20px;
  user-select: none;
}

.checkmark {
  position: relative;
  top: 0;
  left: -10px;
  height: 1.1em;
  width: 1.3em;
}

.checkmark span {
  width: 30px;
  height: 2.5px;
  background-color: #4b5563;
  position: absolute;
  transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
}

.checkmark span:nth-child(1) {
  top: 10%;
}

.checkmark span:nth-child(2) {
  top: 50%;
}

.checkmark span:nth-child(3) {
  top: 90%;
}

.container input:checked + .checkmark span:nth-child(1) {
  top: 50%;
  transform: translateY(-50%) rotate(45deg);
  -webkit-transform: translateY(-50%) rotate(45deg);
  -moz-transform: translateY(-50%) rotate(45deg);
  -ms-transform: translateY(-50%) rotate(45deg);
  -o-transform: translateY(-50%) rotate(45deg);
}

.container input:checked + .checkmark span:nth-child(2) {
  top: 50%;
  transform: translateY(-50%) rotate(-45deg);
  -webkit-transform: translateY(-50%) rotate(-45deg);
  -moz-transform: translateY(-50%) rotate(-45deg);
  -ms-transform: translateY(-50%) rotate(-45deg);
  -o-transform: translateY(-50%) rotate(-45deg);
}

.container input:checked + .checkmark span:nth-child(3) {
  transform: translateX(-50px);
  -webkit-transform: translateX(-50px);
  -moz-transform: translateX(-50px);
  -ms-transform: translateX(-50px);
  -o-transform: translateX(-50px);
  opacity: 0;
}
</style>

