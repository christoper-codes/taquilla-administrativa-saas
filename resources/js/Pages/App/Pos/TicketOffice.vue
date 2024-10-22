<script setup>
import { Head, Link, router,  useForm as useFormInertia } from '@inertiajs/vue3';
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { cashRegisterSchema } from '@/validation/cash.-registers/cash-regiser-schema';
import { useForm, useField } from 'vee-validate'
import useTicketOfficeState from '@/composables/TicketOfficeState';
import usePriceFormat from '@/composables/priceFormat';
import useDateFormat from '@/composables/dateFormat';

const { formatPrice } = usePriceFormat();
const { dateFormat } = useDateFormat();
const { handleSubmit } = useForm({validationSchema : cashRegisterSchema});
const cashRegisterFields = {
    'cash_register_type_id': useField('cash_register_type_id'),
    'opening_balance': useField('opening_balance'),
}
const loading = ref(false);
const { cashRegisterPresent } = useTicketOfficeState();

const selectedEvents = ref([]);
const cashRegisterData = useFormInertia({
    ticket_office_id: '',
    cash_register_type_id: '',
    seller_user_opening_id: '',
    opening_balance: '',
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
        onSuccess: (response) => {
            cashRegisterFields.cash_register_type_id.value.value = '';
            cashRegisterFields.opening_balance.value.value = '';
            localStorage.setItem('cashRegisterData', JSON.stringify(response.props.active_cash_register));
            cashRegisterPresent.value = response.props.active_cash_register.cash_register_type_id;
        },
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
    'active_cash_register': {
        type: Object,
        required: false,
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

const dialog = ref(false);
const notifications = ref(false);
const sound = ref(true);
const widgets = ref(false);

console.log(props.ticket_office);
console.log(props.active_cash_register);


</script>

<template>
    <Head title="Taquillas" />
    <GuestLayout />
    <NavigationDrawer />

    <main class="tw-relative tw-overflow-hidden">
        <section class="tw-max-w-7xl tw-pt-20 tw-mb-20 tw-mx-auto tw-px-4 lg:tw-px-0 ">
            <SuccessSession />
                <div class="tw-w-full tw-flex tw-gap-10 lg:tw-gap-20 tw-flex-col lg:tw-flex-row">
                    <div class="tw-group tw-relative tw-flex tw-flex-col tw-w-full lg:tw-w-1/3 tw-min-h-60 tw-bg-[url('https://i.pinimg.com/564x/4a/04/11/4a04110cc00a352c8c8bc63c4731db1c.jpg')] tw-bg-center tw-bg-cover tw-rounded-xl tw-hover:shadow-lg tw-focus:outline-none tw-focus:shadow-lg tw-transition" href="#">
                        <div class="tw-absolute tw-bottom-5 tw-left-10 tw-w-[80%] tw-rounded-xl tw-bg-black/40 tw-p-3 tw-backdrop-blur-md tw-backdrop-brightness-150 tw-text-white tw-font-bold tw-text-center">
                            {{ ticket_office.name }}
                        </div>
                    </div>

                    <div class="tw-space-y-5 lg:tw-space-y-8 tw-w-full lg:tw-w-1/2">
                        <Link :href="route('welcome')">
                            <div class="tw-inline-flex tw-cursor-pointer tw-items-center tw-gap-x-1.5 tw-text-sm tw-text-gray-600 tw-decoration-2 hover:tw-underline focus:tw-outline-none focus:tw-underline">
                                <svg class="tw-shrink-0 tw-size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Regresar al inicio
                            </div>
                        </Link >

                        <h2 class="lg:tw-text-4xl tw-text-3xl tw-font-bold">{{ ticket_office.name }}. Administracion para el club halcones de xalapa</h2>

                        <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-center tw-gap-5">
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
            <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-py-16 tw-gap-9 tw-px-4 lg:tw-px-0">
                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
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

                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20 tw-relative">
                    <div v-if="active_cash_register" class="tw-absolute -tw-top-1 -tw-right-1">
                        <span class="tw-flex tw-h-6 tw-w-6">
                        <span class="tw-animate-ping tw-absolute tw-inline-flex tw-h-full tw-w-full tw-rounded-full tw-bg-green-400 tw-opacity-75"></span>
                        <span class="tw-inline-flex tw-rounded-full tw-h-6 tw-w-6 tw-bg-green-500"></span>
                        </span>
                    </div>
                    <h3 class="text-white tw-text-2xl">Resumen de caja <span v-if="active_cash_register">{{ active_cash_register.cash_register_type_id }}</span> </h3>
                    <div class="text-center pa-4">
                        <v-dialog
                            v-model="dialog"
                            transition="dialog-bottom-transition"
                            fullscreen
                        >
                            <template v-slot:activator="{ props: activatorProps }">
                            <v-btn v-bind="activatorProps" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-20 !tw-w-[335px]" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">credit_score</span>Cajas aperturadas</v-btn>
                            </template>

                            <v-card>
                            <v-toolbar class="!tw-bg-gradient-to-r !tw-from-slate-950 !tw-via-purple-950 !tw-to-slate-950">
                                <v-btn
                                class="!tw-text-white"
                                icon="mdi-close"
                                @click="dialog = false"
                                ></v-btn>

                                <v-toolbar-title>
                                    <div class="tw-font-bold tw-text-white">Resumen de caja</div>
                                </v-toolbar-title>

                                <v-spacer></v-spacer>

                                <v-toolbar-items>
                                <v-btn
                                    color="white"
                                    text="Aceptar"
                                    variant="tonal"
                                    @click="dialog = false"
                                ></v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <div v-if="active_cash_register" class="tw-w-full tw-max-w-7xl tw-mx-auto tw-mt-10">
                                <div class="tw-text-4xl tw-font-bold"> <span class="tw-text-purple-700">Apertura:</span> {{ dateFormat(active_cash_register.created_at) }}</div>
                                <div class="tw-grid tw-grid-cols-4 tw-gap-10  tw-mt-10">
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Usuario vendedor</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ auth_user.first_name }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Caja registradora</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ active_cash_register.cash_register_type_id }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Saldo de apertura</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ formatPrice(active_cash_register.opening_balance) }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Saldo actual</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ formatPrice(active_cash_register.current_balance) }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Ventas con tarjeta</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ formatPrice(0) }}</div>
                                    </div>
                                    <div class="tw-p-5 tw-rounded-xl tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-3">
                                        <div class="tw-bg-white tw-py-2 tw-px-4 tw-rounded-full tw-text-sm">Ventas con efectivo</div>
                                        <div class="tw-text-4xl tw-font-bold">{{ formatPrice(0) }}</div>
                                    </div>
                                </div>
                                <div class="tw-max-w-7xl tw-py-10 tw-lg:py-14 tw-mx-auto">
  <!-- Card -->
  <div class="tw-flex tw-flex-col">
    <div class="tw--m-1.5 tw-overflow-x-auto">
      <div class="tw-p-1.5 tw-min-w-full tw-inline-block tw-align-middle">
        <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-xl tw-shadow-sm tw-overflow-hidden tw-dark:bg-neutral-800 tw-dark:border-neutral-700">
          <!-- Header -->
          <div class="tw-px-6 tw-py-4 tw-grid tw-gap-3 tw-md:flex tw-md:justify-between tw-md:items-center tw-border-b tw-border-gray-200 tw-dark:border-neutral-700">
            <div>
              <h2 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-dark:text-neutral-200">
                Tickets de venta
              </h2>
              <p class="tw-text-sm tw-text-gray-600 tw-dark:text-neutral-400">
                Listado de tickets vendidos en la caja registradora
              </p>
            </div>

            <div>
              <div class="tw-inline-flex tw-gap-x-2">
                <a class="tw-py-2 tw-px-3 tw-inline-flex tw-items-center tw-gap-x-2 tw-text-sm tw-font-medium tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-text-gray-800 tw-shadow-sm tw-hover:bg-gray-50 tw-disabled:opacity-50 tw-disabled:pointer-events-none tw-focus:outline-none tw-focus:bg-gray-50 tw-dark:bg-transparent tw-dark:border-neutral-700 tw-dark:text-neutral-300 tw-dark:hover:bg-neutral-800 tw-dark:focus:bg-neutral-800" href="#">
                  Ver todos
                </a>

                <a class="tw-py-2 tw-px-3 tw-inline-flex tw-items-center tw-gap-x-2 tw-text-sm tw-font-medium tw-rounded-lg tw-border tw-border-transparent tw-bg-purple-600 tw-text-white tw-hover:bg-blue-700 tw-focus:outline-none tw-focus:bg-blue-700 tw-disabled:opacity-50 tw-disabled:pointer-events-none" href="#">
                   Regresar
                </a>
              </div>
            </div>
          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 tw-dark:divide-neutral-700">
            <thead class="tw-bg-gray-50 tw-dark:bg-neutral-800">
              <tr>
                <th scope="col" class="tw-ps-6 tw-py-3 tw-text-start">
                  <label for="hs-at-with-checkboxes-main" class="tw-flex">
                    <input type="checkbox" class="tw-shrink-0 tw-border-gray-300 tw-rounded tw-text-blue-600 tw-focus:ring-blue-500 tw-disabled:opacity-50 tw-disabled:pointer-events-none tw-dark:bg-neutral-800 tw-dark:border-neutral-600 tw-dark:checked:bg-blue-500 tw-dark:checked:border-blue-500 tw-dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                    <span class="tw-sr-only">Checkbox</span>
                  </label>
                </th>

                <th scope="col" class="tw-ps-6 tw-lg:ps-3 tw-xl:ps-0 tw-pe-6 tw-py-3 tw-text-start">
                  <div class="tw-flex tw-items-center tw-gap-x-2">
                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800 tw-dark:text-neutral-200">
                      codigo
                    </span>
                  </div>
                </th>

                <th scope="col" class="tw-px-6 tw-py-3 tw-text-start">
                  <div class="tw-flex tw-items-center tw-gap-x-2">
                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800 tw-dark:text-neutral-200">
                       precio
                    </span>
                  </div>
                </th>

                <th scope="col" class="tw-px-6 tw-py-3 tw-text-start">
                  <div class="tw-flex tw-items-center tw-gap-x-2">
                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800 tw-dark:text-neutral-200">
                      Status
                    </span>
                  </div>
                </th>

                <th scope="col" class="tw-px-6 tw-py-3 tw-text-start">
                  <div class="tw-flex tw-items-center tw-gap-x-2">
                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800 tw-dark:text-neutral-200">
                      Fecha
                    </span>
                  </div>
                </th>

                <th scope="col" class="tw-px-6 tw-py-3 tw-text-start">
                  <div class="tw-flex tw-items-center tw-gap-x-2">
                    <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-800 tw-dark:text-neutral-200">
                      Acciones
                    </span>
                  </div>
                </th>

                <th scope="col" class="tw-px-6 tw-py-3 tw-text-end"></th>
              </tr>
            </thead>

            <tbody class="tw-divide-y tw-divide-gray-200 tw-dark:divide-neutral-700">
              <tr>
                <!-- Contenido de las filas -->
              </tr>
            </tbody>
          </table>
          <!-- End Table -->
        </div>
      </div>
    </div>
  </div>
</div>

                            </div>
                            <div v-else class="tw-flex tw-items-center tw-justify-center tw-mt-20 tw-flex-col tw-gap-10">
                                <div class="tw-font-bold tw-text-center tw-bg-gray-200 tw-rounded-full tw-inline-flex tw-px-7 tw-py-3 tw-text-gray-600">
                                    No hay cajas abiertas para este usuario en esta taquilla
                                </div>
                                <img class="tw-w-96" src="../../../../../public/img/seats-no-selected-img.svg" alt="">
                            </div>
                            </v-card>
                        </v-dialog>
                        </div>

                </div>

                <div class="tw-h-60 tw-w-full lg:tw-w-96 tw-bg-white/10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-gap-5 tw-px-5 py-14 tw-flex-col hover:tw-scale-105 tw-transition-all tw-duration-500  tw-z-20">
                    <h3 class="text-white tw-text-2xl">Vender entradas</h3>
                    <Link :href="route('events.index')" class="tw-h-full tw-w-full">
                        <v-btn variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400 !tw-h-20 !tw-w-[335px]" rounded="xl" size="large" block><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">credit_score</span>Vender entradas</v-btn>
                    </Link>
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
