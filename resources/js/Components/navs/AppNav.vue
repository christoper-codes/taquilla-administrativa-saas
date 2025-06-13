<script setup>
import { Link } from '@inertiajs/vue3';
import { menuStateApp } from '@/composables/nav/menu-state.js'
import useUserPolicy from '@/composables/UserPolicy';
import { ref } from 'vue';

const year = new Date().getFullYear()
const { viewVendorTopics } = useUserPolicy();
const isMember = ref(true)

</script>

<template>
  <div>
    <transition name="fade" mode="out-in">
      <div
        v-if="menuStateApp"
        @click="menuStateApp = !menuStateApp"
        class="tw-fixed tw-inset-0 tw-bg-black/50 tw-z-20 tw-backdrop-blur-sm"
      ></div>
    </transition>
    <transition name="slide" mode="out-in">
      <div
        v-if="menuStateApp"
        class="tw-bg-white tw-fixed tw-top-0 tw-left-0 tw-h-screen tw-w-[100%] md:tw-w-[50%] lg:tw-w-[43%] xl:tw-w-[35%] tw-z-30 tw-overflow-hidden"
      >
        <div
          class="tw-h-full tw-w-full tw-flex tw-flex-col tw-items-start tw-justify-between tw-py-7 tw-px-5 tw-relative tw-z-0"
        >
          <svg
            class="tw-absolute tw-inset-0 tw-size-full tw-stroke-black/10 tw-[mask-image:radial-gradient(100%_50%_at_top_right,white,transparent)]"
            aria-hidden="true"
          >
            <defs>
              <pattern
                id="about-svg"
                width="80"
                height="80"
                x="50%"
                y="-1"
                patternUnits="userSpaceOnUse"
              >
                <path d="M.5 200V.5H200" fill="none" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#about-svg)" />
          </svg>
            <div class="tw-absolute -tw-bottom-60 tw-h-[680px] tw-w-[500px] tw-rounded-full tw-blur-[150px] tw-bg-white">
            </div>
          <div
            @click="menuStateApp = !menuStateApp"
            class="tw-p-1 tw-border-2 tw-border-black/50 tw-rounded tw-cursor-pointer tw-z-10"
          >
            <svg
              class="tw-w-8 tw-h-auto tw-stroke-black"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </div>
          <div
            class="tw-w-full tw-text-center tw-inline-flex tw-flex-col tw-gap-9 2xl:tw-gap-9 tw-text-4xl tw-z-10 tw-text-neutral-800"
          >
            <Link
                :href="route('events.index')"
                class="tw-w-full"
                @click="menuStateApp = !menuStateApp"
            >
                <div class="tw-inline-block">
                    <div class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Eventos</div>
                </div>
            </Link>
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="tw-w-full"
                @click="menuStateApp = !menuStateApp"
            >
                <div class="tw-inline-block">
                    <div class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Mis boletos</div>
                </div>
            </Link>
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="tw-w-full"
                @click="menuStateApp = !menuStateApp"
            >
                <div class="tw-inline-block">
                    <div class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Dashboard</div>
                </div>
            </Link>
            <Link
                v-if="!$page.props.auth.user"
                :href="route('login')"
                class="tw-w-full"
                @click="menuStateApp = !menuStateApp"
            >
            <div class="tw-inline-block">
                <div class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Iniciar sesión</div>
            </div>
            </Link>
            <Link
                v-if="!$page.props.auth.user"
                :href="route('register')"
                class="tw-w-full"
                @click="menuStateApp = !menuStateApp"
            >
                <div class="tw-inline-block">
                    <div class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Registrarte</div>
                </div>
            </Link>
          </div>
          <div
            class="tw-flex tw-items-center tw-justify-center tw-gap-3 tw-flex-col tw-w-full tw-z-10"
          >
            <div class="tw-flex tw-items-center  tw-relative tw-text-neutral-800">
                <h2 class="tw-font-extrabold tw-font-bebas tw-text-4xl !tw-mt-1">HALCONES</h2>
                <img class="tw-w-10 tw-h-auto tw-absolute -tw-top-5 -tw-left-7" src="/storage/public/hdx-logo.png" alt="hdx logo">
            </div>
            <div class="tw-text-center tw-w-full tw-text-xs 2xl:tw-text-sm tw-text-neutral-400">
              © {{ year }} Todos los derechos reservados.
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
