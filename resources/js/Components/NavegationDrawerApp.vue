<script setup>
import { drawerNavState, draweAppNavState } from '@/composables/drawersStates';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import AppNavLink from './AppNavLink.vue';
import useUserPolicy from '@/composables/UserPolicy';


const fav = ref(true);
const menu = ref(false);
const menu1 = ref(false);
const menu2 = ref(false);
const message = ref(false);
const hints = ref(true);
const open = ref([]);
const page = usePage();
const { viewAdminTopics } = useUserPolicy();
const { viewVendorTopics } = useUserPolicy();

onMounted(() => {
    const superAdmin = ['/create-users', '/indicadores-generales', '/indicadores-evento', '/transito'];
    const admin = ['/series', '/eventos-gestion', '/instituciones', '/promociones', '/convenios'];
    if (superAdmin.some(route => page.url.includes(route))) {
        open.value = ['super_admin'];
    }
    if (admin.some(route => page.url.includes(route))) {
        open.value = ['admin'];
    }
});

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
        <div class="tw-bg-white tw-min-h-screen tw-relative tw-overflow-hidden tw-border-r-2">
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
                                    class="!tw-rounded-full !tw-size-40 bg-profile !tw-bg-slate-300 !tw-shadow-none"
                                    v-bind="props"
                                    @click="fav = !fav"
                                    >
                                    <div
                                        class="tw-size-36 tw-overflow-hidden tw-flex tw-items-center tw-justify-center bg-profile"
                                        v-if="user.global_images.length > 0"
                                        :style="{ backgroundImage: `url(/storage/${user.global_images[0].file_path})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
                                        >
                                    </div>
                                    <div
                                        v-else
                                        class="tw-size-36 tw-overflow-hidden tw-flex tw-items-center tw-justify-center bg-profile"
                                        :style="{ backgroundImage: `url('https://images.pexels.com/photos/2847648/pexels-photo-2847648.jpeg')`, backgroundSize: 'cover', backgroundPosition: 'center' }"
                                        >
                                    </div>
                                </v-btn>
                                <div class="tw-flex tw-flex-col tw-items-center tw-mt-4">
                                    <p class="tw-text-center tw-font-bebas tw-text-2xl tw-font-bold">{{ user.first_name + ' ' + user.last_name }}</p>
                                    <p class="tw-text-center tw-text-xs">@{{ user.username }}</p>
                                </div>
                            </template>

                            <v-card min-width="350" rounded="lg" class="!tw-bg-white tw-backdrop-blur-sm">
                                <v-list class="!tw-bg-transparent">
                                <v-list-item
                                    :prepend-avatar="`/storage/public/user-img.svg`"
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

                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <div class="tw-w-full tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-overflow-hidden tw-shadow-lg tw-relative tw-mb-3">
                                    <div class="tw-w-[55%] tw-p-3 tw-pr-0 tw-text-sm tw-font-semibold tw-text-gray-700">
                                        <p class="">{{ user.first_name + ' ' + user.last_name }}</p>
                                        <p class="tw-text-xs tw-font-normal tw-mb-1">@{{ user.username }}</p>
                                        <Link :href="route('logout')" method="post" as="button">
                                            <v-btn  color="red" variant="tonal" block class="text-none" rounded="lg">
                                                Cerrar sesión
                                        </v-btn>
                                        </Link>
                                    </div>
                                    <img class="tw-w-[35%] tw-absolute tw-top-0 -tw-right-5" src="https://modernize-nuxt3-main.netlify.app/images/backgrounds/unlimited-bg.png" alt="Webiste image">
                                </div>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                    </div>

                </div>
            </div>

            <div class="tw-flex tw-flex-col tw-items-center tw-justify-between tw-gap-10 tw-p-4">
                    <div class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-2xl tw-mb-3 tw-font-bebas">Dashboard</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-4 tw-w-full">
                            <div class="tw-w-full ">
                                <AppNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    <span class="material-symbols-outlined tw-text-xl">home</span>Mis boletos
                                </AppNavLink>
                            </div>
                            <div class="tw-w-full " v-if="viewVendorTopics(user.user_roles)">
                                <AppNavLink :href="route('ticket-offices.index')" :active="route().current('ticket-offices.index')">
                                    <span class="material-symbols-outlined tw-text-xl">confirmation_number</span>Taquillas
                                </AppNavLink>
                            </div>
                            <div class="tw-w-full ">
                                <AppNavLink :href="route('events.index')" :active="route().current('events.index')">
                                    <span class="material-symbols-outlined tw-text-xl">note_stack</span>Partidos
                                </AppNavLink>
                            </div>

                            <div class=" tw-w-full">
                                <AppNavLink :href="route('ticket-offices.share')" :active="route().current('ticket-offices.share')">
                                    <span class="material-symbols-outlined tw-text-xl">share</span>Compartir boletos
                                </AppNavLink>
                            </div>
                            <div class=" tw-w-full">
                                <AppNavLink :href="route('wallet-account.index')" :active="route().current('wallet-account.index')">
                                    <span class="material-symbols-outlined tw-text-xl">credit_card</span>Mis tarjetas
                                </AppNavLink>
                            </div>
                        </div>
                    </div>

                    <div v-if="viewAdminTopics(user.user_roles)" class="tw-flex tw-flex-col tw-w-full">
                        <h2 class="tw-font-semibold tw-text-2xl tw-mb-3 tw-font-bebas">Accesos administrativos</h2>
                        <div class="tw-flex tw-flex-col tw-items-center tw-gap-0 tw-w-full">

                            <v-card class="mx-auto !tw-bg-transparent !tw-shadow-none" width="100%">
                                <v-list v-model:opened="open">
                                    <v-list-group value="super_admin" >
                                        <template v-slot:activator="{ props }">
                                        <v-list-item
                                            v-bind="props"
                                            prepend-icon="mdi-cloud-key"
                                            title="Super administracion"
                                        ></v-list-item>
                                        </template>

                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('create.users')" :active="route().current('create.users')">
                                                <span class="material-symbols-outlined tw-text-lg">person</span>Usuarios
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('indicators.index')" :active="route().current('indicators.index') || route().current('indicators.show')">
                                                <span class="material-symbols-outlined tw-text-lg">monitoring</span>Indicadores
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('events.with.traffic')" :active="route().current('events.with.traffic')">
                                                <span class="material-symbols-outlined tw-text-lg">block</span>Tránsito
                                            </AppNavLink>
                                        </div>
                                    </v-list-group>
                                </v-list>
                            </v-card>
                            <v-card class="mx-auto !tw-bg-transparent !tw-shadow-none" width="100%">
                                <v-list v-model:opened="open">
                                    <v-list-group value="admin" >
                                        <template v-slot:activator="{ props }">
                                        <v-list-item
                                            v-bind="props"
                                            prepend-icon="mdi-cog-outline"
                                            title="Administracion"
                                        ></v-list-item>
                                        </template>

                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('series.index')" :active="route().current('series.index')">
                                                <span class="material-symbols-outlined tw-text-lg">note_stack</span>Series
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('event.management.indexManagement')" :active="route().current('event.management.indexManagement')">
                                                <span class="material-symbols-outlined tw-text-lg">confirmation_number</span>Eventos
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('institutions.index')" :active="route().current('institutions.index')">
                                                <span class="material-symbols-outlined tw-text-lg">home_work</span>Instituciones
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('promotions.index')" :active="route().current('promotions.index')">
                                                <span class="material-symbols-outlined tw-text-lg">Loyalty</span>Promociones
                                            </AppNavLink>
                                        </div>
                                        <div class="tw-mt-3 tw-w-full">
                                            <AppNavLink :href="route('agreements.index')" :active="route().current('agreements.index')">
                                                <span class="material-symbols-outlined tw-text-lg">Handshake</span>Convenios
                                            </AppNavLink>
                                        </div>
                                        <div class=" tw-w-full">
                                            <AppNavLink :href="route('wallet.index')" :active="route().current('wallet.index')">
                                                <span class="material-symbols-outlined tw-text-lg">account_balance_wallet</span>Monederos
                                            </AppNavLink>
                                        </div>
                                    </v-list-group>
                                </v-list>
                            </v-card>

                           <!--  <div class="text-center tw-w-full ">
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
                            </div> -->
                        </div>
                    </div>

                    <div class="tw-w-full tw-p-5 tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-overflow-hidden tw-shadow-xl tw-border tw-relative tw-bg-white/10">
                        <div class="tw-w-full">

                            <v-dialog max-width="500">
                                    <template v-slot:activator="{ props: activatorProps }">
                                        <v-btn  v-bind="activatorProps" color="red" variant="tonal" block class="text-none !tw-h-[50px] !tw-rounded-2xl">
                                            Cerrar sesión
                                        </v-btn>
                                    </template>
                                    <template v-slot:default="{ isActive }">
                                        <v-card title="¿Estás seguro de finalizar tu sesión?">

                                        <v-card-actions class="!tw-my-4 !tw-px-4">
                                            <v-spacer></v-spacer>
                                            <v-btn color="red" variant="tonal" rounded="lg" class="text-none !tw-h-[50px] !tw-px-4" text="Cancelar" @click="isActive.value = false"></v-btn>
                                            <Link :href="route('logout')" method="post" as="button">
                                                <v-btn variant="elevated" rounded="lg" class="text-none !tw-h-[50px] !tw-bg-purple-500 !tw-text-white !tw-px-4" @click="isActive.value = false">
                                                    <span class="material-symbols-outlined tw-text-xl !tw-w-1/2">person</span> Cerrar sesión
                                                </v-btn>
                                            </Link>
                                        </v-card-actions>

                                        </v-card>
                                    </template>
                            </v-dialog>
                        </div>
                    </div>
                </div>
        </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>


<style scoped>

.bg-profile{
    border-radius: 100px 55px 55px 99px/80px 82px 75px 79px !important;
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
        width: 290px !important;
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

