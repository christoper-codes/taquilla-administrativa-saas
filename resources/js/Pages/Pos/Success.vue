<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import confetti from 'canvas-confetti';

const count = 200;
const defaults = {
  origin: { y: 0.7 }
};

const fire = (particleRatio, opts) => {
  confetti({
    ...defaults,
    ...opts,
    particleCount: Math.floor(count * particleRatio)
  });
};

const shootConfetti = () => {
  fire(0.25, {
    spread: 26,
    startVelocity: 55,
  });
  fire(0.2, {
    spread: 60,
  });
  fire(0.35, {
    spread: 100,
    decay: 0.91,
    scalar: 0.8,
  });
  fire(0.1, {
    spread: 120,
    startVelocity: 25,
    decay: 0.92,
    scalar: 1.2,
  });
  fire(0.1, {
    spread: 120,
    startVelocity: 45,
  });
};


const shootConfettiTwice = () => {
  shootConfetti();
  setTimeout(shootConfetti, 1000);
};

onMounted(() => {
    shootConfettiTwice();

});



</script>

<template>
    <Head title="success" />
    <div>
        <!-- Hero -->
        <div class="tw-relative tw-overflow-hidden before:tw-absolute before:tw-top-0 before:tw-start-1/2 before:tw-bg-[url('https://preline.co/assets/svg/examples/squared-bg-element.svg')] before:tw-bg-no-repeat before:tw-bg-top before:tw-size-full before:-tw-z-[1] before:tw-transform before:-tw-translate-x-1/2">
            <div class="tw-max-w-[85rem] tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-pt-24 tw-pb-10">
                <!-- Announcement Banner -->
                <div class="tw-flex tw-justify-center">
                <a class="tw-inline-flex tw-items-center tw-gap-x-2 tw-bg-white tw-border tw-border-gray-200 tw-text-xs tw-text-gray-600 tw-p-2 tw-px-3 tw-rounded-full tw-transition hover:tw-border-gray-300 focus:tw-outline-none focus:tw-border-gray-300" href="#">
                    Seguir comprando entradas
                    <Link :href="route('eventos.index')">
                        <span class="tw-flex tw-items-center tw-gap-x-1">
                        <span class="tw-border-s tw-border-gray-200 tw-text-purple-600 tw-ps-2">Explorar</span>
                        <svg class="tw-shrink-0 tw-size-4 tw-text-purple-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </Link>
                </a>
                </div>
                <!-- End Announcement Banner -->

                <!-- Title -->
                <div class="tw-mt-5 tw-max-w-xl tw-text-center tw-mx-auto">
                <h1 class="tw-block tw-font-bold tw-text-gray-800 tw-text-4xl md:tw-text-5xl lg:tw-text-6xl">
                    ¡Boletos adquiridos con éxito!
                </h1>
                </div>
                <!-- End Title -->

                <div class="tw-mt-5 tw-max-w-3xl tw-text-center tw-mx-auto">
                <p class="tw-text-lg tw-text-gray-600"> ¡Gracias por su compra! Puede ver sus boletos adquiridos en la seccion 'dashboard'. </p>
                </div>

                <!-- Buttons -->
                <div class="tw-mt-8 tw-gap-3 tw-flex tw-justify-center">
                <Link :href="route('dashboard')">
                    <v-btn variant="elevated" class="text-none !tw-bg-gradient-to-br tw-from-tw-primary-600 tw-to-blue-400 !tw-text-white" size="large" rounded="xl" block=""><span class="material-symbols-outlined tw-text-xl">confirmation_number</span>Ver mis boletos</v-btn>
                </Link>
                </div>
                <!-- End Buttons -->
            </div>
        </div>
        <!-- End Hero -->
    </div>
</template>

<style scoped>

</style>
