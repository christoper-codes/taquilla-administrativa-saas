<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SaleTicket from '@/Components/SaleTicket.vue';
import { Head, usePage } from '@inertiajs/vue3';
import TicketCharts from '@/Components/TicketCharts.vue';
import confetti from 'canvas-confetti';
import { onMounted, ref, watch } from 'vue';

const page = usePage().props;
const tab = ref(null);

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

const isNewUser = true

const shootConfettiTwice = () => {
  shootConfetti();
  setTimeout(shootConfetti, 1000);
};

onMounted(() => {
  if (isNewUser) {
    shootConfettiTwice();
  }
});

/* watch(() => page.flash, (newFlash) => {
  if (newFlash === 'is_new_user') {
    shootConfettiTwice();
  }
}); */
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>

        <div class="tw-w-full tw-px-4 lg:tw-px-8 tw-bg-white tw-overflow-hidden tw-py-6 tw-shadow-sm tw-rounded-lg tw-flex tw-items-center tw-gap-2 tw-flex-col">
            <div class="tw-px-5 tw-py-2 tw-bg-green-100 tw-rounded-md tw-text-green-600 tw-text-sm"><span class="tw-font-bold">Bienvenido!!</span> Eres uno de los primeros usuarios en probar la nueva plataforma</div>
            <div class="tw-text-gray-900 tw-font-medium">Boletos actuales!!</div>
        </div>

        <div class="tw-mt-10 tw-gap-5 tw-w-full tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-between">
            <div class="tw-w-full lg:tw-w-[47%] tw-shadow-lg tw-bg-white tw-px-5 tw-py-7 tw-rounded-lg">
                <div class="tw-px-5 tw-py-2 tw-bg-gray-100 tw-rounded-md tw-text-gray-600 tw-text-sm"><span class="tw-font-bold">Seleciona </span> un partido para ver tus boletos</div>
                <div class="tw-mt-5">
                    <v-tabs
                        v-model="tab"
                        align-tabs="center"
                        color="deep-purple-accent-4"
                        >
                        <v-tab value="one">Halcones de xala vs buap</v-tab>
                        <v-tab value="two">Halcones de xala rojos</v-tab>
                        <v-tab value="three">Halcones de xala vs monterrey</v-tab>
                    </v-tabs>
                </div>
            </div>
            <div class="tw-w-full lg:tw-w-[50%] tw-bg-white tw-rounded-lg tw-p-5 tw-shadow-lg">
                <TicketCharts/>
            </div>
        </div>


        <div class="tw-mt-10 tw-bg-white">
            <v-tabs-window v-model="tab">
                <v-tabs-window-item value="one">
                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 lg:tw-overflow-y-auto">
                        <SaleTicket/>
                        <SaleTicket/>
                    </div>
                </v-tabs-window-item>

                <v-tabs-window-item value="two">
                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 lg:tw-overflow-y-auto">
                        <SaleTicket/>
                    </div>
                </v-tabs-window-item>

                <v-tabs-window-item value="three">
                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 lg:tw-overflow-y-auto">
                        <SaleTicket/>
                        <SaleTicket/>
                        <SaleTicket/>
                    </div>
                </v-tabs-window-item>
            </v-tabs-window>
        </div>

    </AppLayout>


    <!-- <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout> -->
</template>

<style scoped>

</style>
