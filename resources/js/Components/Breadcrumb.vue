<script setup>

</script>

<template>
    <section class="tw-h-[250px]">
        <div class="tw-rounded-none tw-h-[300px] tw-relative tw-bg-white">
            <img class="tw-w-[1300px] tw-h-auto tw-absolute tw-right-0 tw-top-0 lg:tw-mt-[-80px]" src="../../../public/img/hero.svg" alt="Webiste image">
            <div class="d-flex flex-column fill-height justify-center align-center text-white tw-relative tw-px-4 lg:tw-px-0">
                <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-flex-col tw-w-full tw-gap-10">
                    <div class="tw-flex tw-items-end tw-gap-0 lg:tw-gap-3">
                        <h2 class="tw-font-bold tw-text-3xl lg:tw-text-5xl tw-text-gray-600 tw-max-w-2xl">
                            <slot name="title" />
                            <span>
                                <svg class="tw-shrink-0 tw-ms-2 tw-size-10 tw-text-gray-500 tw-inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                            </span>
                        </h2>
                    </div>
                    <div class="tw-flex tw-items-center">
                        <ol class="tw-flex tw-items-center tw-whitespace-nowrap">
                            <li class="tw-inline-flex tw-items-center tw-py-1.5 tw-px-2">
                                <a class="tw-flex tw-items-center tw-text-sm tw-text-gray-600 hover:tw-text-purple-600" href="/">
                                    Comienzo
                                </a>
                                <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </li>
                            <li class="tw-inline-flex tw-items-center tw-text-sm">
                                <div class="[--placement:top-left] tw-relative tw-inline-flex">
                                <div class="tw-flex tw-items-center gap-2 tw-bg-gray-200 tw-py-2 tw-px-4 tw-rounded-full tw-cursor-pointer tw-text-gray-600">
                                    <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                    <p>Pagina actual</p>
                                </div>
                                <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 tw-w-48 tw-hidden tw-z-10 tw-transition-[margin,opacity] tw-opacity-0 tw-duration-300 tw-mb-2 tw-bg-white tw-shadow-md tw-rounded-lg tw-p-1 tw-space-y-0.5" role="menu">
                                    <a class="tw-flex tw-items-center tw-gap-x-3.5 tw-py-2 tw-px-3 tw-rounded-lg tw-text-sm tw-text-gray-800 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 disabled:tw-opacity-50 disabled:tw-pointer-events-none" href="#">
                                    Projects
                                    </a>
                                    <a class="tw-flex tw-items-center tw-gap-x-3.5 tw-py-2 tw-px-3 tw-rounded-lg tw-text-sm tw-text-gray-800 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 disabled:tw-opacity-50 disabled:tw-pointer-events-none" href="#">
                                    Preline
                                    </a>
                                </div>
                                </div>
                                <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </li>
                            <li class="tw-inline-flex tw-items-center tw-py-1.5 tw-px-2">
                                <a class="tw-flex tw-items-center tw-text-sm tw-text-gray-600 hover:tw-text-purple-600" href="/">
                                    Dashboard
                                </a>
                                <svg class="tw-shrink-0 tw-ms-2 tw-size-4 tw-text-gray-500 tw-hidden lg:tw-block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </li>
                        </ol>

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style  scoped>

</style>
