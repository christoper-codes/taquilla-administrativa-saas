<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import confetti from 'canvas-confetti';
import SuccessSession from '@/Components/SuccessSession.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';

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
    <SuccessSession />

    <div>
        <!-- Hero -->
        <div class="tw-relative tw-h-screen tw-overflow-hidden tw-flex tw-items-center tw-flex-col tw-justify-center tw-gap-10">
            <div class="tw-absolute -tw-right-40 lg:-tw-right-96 -tw-top-52 lg:-tw-top-52 tw-h-[480px] tw-w-[300px] lg:tw-h-[680px] lg:tw-w-[500px] tw-rounded-full tw-blur-[120px] lg:tw-blur-[220px] tw-bg-primary">
            </div>
            <div class="tw-text-center tw-z-20 tw-relative">
                <h1 class="tw-font-bold tw-text-4xl md:tw-text-5xl lg:tw-text-7xl tw-font-bebas">
                    ¡Boletos adquiridos con éxito!
                </h1>
                <p>¡Gracias por su compra! Puede ver sus boletos adquiridos en la seccion 'dashboard'.</p>
            </div>
            <Link :href="route('dashboard')">
                <PrimaryButton heightbtn="!tw-h-[70px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-14">
                    <span class="material-symbols-outlined tw-text-2xl tw-mr-2">confirmation_number</span>Ver mis boletos
                </PrimaryButton>
            </Link>
        </div>
    </div>
</template>

<style scoped>

</style>
