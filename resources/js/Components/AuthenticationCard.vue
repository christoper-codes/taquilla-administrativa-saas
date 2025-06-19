<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
   <main class="tw-min-h-screen tw-w-full tw-flex tw-flex-col lg:tw-flex-row lg:tw-justify-between">
        <div class="tw-min-h-screen tw-w-full lg:tw-w-[45%] tw-bg-white tw-flex tw-items-center tw-justify-center lg:tw-py-20">
            <div class="tw-w-full tw-h-auto">
                <slot />
            </div>
        </div>

       <div data-aos="fade-left" data-aos-duration="1300" class="tw-bg-white tw-hidden tw-min-h-screen tw-w-[55%] tw-rounded-tl-[33px] tw-rounded-bl-[30px] tw-bg-center tw-bg-no-repeat lg:tw-flex tw-flex-col tw-items-center tw-justify-between tw-relative tw-overflow-hidden">
            <video autoplay loop muted playsinline
                class="tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover tw-z-0">
                <source type="video/mp4" src="/videos/auth-video.mp4">
            </video>
            <div class="tw-h-full tw-bg-black/60 tw-backdrop-blur-md tw-w-full tw-flex tw-items-center tw-justify-center" style="z-index: 2;">
               <h3 class="tw-mt-5 tw-inline-block tw-font-bebas tw-pr-1 tw-font-bold tw-text-7xl tw-bg-clip-text tw-bg-white tw-opacity-50 tw-text-transparent">Halcones de xalapa</h3>
            </div>
        </div>
    </main>
</template>

