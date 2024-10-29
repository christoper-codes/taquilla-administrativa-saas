<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { ref } from 'vue';
import SaleTicket from '@/Components/SaleTicket.vue';
import { shareTicketSchema } from '@/validation/Administration/share-tickets-schema';
import InputError from '@/Components/InputError.vue';



const { handleSubmit } = useForm({validationSchema : shareTicketSchema});

const data  = useFormInertia({
    senderUserName: {},
    receiverUserName: {},
    ticketListOfSender: [],
});

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
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
let tickets_list_v = [];

let dialog = ref(false);

let alertDialong = ref(false);

const user = usePage().props.auth.user;
const selected_value = ref(null);

const alert = ()=> {

    data.receiverUserName = selected_value.value;

    if (!data.receiverUserName) {
        dialog.value = true
    }else{
        dialog.value = false
        alertDialong.value = true
    }
}

const selection = ref([])

const messageErrorreceiverUserName = ref('Debes seleccionar a un amigo');

const send_tickets = (values)=>{

    data.senderUserName = props.user['id'];
    data.receiverUserName = selected_value.value['id'];

    tickets_list_v.forEach(element => {
        data.ticketListOfSender.push(element['id']);
    });


    data.post(route('ticket-offices.change'), {
        onFinish: () => {
            alertDialong.value = false
            data.get(route('ticket-offices.share'),{});
        }
    });


};


const tickets_select = (tickets) => {
    let contain = false;

    if (tickets_list_v.length == 0) {
        tickets_list_v.push(tickets)
    }else{

        tickets_list_v.forEach(element => {
            if (tickets['id'] == element['id']) {
                contain = true
                tickets_list_v = tickets_list_v.filter(item => item['id'] !== tickets['id'])
            }
        });

        if (!contain) {
            contain = false
            tickets_list_v.push(tickets)
        }

    }
}


