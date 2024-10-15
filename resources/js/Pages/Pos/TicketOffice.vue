<script setup>
import { Head, Link, router,  useForm as useFormInertia } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { cashRegisterSchema } from '@/validation/cash.-registers/cash-regiser-schema';
import { useForm, useField } from 'vee-validate'

const { handleSubmit } = useForm({validationSchema : cashRegisterSchema});
const cashRegisterFields = {
    'cash_register_type_id': useField('cash_register_type_id'),
    'opening_balance': useField('opening_balance'),
}
const loading = ref(false);

const selectedEvents = ref([]);
const cashRegisterData = useFormInertia({
    'ticket_office_id': '',
    'cash_register_type_id': '',
    'seller_user_opening_id': '',
    'opening_balance': '',
})

onMounted(() => {
    selectedEvents.value = props.events.map((event) => event);
})

const cashRegisterSubmit = handleSubmit((values, isActive) => {
    loading.value = true;
    cashRegisterData.ticket_office_id = props.ticket_office.id;
    cashRegisterData.seller_user_opening_id = props.auth_user.id;
    cashRegisterData.cash_register_type_id = values.cash_register_type_id.id;
    cashRegisterData.opening_balance = values.opening_balance;

    cashRegisterData.post(route('cash-registers.store'), {
        onFinish: () => {
            loading.value = false;
            isActive.evt.value = false;
        }
    });
});

const props = defineProps({
    'ticket_office': {
        type: Object,
        required: true,
    },
    'events': {
        type: Array,
        required: true,
    },
    'auth_user': {
        type: Object,
        required: true,
    },
})

const eventProps = (item) => {
  return {
    title: item.name,
    subtitle: item.description,
  };
};

const ticketOfficeProps = (item) => {
  return {
    id: item.id,
    title: item.name,
    subtitle: item.description,
  };
};

console.log(props.ticket_office);

</script>

