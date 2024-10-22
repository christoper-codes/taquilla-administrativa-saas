<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SaleTicket from '@/Components/SaleTicket.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import TicketCharts from '@/Components/TicketCharts.vue';
import confetti from 'canvas-confetti';
import { onMounted, ref, watch } from 'vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import BreadcrumbApp from '@/Components/BreadcrumbApp.vue';

const page = usePage().props;

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

const isNewUser = false;

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

const props = defineProps({
    'events_with_tickets': {
        type: Object,
        required: true,
    },
})
const eventsWithTickets = ref(Object.values(props.events_with_tickets));
const tab = ref('tab-0');
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <SuccessSession />

        <BreadcrumbApp>
                <template #title>
                    <span>Mis boletos</span>
                </template>
        </BreadcrumbApp>

        <div class="tw-px-4 tw-py-10 lg:tw-p-10">

            <div class="tw-flex tw-flex-wrap lg:tw-flex-row lg:tw-items-center tw-gap-2 lg:tw-gap-5">
                <div class="tw-px-4 lg:tw-px-7 tw-py-2 tw-bg-gray-200 tw-text-xs lg:tw-text-base tw-rounded-full">
                    <span>!Bienvenido a la nueva plataforma!</span>
                </div>
                <div class="tw-px-4 lg:tw-px-7 tw-py-2 tw-bg-gradient-to-tr tw-from-tw-primary-500 tw-to-pink-400 tw-text-white tw-text-xs lg:tw-text-base tw-rounded-full">
                    <span>Mis boletos</span>
                </div>
                <div class="tw-px-4 lg:tw-px-7 tw-py-2 tw-bg-gray-200 tw-text-xs lg:tw-text-base tw-rounded-full">
                    <span>Valipor por un partido</span>
                </div>
                <div class="tw-px-4 lg:tw-px-7 tw-py-2 tw-bg-gray-200 tw-text-xs lg:tw-text-base tw-rounded-full">
                    <span>Halcones de xalapa</span>
                </div>
            </div>

            <div class="tw-mt-10 tw-gap-5 tw-w-full tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-between">
                <div class="tw-w-full tw-shadow-lg tw-bg-gray-200 tw-px-5 tw-py-7 tw-overflow-x-scroll tw-rounded-2xl tw-border tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center">
                    <span class="tw-text-sm tw-font-bold tw-text-gray-500 tw-text-center tw-inline-flex tw-bg-white tw-px-5 tw-py-2 tw-rounded-full">Seleciona un partido para ver tus boletos</span>

                    <div class="tw-mt-5">
                        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
                            <v-tab v-for="(event, index) in eventsWithTickets" :key="event.event.id" :value="`tab-${index}`">
                                {{ event.event.name }}
                            </v-tab>
                        </v-tabs>
                    </div>
                </div>
            </div>

            <div class="tw-mt-10 tw-bg-white">
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item v-for="(event, index) in eventsWithTickets" :key="event.event.id" :value="`tab-${index}`">
                        <div v-if="event.tickets.length > 0" class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 lg:tw-overflow-y-auto">
                            <SaleTicket v-for="ticket in event.tickets" :key="ticket.id" v-bind:ticket="ticket" />
                        </div>
                        <div v-else class="tw-flex tw-items-center tw-flex-col tw-gap-5">
                            <div class="tw-p-5 tw-text-center tw-text-gray-500">
                                <span>No cuenta con boletos disponibles para este partido.</span>
                            </div>
                            <div>
                                <Link :href="route('events.index')">
                                    <v-btn variant="tonal" color="purple" size="large" rounded="xl" class="text-none"><span class="material-symbols-outlined tw-text-lg">note_stack</span>Obtener boletos</v-btn>
                                </Link>
                            </div>
                            <img class="tw-w-80" src="https://i.ibb.co/ck1SGFJ/Group.png" />
                        </div>
                    </v-tabs-window-item>
                </v-tabs-window>
            </div>
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
