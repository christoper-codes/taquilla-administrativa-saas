<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { drawerNavState, draweAppNavState } from '@/composables/drawersStates';
import NavegationDrawerApp from '@/Components/NavegationDrawerApp.vue';

const fav = ref(true);
const menu = ref(false);
if (window.innerWidth < 1024) {
  menu.value = false;
} else {
  menu.value = true;
}

const message = ref(false);
const hints = ref(true);

const toggleFav = () => {
  fav.value = !fav.value;
};

const user = usePage().props.auth.user;


</script>

<template>
    <NavegationDrawerApp v-bind:user="user" />

   <div class="tw-bg-white/50 lg:tw-bg-white/50 tw-fixed tw-w-full tw-z-50 tw-top-0 tw-left-0 tw-overflow-hidden tw-backdrop-filter tw-backdrop-blur-md">
    <div class="tw-w-full tw-h-6 tw-bg-gradient-to-r tw-from-purple-700 tw-via-purple-300 tw-to-tw-secondary-300">
    </div>
    <div class="w-full tw-py-3 lg:tw-py-3 tw-px-4 lg:tw-px-5 lg:tw-pl-[260px]">
            <div class="tw-flex tw-items-center tw-justify-between ">
                <div class="tw-hidden lg:tw-flex tw-items-center tw-gap-5">
                    <div class="tw-flex tw-pl-5">
                        <div class="container">
                            <label @click.stop class="tw-cursor-pointer">
                                <input type="checkbox" @click="draweAppNavState = !draweAppNavState">
                                <div class="checkmark">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="tw-w-80 tw-flex tw-items-center tw-gap-5 tw-font-medium tw-text-gray-500">
                        <div>Calendario</div>
                        <div>Temporada</div>
                    </div>
                </div>
                <div class="lg:tw-hidden">
                    <Link :href="route('welcome')" class="tw-flex tw-items-end tw-gap-1">
                        <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                        <div class="tw-font-semibold tw-text-tw-text-primary-600 tw-text-xs">
                            <span class="tw-block">Halcones de</span>
                            <span class="tw-block">xalapa</span>
                        </div>
                    </Link>
                </div>
                <div class="tw-flex tw-items-center tw-gap-10">
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <div class="tw-hidden lg:tw-flex tw-items-center tw-gap-4">
                            <v-btn  href="#" color="primary" variant="tonal" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl">confirmation_number</span></v-btn>
                            <v-btn  href="#" color="orange" variant="tonal" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl">notifications_active</span></v-btn>
                            <v-btn  href="#" color="green" variant="tonal" class="text-none" rounded="lg"><span class="material-symbols-outlined tw-text-xl">shopping_bag</span></v-btn>
                        </div>

                        <div class="text-center profile-btn tw-cursor-pointer">
                            <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            location="bottom start" origin="top center"
                            >
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    :class="fav ? 'text-purple' : ''"
                                    class="!tw-rounded-full !tw-size-[45px]"
                                    v-bind="props"
                                    variant="tonal"
                                    @click="fav = !fav"
                                    >
                                    <div v-if="user.global_images.length > 0">
                                        <img class="tw-shrink-0 tw-size-[38px] tw-rounded-full" :src="`/storage/${user.global_images[0].file_path}`" alt="">
                                    </div>
                                    <div v-else>
                                        <img  class="tw-shrink-0 tw-size-[38px] tw-rounded-full" src="https://img.icons8.com/?size=100&id=m0c1h1XS3gNM&format=png&color=000000" alt="">
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
                        <div class="lg:tw-hidden">
                            <div class="container">
                                <label @click.stop class="tw-cursor-pointer">
                                    <input type="checkbox" @click="draweAppNavState = !draweAppNavState">
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
       </div>
   </div>

   <div class="lg:tw-ml-[260px]  tw-mt-24 lg:tw-mt-[95px] lg:tw-rounded-lg">
        <main class="tw-p-0 tw-space-y-4 sm:tw-space-y-6 tw-bg-white tw-min-h-screen lg:tw-rounded-lg tw-overflow-hidden tw-relative">
        <div class="tw-hidden lg:tw-block  tw-absolute tw-left-0 tw-top-0 tw-ml-[-30rem] tw-h-[25rem] tw-w-[81.25rem] dark:[mask-image:linear-gradient(white,transparent)] tw-z-10">
            <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-r tw-from-cyan-400 tw-to-pink-500 tw-opacity-40 [mask-image:radial-gradient(farthest-side_at_top,white,transparent)] dark:tw-from-[#36b49f]/30 dark:tw-to-[#DBFF75]/30 dark:tw-opacity-100">
                <svg aria-hidden="true" class="tw-absolute tw-inset-x-0 tw-inset-y-[-50%] tw-h-[200%] tw-w-full tw-skew-y-[-18deg] tw-fill-black/40 tw-stroke-black/50 tw-mix-blend-overlay dark:tw-fill-white/2.5 dark:tw-stroke-white/5">
                    <defs>
                        <pattern id=":S1:" width="72" height="56" patternUnits="userSpaceOnUse" x="-12" y="4">
                            <path d="M.5 56V.5H72" fill="none"></path>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" stroke-width="0" fill="url(#:S1:)"></rect>
                    <svg x="-12" y="4" class="tw-overflow-visible">
                        <rect stroke-width="0" width="73" height="57" x="288" y="168"></rect>
                        <rect stroke-width="0" width="73" height="57" x="144" y="56"></rect>
                        <rect stroke-width="0" width="73" height="57" x="504" y="168"></rect>
                        <rect stroke-width="0" width="73" height="57" x="720" y="336"></rect>
                    </svg>
                </svg>
            </div>
        </div>
        <img alt="" loading="lazy" height="946" decoding="async" data-nimg="1" class="tw-absolute tw-left-0 tw-w-full tw-top-0 -tw-translate-y-1/4" style="color:transparent" src="https://salient.tailwindui.com/_next/static/media/background-faqs.55d2e36a.jpg">

        <div class="tw-z-20 inner-shadow tw-relative tw-bg-white tw-bg-opacity-30 tw-backdrop-blur-md !tw-m-0 lg:tw-rounded-lg tw-px-4  tw-pt-10 lg:tw-p-10 tw-min-h-screen">
            <slot/>
        </div>
    </main>
   </div>

</template>


<style >
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

.profile-btn .v-btn--size-default {
    padding: 0px !important;
    min-width: 0px !important;
}

/* CSS Inner Shadow Code */
.inner-shadow {
box-shadow: 36px -1px 26px -35px rgba(189, 189, 189, 0.67) inset;
-webkit-box-shadow: 36px -1px 26px -35px rgba(189, 189, 189, 0.67) inset;
-moz-box-shadow: 36px -1px 26px -35px rgba(189, 189, 189, 0.67) inset;
}
</style>

