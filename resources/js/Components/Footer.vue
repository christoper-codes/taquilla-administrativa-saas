<script setup>
const year = new Date().getFullYear()
</script>

<template>
    <footer class="w-full tw-bg-gradient-to-r tw-from-secondary tw-to-primary tw-p-2 tw-flex tw-items-center tw-justify-center tw-z-20 tw-relative">
        <div class="tw-rounded-3xl tw-bg-white tw-py-12 lg:tw-pt-20 tw-w-full tw-text-center tw-flex tw-items-center tw-justify-center">
            <div class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-5">
                <div class="tw-flex tw-items-center tw-justify-center tw-gap-3 tw-flex-col tw-w-full tw-z-10">
                    <div class="tw-flex tw-items-center  tw-relative">
                        <h2 class="tw-font-extrabold tw-font-bebas tw-text-xl lg:tw-text-4xl !tw-mt-1">Temporada 2025</h2>
                        <img class="tw-w-7 lg:tw-w-10 tw-h-auto tw-absolute -tw-top-2 lg:-tw-top-5 -tw-left-6 lg:-tw-left-7" src="/storage/public/hdx-logo.png" alt="hdx logo">
                    </div>
                    <div class="tw-text-center tw-text-xs lg:tw-text-base tw-w-full tw-text-neutral-500">
                        © {{ year }} Todos los derechos reservados. <a href="/politicas-de-privacidad" target="_blank"><span class="tw-underline">Políticas de privacidad</span></a>
                    </div>
                </div>
                <h2 class="tw-inline-block tw-font-bebas lg:tw-mt-5 tw-pr-1 tw-font-bold tw-text-3xl lg:tw-text-9xl tw-opacity-50 tw-bg-clip-text tw-bg-gradient-to-r tw-from-primary tw-to-secondary tw-text-transparent !tw-underline">Halcones de Xalapa</h2>
            </div>
        </div>
    </footer>
</template>

