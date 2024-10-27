<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import SaleTicket from '@/Components/SaleTicket.vue';

const props = defineProps({
    "users": {
        Type: Object,
        Required: true
    },
    'events_with_tickets': {
        type: Object,
        required: true,
    },
})

const eventsWithTickets = ref(Object.values(props.events_with_tickets));
const tab = ref('tab-0');

const users_list = [];
const user = usePage().props.auth.user;
const selected_value = ref(null);

props.users.forEach(element => {

    if (user['username'] != element['username']) {
        users_list.push(
            {
                name: `${element['first_name']} ${element['last_name']} ${element['middle_name']} (${element['email']})`,
                value: element['username']
            }
        )
    }

});

</script>

<template>

    <Head title="Compartir"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Compartir Tickets</span>
        </BreadcrumbAppSecondary>


        <div class="tw-px-4 tw-py-10 lg:tw-p-10">

            <v-container>
                <v-row>
                    <v-col xs12 sm6 md4>
                        <v-autocomplete
                            v-model="selected_value"
                            clearable
                            chips
                            label="Busca a tu amigo..."
                            :items="users_list"
                            variant="solo-filled"
                            item-title="name"
                            item-value="value"
                        ></v-autocomplete>
                    </v-col>
                </v-row>
            </v-container>

            <div class="tw-mt-1 tw-gap-5 tw-w-full tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-betwee">
                <div class="tw-w-full tw-shadow-lg tw-bg-gray-200 tw-px-5 tw-overflow-x-scroll tw-rounded-2xl tw-border tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center">
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


</template>

<style scoped></style>
