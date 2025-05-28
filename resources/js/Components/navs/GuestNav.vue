<script setup >
import { Link, usePage } from '@inertiajs/vue3';
import SecondaryButton from '../buttons/SecondaryButton.vue';
import { menuStateSocialMedia, menuStateApp } from '@/composables/nav/menu-state.js'
import GuestNavSocial from './GuestNavSocial.vue';
</script>

<template>
    <div>
        <div class="lg:tw-hidden">
            <div class="tw-flex tw-items-center tw-w-full tw-pr-8 tw-justify-between tw-relative tw-z-10">
                <Link :href="route('welcome')">
                    <div class="tw-flex tw-items-center tw-relative">
                        <h2 class="tw-font-extrabold tw-font-bebas tw-text-2xl !tw-mt-1">HALCONES</h2>
                        <img class="tw-w-10 tw-h-auto tw-absolute -tw-top-5 -tw-left-3" src="/storage/public/hdx-logo.png" alt="hdx logo">
                    </div>
                </Link>
                <div @click="menuStateApp = !menuStateApp">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon" class="tw-size-8 tw-mb-1"><path fill-rule="evenodd" d="M3 9a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9Zm0 6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
        <div class="tw-w-full tw-hidden lg:tw-flex tw-items-center tw-justify-between !tw-mx-auto !tw-max-w-7xl tw-p-5 tw-z-20 tw-relative">
            <Link :href="route('welcome')">
                <div class="tw-flex tw-items-center  tw-relative">
                    <h2 class="tw-font-extrabold tw-font-bebas tw-text-4xl !tw-mt-1">HALCONES</h2>
                    <img class="tw-w-10 tw-h-auto tw-absolute -tw-top-5 -tw-left-7" src="/storage/public/hdx-logo.png" alt="hdx logo">
                </div>
            </Link>

            <div class="tw-flex tw-items-center tw-gap-5">
                <GuestNavSocial />
                <div @click="menuStateSocialMedia = !menuStateSocialMedia" class="tw-items-center tw-gap-1 tw-cursor-pointer tw-relative tw-inline-flex tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">
                    <span class="block">Redes sociales</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 tw-fill-current" viewBox="0 0 24 24"><path d="M16.293 9.293 12 13.586 7.707 9.293l-1.414 1.414L12 16.414l5.707-5.707z"></path></svg>
                </div>
                <div class="tw-inline-block">
                    <Link :href="route('register')" class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Registrarse</Link>
                </div>
                <div class="tw-inline-block">
                    <Link :href="route('login')" class="tw-relative tw-inline-block tw-transition-colors tw-duration-300 after:tw-content-[''] after:tw-absolute after:tw-left-0 after:tw-bottom-0 after:tw-h-[2px] after:tw-w-0 after:tw-bg-current after:tw-transition-all after:tw-duration-500 hover:after:tw-w-full">Iniciar sesión</Link>
                </div>
            </div>
            <Link :href="route('dashboard')" >
                <SecondaryButton>
                    Mis boletos
                </SecondaryButton>
            </Link>
        </div>
    </div>
</template>