<template>
    <Head title="Taquillas" />
    <GuestLayout />
    <NavigationDrawer />

    <main class="tw-relative tw-overflow-hidden">
        <section class="tw-max-w-7xl tw-pt-28 lg:tw-pt-10 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">
            <ErrorSession />
                <div class="tw-w-full tw-flex tw-gap-20">
                    <div class="">
                        <div class="tw-flex tw-h-full tw-w-full tw-not-prose">
                            <div class="tw-relative tw-shadow-black/5 tw-shadow-none tw-rounded-large">
                                <img src="https://i.pinimg.com/564x/4a/04/11/4a04110cc00a352c8c8bc63c4731db1c.jpg" class="tw-relative tw-w-[400px] tw-rounded-lg tw-z-10 tw-opacity-0 tw-shadow-black/5 data-[loaded=true]:tw-opacity-100 tw-shadow-none tw-transition-transform-opacity motion-reduce:tw-transition-none !tw-duration-300 tw-rounded-large tw-m-5" width="240" data-loaded="true">
                                <img src="https://i.pinimg.com/564x/4a/04/11/4a04110cc00a352c8c8bc63c4731db1c.jpg" class="tw-absolute tw-z-0 tw-inset-0 tw-w-full tw-h-full tw-object-cover tw-filter tw-blur-sm tw-pr-[30px] tw-scale-105 tw-saturate-150 tw-opacity-30 tw-translate-y-1 tw-rounded-large" width="240" aria-hidden="true" data-loaded="true">
                            </div>
                        </div>
                    </div>

                    <div class="tw-space-y-5 lg:tw-space-y-8 tw-max-w-2xl">
                        <Link :href="route('welcome')">
                            <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Regresar al inicio
                            </div>
                        </Link >

                        <h2 class="tw-text-4xl tw-font-bold">{{ ticket_office.name }}. Administracion para el club halcones de xalapa</h2>

                        <div class="tw-flex tw-items-center tw-gap-x-5">
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>Halcones de Xalapa
                            </div>
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">location_on</span>{{ ticket_office.is_active ? 'Activo' : 'Inactivo' }}
                            </div>
                            <div class="tw-inline-flex tw-items-center tw-gap-1.5 tw-py-1 tw-px-3 sm:tw-py-2 sm:tw-px-4 tw-rounded-full tw-text-xs sm:tw-text-sm tw-bg-gray-100 tw-text-gray-800 hover:tw-bg-gray-200 focus:tw-outline-none focus:tw-bg-gray-200">
                                <span class="material-symbols-outlined tw-text-xl">calendar_today</span>{{ ticket_office.description }}
                            </div>
                        </div>
                    </div>

                </div>
        </section>

        <div class="tw-w-full tw-bg-slate-950 tw-relative tw-overflow-hidden tw-mt-16 tw-mb-36">
            <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-items-center tw-justify-between tw-py-16 tw-gap-9">
                <div class="tw-h-60 tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Abrir cajas registradoras</h3>
                    <v-dialog max-width="700">
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-1/2" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">store</span>Abrir caja registradora</v-btn>
                        </template>
                        <template v-slot:default="{ isActive }">
                            <v-card title="¿Estas seguro de abrir una caja registradora?">
                            <v-form>
                                <v-card-text>
                                <div>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Partidos activos"
                                        hint="Partidos activos"
                                        multiple
                                        v-model="selectedEvents"
                                        :item-props="eventProps"
                                        :items="events"
                                    ></v-select>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Seleciona la caja"
                                        hint="Selecciona la caja"
                                        v-model= "cashRegisterFields.cash_register_type_id.value.value"
                                        :item-props="ticketOfficeProps"
                                        :items="ticket_office.cash_register_types_no_actives"
                                        :error-messages="cashRegisterFields.cash_register_type_id.errorMessage.value"
                                    ></v-select>
                                    <v-text-field
                                        color="primary"
                                        label="Saldo de apertura"
                                        placeholder="$1000.00"
                                        hint="Ingresa el saldo de apertura"
                                        v-model="cashRegisterFields.opening_balance.value.value"
                                        :error-messages="cashRegisterFields.opening_balance.errorMessage.value"
                                    ></v-text-field>
                                </div>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                               <div class="tw-flex tw-items-center tw-gap-3 tw-mb-3">
                                    <v-btn variant="tonal" color="red" class="text-none !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Cancelar</v-btn>
                                    <v-btn :loading="loading" variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl" @click="cashRegisterSubmit(isActive)">Abrir caja</v-btn>
                               </div>
                            </v-card-actions>
                            </v-form>

                            </v-card>
                        </template>
                    </v-dialog>
                </div>

                <div class="tw-h-60 tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Resumen de cajas</h3>
                    <v-dialog max-width="700">
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-1/2" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">credit_score</span>Cajas aperturadas</v-btn>
                        </template>
                        <template v-slot:default="{ isActive }">
                            <v-card title="Resumen de cajas aperturadas">
                            <v-card-text>
                                <div class="">
                                    Resumen
                                </div>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                               <div class="tw-flex tw-items-center tw-gap-3 tw-mb-3">
                                    <v-btn variant="tonal" color="red" class="text-none !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Cancelar</v-btn>
                                    <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Aceptar</v-btn>
                               </div>
                            </v-card-actions>

                            </v-card>
                        </template>
                    </v-dialog>
                </div>

                <div class="tw-h-60 tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Registro de usuarios</h3>
                    <v-dialog max-width="700">
                        <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-1/2" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">person</span>Registrar usuarios</v-btn>
                        </template>
                        <template v-slot:default="{ isActive }">
                            <v-card title="¿Estas seguro de registrar un nuevo usuario?">
                            <v-card-text>
                                <div>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Seleciona el partido"
                                        hint="Selecciona el partido"
                                        :item-props="eventProps"
                                        :items="events"
                                    ></v-select>
                                    <v-select
                                        color="primary"
                                        clearable
                                        label="Seleciona la caja"
                                        hint="Selecciona la caja"
                                        :item-props="ticketOfficeProps"
                                        :items="ticket_office.cash_register_types"
                                    ></v-select>
                                    <v-text-field
                                        color="primary"
                                        label="Saldo de apertura"
                                        placeholder="$1000.00"
                                        hint="Ingresa el saldo de apertura"
                                    ></v-text-field>
                                </div>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                               <div class="tw-flex tw-items-center tw-gap-3 tw-mb-3">
                                    <v-btn variant="tonal" color="red" class="text-none !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Cancelar</v-btn>
                                    <v-btn variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl" @click="isActive.value = false">Registrar ahora</v-btn>
                               </div>
                            </v-card-actions>

                            </v-card>
                        </template>
                    </v-dialog>
                </div>
            </div>

            <div class="tw-absolute tw-left-1/2 tw-top-[80%] tw-h-[1280px] tw-w-[800px] tw--translate-x-1/2 tw-rounded-full tw-bg-gradient-to-t tw-blur-[250px] tw-from-tw-primary-800 tw-to-tw-primary-600">
            </div>

        </div>

        <Footer />

    </main>
</template>

<style scoped>

</style>
