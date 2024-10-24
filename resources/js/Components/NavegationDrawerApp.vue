<script setup>
import { drawerNavState, draweAppNavState } from '@/composables/drawersStates';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppNavLink from './AppNavLink.vue';


const fav = ref(true);
const menu = ref(false);
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
  <div class="">
    <v-layout>
      <v-navigation-drawer v-model="draweAppNavState" temporary class="">
        <div class="!tw-text-gray-100 tw-bg-slate-950 tw-min-h-screen tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-left-1/2 tw-top-[80%] tw-h-[700px] tw-w-[500px] tw--translate-x-1/2 tw-rounded-full tw-bg-gradient-to-t tw-blur-[250px] tw-from-tw-primary-800 tw-to-tw-primary-600">
            </div>
            <div class="tw-w-full tw-relative">
                <div class="w-full tw-py-3 lg:tw-py-4 tw-px-4">

                    <div class="text-center profile-btn tw-cursor-pointer">
                        <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        location="bottom start" origin="top center"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    :class="fav ? 'text-purple' : '!tw-text-purple-500'"
                                    class="!tw-rounded-full !tw-size-40 bg-profile !tw-bg-slate-800"
                                    v-bind="props"
                                    variant="tonal"
                                    @click="fav = !fav"
                                    >
                                    <div
                                        class="tw-size-36 tw-overflow-hidden tw-flex tw-items-center tw-justify-center bg-profile"
                                        v-if="user.global_images.length > 0"
                                        :style="{ backgroundImage: `url(/storage/${user.global_images[0].file_path})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
                                        >
                                    </div>
                                    <div v-else>
                                        <img  class="tw-shrink-0 tw-size-40 tw-rounded-full" src="https://img.icons8.com/?size=100&id=m0c1h1XS3gNM&format=png&color=000000" alt="">
                                    </div>
                                </v-btn>
                            </template>

                            <v-card min-width="350" rounded="lg" class="!tw-bg-white tw-backdrop-blur-sm">
                                <v-list class="!tw-bg-transparent">
                                <v-list-item
                                    :prepend-avatar="`/storage/${user.global_images[0].file_path}`"
                                    :title="user.first_name"
                                    :subtitle="'@'+user.username"
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
                                <div class="tw-w-full tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-overflow-hidden tw-shadow-lg tw-relative tw-mb-3">
                                    <div class="tw-w-[55%] tw-p-3 tw-pr-0 tw-text-sm tw-font-semibold tw-text-gray-700">
                                        <p class="">{{ user.first_name + ' ' + user.last_name }}</p>
                                        <p class="tw-text-xs tw-font-normal tw-mb-1">@{{ user.username }}</p>
                                        <Link :href="route('logout')" method="post" as="button">
                                            <v-btn  color="red" variant="tonal" block class="text-none" rounded="lg">
                                                Cerrar sesion
                                        </v-btn>
                                        </Link>
                                    </div>
                                    <img class="tw-w-[35%] tw-absolute tw-top-0 -tw-right-5" src="https://modernize-nuxt3-main.netlify.app/images/backgrounds/unlimited-bg.png" alt="">
                                </div>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                    </div>

                </div>
            </div>

            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-10 tw-p-4">
                    <div class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-sm tw-mb-3">Dashboard</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-4 tw-w-full">
                            <div class="tw-w-full ">
                                <AppNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    <span class="material-symbols-outlined tw-text-lg">home</span>Mis boletos
                                </AppNavLink>
                            </div>
                            <div class=" tw-w-full">
                                <AppNavLink :href="route('ticket-offices.share')" :active="route().current('ticket-offices.share')">
                                    <span class="material-symbols-outlined tw-text-lg">share</span>Compartir
                                </AppNavLink>
                            </div>
                            <div class=" tw-w-full">
                                <AppNavLink :href="route('series.index')" :active="route().current('series.index')">
                                    <span class="material-symbols-outlined tw-text-lg">signpost</span>series
                                </AppNavLink>
                            </div>
                            <div class=" tw-w-full">
                                <AppNavLink :href="route('event.management.indexManagement')" :active="route().current('event.management.indexManagement')">
                                    <span class="material-symbols-outlined tw-text-lg">note_stack</span>Eventos
                                </AppNavLink>
                            </div>
                            <div class="tw-w-full ">
                                <v-menu
                                v-model="menu1"
                                :close-on-content-click="false"
                                location="bottom start" origin="top center"
                                >
                                <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" variant="text" class="text-none !tw-h-[40px] !tw-w-full !tw-text-gray-300 !tw-bg-transparent !tw-justify-start" rounded="xl" block><span class="material-symbols-outlined tw-text-xl">keyboard_arrow_down</span>Promociones</v-btn>
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
                        </div>
                    </div>

                    <div class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-sm tw-mb-3">Widgets</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-w-full">
                            <div class="tw-w-full">
                                <AppNavLink :href="route('welcome')" :active="route().current('welcome')">
                                    <span class="material-symbols-outlined tw-text-lg">folder</span>Historial
                                </AppNavLink>
                            </div>
                            <div class="text-center tw-w-full ">
                                <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                location="bottom start" origin="top center"
                                >
                                <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" variant="text" class="text-none !tw-h-[40px] !tw-w-full !tw-text-gray-300 !tw-bg-transparent !tw-justify-start" rounded="xl" block><span class="material-symbols-outlined tw-text-xl">keyboard_arrow_down</span>Servicios</v-btn>
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

                    <div class="tw-w-full tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-overflow-hidden tw-shadow-xl tw-relative tw-bg-white/10">
                        <div class="tw-w-[55%] tw-p-3 tw-pr-0 tw-text-sm tw-font-semibold tw-text-gray-200">
                            <p class="tw-mb-4">{{ user.first_name + ' ' + user.last_name }}</p>

                            <v-dialog max-width="500">
                                    <template v-slot:activator="{ props: activatorProps }">
                                        <v-btn v-bind="activatorProps" color="red" variant="tonal" block class="text-none" rounded="lg">
                                            Cerrar sesion
                                        </v-btn>
                                    </template>
                                    <template v-slot:default="{ isActive }">
                                        <v-card title="¿Estas seguro de finalizar tu sesion ?">
                                        <v-card-text>
                                            <p class="tw-opacity-50 tw-mt-3">Oprime 'cerrar sesion' para finalizar la autenticacion.</p>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="red" rounded="xl" variant="tonal" class="text-none !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                            <Link :href="route('logout')" method="post" as="button">
                                                <v-btn rounded="xl" variant="elevated" class="text-none !tw-bg-purple-500 !tw-text-white tw-mb-2 !tw-px-4" @click="isActive.value = false">
                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">person</span> Cerrar sesion
                                                </v-btn>
                                            </Link>
                                        </v-card-actions>

                                        </v-card>
                                    </template>
                            </v-dialog>
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
.bg-profile{
    border-radius: 100px 55px 55px 90px/80px 82px 75px 79px !important;
}
.v-btn__prepend {
    margin-right: 3px;
}
.v-btn__content {
    gap: 5px;
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
        width: 270px !important;
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

