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
const isMobile = ref(window.innerWidth < 1024);

</script>

<template>
   <NavegationDrawerApp v-bind:user="user" />
   <transition name="fade" mode="out-in">
        <div v-if="draweAppNavState && isMobile" @click="draweAppNavState = !draweAppNavState" class="tw-h-screen tw-w-full tw-bg-black/50 tw-fixed tw-z-50 tw-backdrop-blur-sm"></div>
    </transition>
   <div class="tw-bg-white lg:tw-bg-white/50 tw-fixed tw-w-full tw-z-40 tw-top-0 tw-left-0 tw-overflow-hidden tw-backdrop-filter tw-backdrop-blur-md tw-shadow-xl">
       <div class="tw-w-full tw-bg-transparent tw-py-5 lg:tw-py-5 tw-px-4 lg:tw-px-10 tw-flex tw-items-center tw-justify-between lg:tw-pl-[295px]">
            <div class="tw-flex tw-items-center tw-gap-3">
                <Link :href="route('welcome')" class="tw-flex tw-gap-1 tw-flex-col">
                    <h1 class="tw-bg-clip-text lg:tw-ml-9 tw-bg-gradient-to-r tw-from-primary tw-to-secondary tw-text-transparent tw-font-bebas tw-text-xl md:tw-text-4xl tw-font-bold">Halcones de xalapa</h1>
                </Link>
            </div>
            <div class="lg:tw-flex tw-items-center tw-gap-4 lg:tw-gap-10 tw-text-gray-500 tw-hidden">
                <Link :href="route('events.index')">
                    <v-btn variant="elevated" class="text-none !tw-text-white !tw-px-8 !tw-h-[60px] !tw-rounded-2xl !tw-bg-gradient-to-r !tw-from-primary !tw-to-cyan-500">
                        Comprar boletos
                    </v-btn>
                </Link>
            </div>
            <div class="lg:tw-hidden">
                 <svg @click="draweAppNavState = !draweAppNavState" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon" class="tw-size-8 tw-mb-1"><path fill-rule="evenodd" d="M3 9a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9Zm0 6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
   </div>

   <div class="lg:tw-ml-[270px] tw-mt-[65px] lg:tw-mt-[100px]">
        <main class="tw-px-5 lg:tw-px-5 tw-space-y-4 sm:tw-space-y-6 tw-min-h-screen tw-overflow-hidden tw-relative">
           <div class="tw-z-20 tw-relative !tw-m-0  tw-min-h-screen" data-aos="fade-right" data-aos-duration="1500">
            <div class="tw-absolute -tw-right-40 lg:-tw-right-96 -tw-top-52 lg:-tw-top-52 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[500px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
            </div>
            <div class="tw-absolute -tw-bottom-60 -tw-left-72 tw-h-[700px] tw-w-[1500px] tw-rounded-full tw-blur-[100px] tw-bg-white">
            </div>
                <div class="tw-z-20 tw-relative lg:!tw-ml-12">
                    <slot/>
                </div>
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

