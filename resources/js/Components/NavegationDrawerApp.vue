<script setup>
import { drawerNavState, draweAppNavState } from '@/composables/drawersStates';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';


const fav = ref(true);
const menu1 = ref(false);
const menu2 = ref(false);
const message = ref(false);
const hints = ref(true);

const toggleFav = () => {
  fav.value = !fav.value;
};

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

</script>

<template>
  <div>
    <v-layout>
      <v-navigation-drawer v-model="draweAppNavState" temporary class="">
        <div class="!tw-text-gray-600">

            <div class="tw-w-full">
                <div class="tw-w-full tw-h-6 tw-bg-[#984CDC]">
                </div>
                <div class="w-full tw-py-3 lg:tw-py-4 tw-px-4">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <Link :href="route('welcome')" class="tw-flex tw-items-end tw-gap-1">
                                <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                                <div class="tw-font-semibold tw-text-tw-text-primary-600 tw-text-xs">
                                    <span class="tw-block">Halcones de</span>
                                    <span class="tw-block">xalapa</span>
                                </div>
                            </Link>
                        </div>
                </div>
            </div>

            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-10 tw-p-4">
                    <div class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-sm tw-mb-3">Dashboard</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-5 tw-w-full">
                            <div class="left-zone tw-w-full">
                                <v-btn  href="#" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">home</span> Mis boletos</v-btn>
                            </div>
                            <div class="text-center tw-w-full left-zone">
                                <v-menu
                                v-model="menu1"
                                :close-on-content-click="false"
                                location="bottom start" origin="top center"
                                >
                                <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">keyboard_arrow_down</span>Promociones</v-btn>
                                </template>

                                <v-card min-width="300" rounded="lg" class="">
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="red"
                                            variant="tonal"
                                            class="text-none" rounded="lg"
                                            @click="menu1 = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            color="primary"
                                            class="text-none" rounded="lg"
                                            variant="tonal"
                                            @click="menu1 = false"
                                        >
                                            Save
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                                </v-menu>
                            </div>
                            <div class="left-zone tw-w-full">
                                <v-btn  href="#" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">note_stack</span> Cuentas</v-btn>
                            </div>
                        </div>
                    </div>

                    <div class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-sm tw-mb-3">Widgets</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-5 tw-w-full">
                            <div class="left-zone tw-w-full">
                                <v-btn  href="#" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">folder</span> Historial</v-btn>
                            </div>
                            <div class="text-center tw-w-full left-zone">
                                <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                location="bottom start" origin="top center"
                                >
                                <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" color="blue-grey" variant="text" class="text-none" rounded="lg" block><span class="material-symbols-outlined tw-text-xl">keyboard_arrow_down</span>Servicios</v-btn>
                                </template>

                                <v-card min-width="300" rounded="lg" class="">

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="red"
                                            variant="tonal"
                                            class="text-none" rounded="lg"
                                            @click="menu2 = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            color="primary"
                                            class="text-none" rounded="lg"
                                            variant="tonal"
                                            @click="menu2 = false"
                                        >
                                            Save
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                                </v-menu>
                            </div>
                        </div>
                    </div>

                    <div class="tw-w-full tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-overflow-hidden tw-shadow-xl tw-relative">
                        <div class="tw-w-[55%] tw-p-3 tw-pr-0 tw-text-sm tw-font-semibold tw-text-gray-700">
                            <p class="">{{ user.first_name + ' ' + user.last_name }}</p>
                            <p class="tw-text-xs tw-font-normal tw-mb-1">@{{ user.username }}</p>
                            <Link :href="route('logout')" method="post" as="button">
                                <v-btn  color="red" variant="tonal" block class="text-none" rounded="lg">
                                    Cerrar sesion
                            </v-btn>
                            </Link>
                        </div>
                        <img class="tw-w-[60%] tw-absolute tw-top-0 -tw-right-5" src="https://modernize-nuxt3-main.netlify.app/images/backgrounds/unlimited-bg.png" alt="">
                    </div>
                </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>


<style scoped>
.v-btn__prepend {
    margin-right: 3px;
}
.v-btn__content {
    gap: 5px;
}

.left-zone .v-btn.v-btn--density-default {
    align-items: center !important;
    justify-content: start !important;
}
.v-navigation-drawer__scrim {
    display: none !important;
}
.v-navigation-drawer--temporary.v-navigation-drawer--active {
    width: 75% !important;
    border: none !important;
}

@media (min-width: 768px) {
    .v-navigation-drawer--temporary.v-navigation-drawer--active {
        width: 260px !important;
        box-shadow: none !important;
    }
}
@keyframes spin-gradient {
  0% {
    background: #f8b3fa;
  }
  25% {
    background: #f4fcb2;
  }
  50% {
    background: #de89ff;
  }
  75% {
    background: #a567f5;
  }
  100% {
    background: #ffed88;
  }
}

</style>

