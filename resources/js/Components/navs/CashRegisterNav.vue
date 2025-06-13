<script setup>
import useTicketOfficeState from '@/composables/TicketOfficeState';
import useUserPolicy from '@/composables/UserPolicy';
import { Link } from '@inertiajs/vue3';

const { cashRegisterPresent } = useTicketOfficeState();
const { viewVendorTopics } = useUserPolicy();
defineProps({
    user_roles: {
        type: Array,
        required: false,
    }
})
</script>

<template>
    <div v-if="user_roles && viewVendorTopics(user_roles)">
        <Link :href="route('ticket-offices.index')">
            <div v-if="cashRegisterPresent " class="tw-fixed tw-bottom-5 lg:tw-bottom-16 tw-left-3 tw-z-[60]">
                <div class="tw-flex tw-flex-col tw-items-center tw-text-xs lg:tw-text-base tw-gap-2 tw-justify-center tw-bg-white tw-shadow-lg tw-border-4 tw-cursor-pointer hover:tw-scale-105 tw-transition-transform tw-duration-500 tw-px-4 lg:tw-px-6 tw-py-3 lg:tw-py-4 tw-rounded-2xl">
                    <span class="text-xs">caja</span>
                    <div class="lg:tw-text-4xl tw-font-bold">{{ cashRegisterPresent }}</div>
                </div>
            </div>
            <div v-else class="tw-fixed tw-bottom-5 lg:tw-bottom-16 tw-left-3 tw-z-[60]">
                <div class="tw-flex tw-flex-col tw-items-center tw-text-xs lg:tw-text-base tw-gap-2 tw-justify-center tw-bg-white tw-shadow-lg tw-border-4 tw-cursor-pointer hover:tw-scale-105 tw-transition-transform tw-duration-500 tw-px-3.5 tw-py-3 lg:tw-py-4 tw-rounded-2xl">
                    <span class="text-xs">Abrir caja</span>
                    <span class="material-symbols-outlined tw-text-4xl">computer</span>
                </div>
            </div>
        </Link>
    </div>
</template>
