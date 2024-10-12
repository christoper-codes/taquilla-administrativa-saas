<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { drawerNavState } from '@/composables/drawersStates';

const fav = ref(true);
const menu = ref(false);
const message = ref(false);
const hints = ref(true);

const toggleFav = () => {
  fav.value = !fav.value;
};
</script>

<template>

   <div class="tw-bg-white/50 tw-fixed tw-w-full tw-z-50 tw-top-0 tw-left-0 tw-overflow-hidden tw-backdrop-filter tw-backdrop-blur-md">
    <div class="tw-w-full tw-h-6 tw-bg-gradient-to-r tw-from-tw-primary-600 tw-via-purple-400 tw-to-tw-secondary-400">
    </div>
    <div class="tw-max-w-7xl tw-mx-auto tw-py-3 lg:tw-py-5 tw-px-4 lg:tw-px-0">
            <div class="tw-flex tw-items-center tw-justify-between">
                <Link :href="route('welcome')" class="tw-flex tw-items-end tw-gap-1">
                    <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                    <div class="tw-font-semibold tw-text-tw-text-primary-600 tw-text-xs">
                        <span class="tw-block">Halcones de</span>
                        <span class="tw-block">xalapa</span>
                    </div>
                </Link>
                <div class="lg:tw-flex tw-items-center tw-gap-10 tw-hidden">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <Link
                            :href="route('welcome')"
                        >
                            <v-btn color="blue-grey" variant="text" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-lg">home</span>Inicio</v-btn>
                        </Link>
                        <div class="text-center">
                            <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            location="bottom start" origin="top center"
                            >
                            <template v-slot:activator="{ props }">
                                <v-btn v-bind="props" color="blue-grey" variant="text" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl">settings</span>Servicios</v-btn>
                            </template>

                            <v-card min-width="500" rounded="lg" class="!tw-bg-white/80 tw-backdrop-blur-sm">
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
                        <Link
                            :href="route('eventos.index')"
                        >
                            <v-btn color="blue-grey" variant="text" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl">note_stack</span>Eventos</v-btn>
                        </Link>
                    </div>
                    <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                        >
                        <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600" size="large" rounded="lg">Dashboard</v-btn>
                    </Link>
                    <div v-else class="tw-flex tw-items-center tw-gap-3">
                        <Link
                            :href="route('register')"
                        >
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white" size="large" rounded="lg">Registrarse</v-btn>
                        </Link>
                        <Link
                            :href="route('login')"
                        >
                        <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600" size="large" rounded="lg">Iniciar sesion</v-btn>
                        </Link>
                    </div>
                </div>
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

