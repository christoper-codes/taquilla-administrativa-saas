<script setup>
import { drawerNavState } from '@/composables/drawersStates';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';


const fav = ref(true);
const menu = ref(false);
const message = ref(false);
const hints = ref(true);

const toggleFav = () => {
  fav.value = !fav.value;
};


</script>

<template>
  <div>
    <v-layout>
      <v-navigation-drawer v-model="drawerNavState" temporary class="!tw-bg-white">
        <div class="">
            <Link @click="drawerNavState = !drawerNavState" :href="route('welcome')" class="tw-flex tw-items-end tw-gap-1 tw-shadow-md tw-p-4">
                <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                <div class="tw-font-semibold tw-text-tw-text-primary-600 tw-text-xs">
                    <span class="tw-block">Halcones de</span>
                    <span class="tw-block">xalapa</span>
                </div>
            </Link>
            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-10 tw-p-4">
                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-5 tw-w-full">
                        <div @click="drawerNavState = !drawerNavState" class="left-zone tw-w-full">
                            <v-btn  href="#" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-lg">home</span>Inicio</v-btn>
                        </div>
                        <div class="text-center tw-w-full left-zone">
                            <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            location="bottom start" origin="top center"
                            >
                            <template v-slot:activator="{ props }">
                                <v-btn v-bind="props" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">settings</span>Servicios</v-btn>
                            </template>

                            <v-card min-width="300" rounded="lg" class="">
                                <v-list class="">
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

                                <v-list class="">
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
                        <div class="left-zone tw-w-full">
                            <v-btn  href="#" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">note_stack</span> blog</v-btn>
                        </div>
                    </div>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="tw-w-full"
                    >
                        <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600" block size="large" rounded="lg">Dashboard</v-btn>
                    </Link>
                    <div v-else class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-w-full">
                        <Link
                            @click="drawerNavState = !drawerNavState"
                            class="tw-w-full"
                            :href="route('register')"
                        >
                            <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white" block size="large" rounded="lg">Registrarse</v-btn>
                        </Link>
                        <Link
                            @click="drawerNavState = !drawerNavState"
                            class="tw-w-full"
                            :href="route('login')"
                        >
                        <v-btn variant="tonal" class="text-none !tw-bg-tw-primary-100 !tw-text-tw-primary-600" block size="large" rounded="lg">Iniciar sesion</v-btn>
                        </Link>
                    </div>
                </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>


<style scoped>
.left-zone .v-btn.v-btn--density-default {
    align-items: center !important;
    justify-content: start !important;
}
    .v-navigation-drawer__scrim {
        display: none !important;
    }

</style>