props.users.forEach(element => {

    if (user['username'] != element['username']) {
        users_list.push(
            {
                name: `${element['first_name']} ${element['last_name']} ${element['middle_name']} (${element['email']})`,
                value: element
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

        <v-dialog
            v-model="alertDialong"
            max-width="600"
        >
            <v-card
            >

                <div class="tw-p-5">
                    <h1 class="tw-font-bold">Términos y condiciones</h1>
                    <br/>
                    <p class="tw-bg-red-100 tw-py-2 tw-px-4 tw-rounded-xl ">
                        <b class="tw-text-xs tw-text-red-500 tw-uppercase">SOLO SE PUEDEN TRANSFERIR BOLETOS ENTRE USUARIOS DE OTRA APLICACIÓN (este boleto no llega al correo).</b>
                    </p>
                    <br/>
                    <p class="tw-text-xs tw-text-red-500">
                     EL BOLETO NO ESTÁ SUJETO A REEMBOLSO, CAMBIO O REPOSICIÓN. EL BOLETO TE DA DERECHO A UN ACCESO AL INMUEBLE. El boleto te da derecho a un lugar específico dentro del inmueble. No está permitido el reingreso. Este boleto es válido solo para el evento y asiento descrito en pantalla. Queda prohibido mostrar capturas de pantalla del boleto en la entrada. El poseedor del boleto asume cualquier riesgo o peligro accidental proveniente del evento. La admisión está sujeta a que el espectador permita que se practique la revisión correspondiente para evitar el acceso a alimentos y bebidas alcohólicas, drogas, armas, mochilas, maletas, productos de tabaco, vapeadores, grabadoras, cámaras de cualquier tipo o cualquier otro artículo o sustancia no autorizada. El titular del inmueble del evento o sus representantes se reservan el derecho de admisión o, en su caso, se retirará del inmueble a cualquier persona cuya conducta se considere ofensiva, que induzca al desorden, y en general aquellas conductas que pudieran constituir una infracción o delito, no estando obligado a reembolsar cantidad alguna. El espectador se obliga a cumplir con las reglas del inmueble.
                    </p>
                </div>

                <template v-slot:actions>
                    <v-btn
                        class="ms-auto tw-mb-2 tw-mr-2 text-none"
                        color="purple"
                        variant="tonal"
                        rounded="lg"
                        text="Transferir ahora"
                        @click="send_tickets"
                    ></v-btn>
                </template>
            </v-card>
        </v-dialog>


        <div class="tw-px-4 tw-py-10 lg:tw-p-10">

            <div class="tw-w-full !tw-flex tw-flex-col lg:tw-flex-row tw-mb-10 lg:tw-mb-0 lg:tw-gap-7">
                <div class="tw-w-full">
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
                </div>
                <div>
                    <v-btn @click="alert" variant="tonal" rounded="lg" text="Transferir" class="text-none lg:!tw-h-[70px]" color="purple" size="large"></v-btn>
                </div>
            </div>
            <div v-if="dialog === true">
                <p class="tw-text-red-500 tw-bg-red-100 tw-inline-flex tw-rounded-md tw-py-1 tw-px-4 tw-text-center tw-text-xs">{{ messageErrorreceiverUserName }}</p>
            </div>

            <div class="tw-mt-10 tw-gap-5 tw-w-full tw-flex tw-flex-col-reverse lg:tw-flex-row tw-items-start tw-justify-between">
                <div class="tw-w-full tw-shadow-lg tw-bg-gray-200 tw-px-5 tw-py-7 tw-overflow-x-scroll tw-rounded-2xl tw-border tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center">
                    <span class="tw-text-sm tw-font-bold tw-text-gray-500 tw-text-center tw-inline-flex tw-bg-white tw-px-5 tw-py-2 tw-rounded-full">Desliza y seleciona un partido para ver tus boletos</span>

                    <div class="tw-mt-5 tw-hidden lg:tw-block">
                        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
                            <v-tab v-for="(event, index) in eventsWithTickets" :key="event.event.id" :value="`tab-${index}`">
                                {{ event.event.name }}
                            </v-tab>
                        </v-tabs>
                    </div>
                </div>
            </div>

            <div class="lg:tw-hidden tw-w-full tw-mt-5">
                <v-tabs v-model="tab" align-tabs="center" class="tw-relative" color="deep-purple-accent-4">
                    <v-tab v-for="(event, index) in eventsWithTickets" :key="event.event.id" :value="`tab-${index}`">
                        <span class="!tw-text-xs">{{ event.event.name }}</span>
                    </v-tab>
                </v-tabs>
                <div class="tw-flex tw-w-full tw-items-center tw-justify-center tw-flex-col tw-mt-2">
                    <div class="tw-flex tw-items-center tw-justify-center tw-relative">
                        <div class="tw-animate-ping tw-absolute -tw-right-1 tw-top-5 tw-inline-flex tw-h-4 tw-w-4 tw-rounded-full tw-bg-purple-500 tw-opacity-80"></div>
                        <span class="material-symbols-outlined tw-rotate-90 tw-text-6xl tw-text-gray-500 tw-relative tw-z-10">swipe_down</span>
                    </div>
                    <p class="tw-text-xs tw-mt-2">Desliza hacia la izquierda para ver y selecionar los partidos</p>
                </div>
            </div>

            <div class="tw-mt-10 tw-bg-white">
                <InputError class="" :message="data.errors.receiverUserName" />
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item v-for="(event, index) in eventsWithTickets" :key="event.event.id" :value="`tab-${index}`">
                        <div v-if="event.tickets.length > 0" class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 lg:tw-overflow-y-auto">
                            <v-item-group
                                v-model="selection"
                                multiple
                            >
                            <v-row>
                            <div class="tw-px-5 tw-py-5" v-for="ticket in event.tickets" :key="ticket.id">
                                <v-row align="center" justify="space-between">
                                    <v-col  cols="12" sm="6" md="4" lg="3">
                                        <SaleTicket v-bind:ticket="ticket"/>
                                    </v-col>

                                    <v-col  cols="12" sm="6" md="4" lg="3">
                                        <div @click="tickets_select(ticket)" >
                                            <v-item v-slot="{ isSelected , toggle}">
                                            <v-btn @click="toggle" :color="isSelected ? 'blue': 'green' "  :icon="isSelected ? 'mdi-cancel' : 'mdi-share'"></v-btn>
                                            </v-item>
                                        </div>
                                    </v-col>


                                </v-row>
                            </div>
                            </v-row>
                            </v-item-group>
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

<style scoped>
.container{
    align-items: center;
    width: 70%;
}
</style>
