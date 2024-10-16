<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { drawerNavState, draweAppNavState } from '@/composables/drawersStates';
import NavegationDrawerApp from '@/Components/NavegationDrawerApp.vue';

const fav = ref(true);
const menu = ref(false);
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
<!--         <div class="w-full tw-py-3 lg:tw-py-3 tw-px-4 lg:tw-px-5 lg:tw-pl-[260px]">
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
       </div> -->


       <div class="tw-w-full tw-bg-transparent tw-py-3 lg:tw-py-5 tw-px-4 lg:tw-px-5 tw-flex tw-items-center tw-justify-between lg:tw-pl-[295px]">
            <div class="tw-flex tw-items-center tw-gap-3">
                <Link :href="route('welcome')" class="lg:tw-block tw-hidden">
                    <ApplicationLogo class="tw-w-16 tw-h-auto tw-fill-current"/>
                </Link>
                <Link :href="route('welcome')" class="tw-flex tw-gap-1 tw-flex-col">
                    <h1 class="tw-bg-clip-text tw-bg-gradient-to-r tw-from-purple-600 tw-to-yellow-300 tw-text-transparent tw-text-lg md:tw-text-2xl tw-font-bold">Halcones de xalapa</h1>
                    <p class="tw-text-gray-500 tw-text-xs">Club de baloncesto | Temporada 2024 - 2025</p>
                </Link>
            </div>
            <div class="lg:tw-flex tw-items-center tw-gap-4 lg:tw-gap-10 tw-text-gray-500 tw-hidden">
                <div class="tw-flex tw-items-center tw-gap-2 lg:tw-gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h8.615v-6.96h-2.338v-2.725h2.338v-2c0-2.325 1.42-3.592 3.5-3.592.699-.002 1.399.034 2.095.107v2.42h-1.435c-1.128 0-1.348.538-1.348 1.325v1.735h2.697l-.35 2.725h-2.348V21H20a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1z"></path></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M20.947 8.305a6.53 6.53 0 0 0-.419-2.216 4.61 4.61 0 0 0-2.633-2.633 6.606 6.606 0 0 0-2.186-.42c-.962-.043-1.267-.055-3.709-.055s-2.755 0-3.71.055a6.606 6.606 0 0 0-2.185.42 4.607 4.607 0 0 0-2.633 2.633 6.554 6.554 0 0 0-.419 2.185c-.043.963-.056 1.268-.056 3.71s0 2.754.056 3.71c.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.043 1.268.056 3.71.056s2.755 0 3.71-.056a6.59 6.59 0 0 0 2.186-.419 4.615 4.615 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.187.043-.962.056-1.267.056-3.71-.002-2.442-.002-2.752-.058-3.709zm-8.953 8.297c-2.554 0-4.623-2.069-4.623-4.623s2.069-4.623 4.623-4.623a4.623 4.623 0 0 1 0 9.246zm4.807-8.339a1.077 1.077 0 0 1-1.078-1.078 1.077 1.077 0 1 1 2.155 0c0 .596-.482 1.078-1.077 1.078z"></path><circle cx="11.994" cy="11.979" r="3.003"></circle></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M19.633 7.997c.013.175.013.349.013.523 0 5.325-4.053 11.461-11.46 11.461-2.282 0-4.402-.661-6.186-1.809.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721 4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973 4.02 4.02 0 0 1-1.771 2.22 8.073 8.073 0 0 0 2.319-.624 8.645 8.645 0 0 1-2.019 2.083z"></path></svg>
                </div>
                <div class="tw-h-5 tw-w-[1px] tw-bg-gray-600 tw-hidden md:tw-flex"></div>
                <div class="tw-items-center tw-gap-2 lg:tw-gap-4 tw-hidden md:tw-flex ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="m12.954 11.616 2.957-2.957L6.36 3.291c-.633-.342-1.226-.39-1.746-.016l8.34 8.341zm3.461 3.462 3.074-1.729c.6-.336.929-.812.929-1.34 0-.527-.329-1.004-.928-1.34l-2.783-1.563-3.133 3.132 2.841 2.84zM4.1 4.002c-.064.197-.1.417-.1.658v14.705c0 .381.084.709.236.97l8.097-8.098L4.1 4.002zm8.854 8.855L4.902 20.91c.154.059.32.09.495.09.312 0 .637-.092.968-.276l9.255-5.197-2.666-2.67z"></path></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-size-5 lg:tw-size-6" viewBox="0 0 24 24"><path d="M19.665 16.811a10.316 10.316 0 0 1-1.021 1.837c-.537.767-.978 1.297-1.316 1.592-.525.482-1.089.73-1.692.744-.432 0-.954-.123-1.562-.373-.61-.249-1.17-.371-1.683-.371-.537 0-1.113.122-1.73.371-.616.25-1.114.381-1.495.393-.577.025-1.154-.229-1.729-.764-.367-.32-.826-.87-1.377-1.648-.59-.829-1.075-1.794-1.455-2.891-.407-1.187-.611-2.335-.611-3.447 0-1.273.275-2.372.826-3.292a4.857 4.857 0 0 1 1.73-1.751 4.65 4.65 0 0 1 2.34-.662c.46 0 1.063.142 1.81.422s1.227.422 1.436.422c.158 0 .689-.167 1.593-.498.853-.307 1.573-.434 2.163-.384 1.6.129 2.801.759 3.6 1.895-1.43.867-2.137 2.08-2.123 3.637.012 1.213.453 2.222 1.317 3.023a4.33 4.33 0 0 0 1.315.863c-.106.307-.218.6-.336.882zM15.998 2.38c0 .95-.348 1.838-1.039 2.659-.836.976-1.846 1.541-2.941 1.452a2.955 2.955 0 0 1-.021-.36c0-.913.396-1.889 1.103-2.688.352-.404.8-.741 1.343-1.009.542-.264 1.054-.41 1.536-.435.013.128.019.255.019.381z"></path></svg>
                </div>
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

   <div class="lg:tw-ml-[270px]  tw-mt-[70px] lg:tw-mt-[95px] lg:tw-rounded-lg">
        <main class="tw-p-0 tw-space-y-4 sm:tw-space-y-6 tw-bg-white tw-min-h-screen lg:tw-rounded-lg tw-overflow-hidden tw-relative">
           <div class="tw-z-20 inner-shadow tw-relative tw-bg-white tw-bg-opacity-30 tw-backdrop-blur-md !tw-m-0 lg:tw-rounded-lg  tw-min-h-screen">
            <slot/>
            </div>
        </main>
        <div class="tw-p-10 tw-bg-slate-200 tw-text-center">
            <h3 class="tw-font-bold">Halocones<span class="tw-font-light">Xalapa | 2024</span></h3>
        </div>
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

