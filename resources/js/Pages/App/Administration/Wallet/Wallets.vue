<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import useDateFormat from '@/composables/dateFormat';
import useStringFormat from '@/composables/stringFormat';
import useObjectsFormat from '@/composables/objectsFormat';
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate'
import { ref, computed, watch } from 'vue';
import { walletSchema } from '@/validation/Administration/Wallet/wallet-schema';
import InputError from '@/Components/InputError.vue';

const { formatDateForDisplay } = useDateFormat();
const { formatFirstLetterUppercase, formatTitleCase } = useStringFormat();
const { findPropertyInObject, cloneObject } = useObjectsFormat();

/**
 * Propiedades esperadas por el componente
 */
    const props = defineProps({
        wallet_account_types: { type: Array, required: true },
        wallet_account_roles: { type: Array, required: true },
        wallet_accounts: { type: Array, required: true },
        global_seasons: { type: Array, required: true },
    });

/**
 * Configuración de tabla de datos para Wallets
 */
    const headerAccount = [
        { title: 'Usuario', align: 'start', sortable: true, key: 'full_name' },
        { title: 'Asiento', align: 'start', sortable: true, key: 'code' },
        { title: 'Correo', align: 'start', sortable: true, key: 'email' },
        { title: 'Número de teléfono', align: 'start', sortable: true, key: 'phone_number' },
        { title: 'Número de Cuenta', align: 'start', sortable: true, key: 'account_number' },
        { title: 'Fecha de Creación', align: 'start', sortable: true, key: 'created_at' },
    ];

    const expanded = ref([]);

    const headerWallet = [
        { title: 'Cuenta', align: 'start', sortable: true, key: 'name' },
        { title: 'Saldo Actual', align: 'start', sortable: true, key: 'current_balance' },
        { title: 'Fecha de Expiración', align: 'start', sortable: true, key: 'expiration_date' },
        { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
        { title: 'Acciones', key: 'actions', sortable: false }
    ];

/**
 * Estado del formulario de creación y edición de Monedero
 */
    const dialogFormWalletIsOpen = ref(false);
    const editedWalletIndex = ref(-1);
    const isCreating = computed(() => editedWalletIndex.value === -1 ? true : false);
    const formTitleWallet = computed(() => isCreating.value ? 'Nuevo Monedero' : 'Editar Monedero');

    const { handleSubmit, resetForm } = useForm({
        validationSchema: walletSchema,
        initialValues: {
            id: null,
            is_active: true,
            current_balance: 0
        },
    });

/**
 * Configuración de el Tab de creación de cuenta
 */
    const valuesTabDialog = {
        subscribe:'Abonado',
        user:'Usuario',
        generic:'Genérico',
    }

    const tabDialogForm = ref(valuesTabDialog.subscribe);

    function selectTabDialogForm(property, value) {
        if (property === 'user_id' && value) {
            tabDialogForm.value = valuesTabDialog.user;
        }else if(property === 'season_ticket_id' && value){
            tabDialogForm.value = valuesTabDialog.subscribe;
        }
    }

/**
 * Configuración del modelo de formulario Agreement
 */
    const walletFields = {
        id:{
            isField: true,
        },
        user_id:{
            isField: true,
        },
        role_type_id:{
            isField: true,
        },
        wallet_account_type_id:{
            isField: true,
        },
        season_ticket_id:{
            isField: true,
        },
        current_balance:{
            isField: true,
        },
        account_number:{
            isField: true,
        },
        is_active:{
            isField: true,
        },
        expiration_date:{
            isField: true,
        },
        global_season_id:{
            isField: false,
        },

        // Account privilege
        name:{
            isField: true,
        },
        code:{
            isField: true,
        },
        percentage_cashback:{
            isField: true,
        },
        description:{
            isField: true,
        }
    };

/**
 * Inicialización de campos con useField o ref
 */
    const wallet = Object.fromEntries(Object.keys(walletFields).map((property) => [property, walletFields[property].isField ? useField(property) : ref(property)] ));

/**
 * Configuración inicial del formulario para useFormInertia
 */
    const dataFormWallet = useFormInertia(Object.fromEntries(Object.keys(walletFields).filter((property) => walletFields[property].isField).map((property) => [property, ''])));

/**
 * Limpieza de los las propiedades ref del formulario
 *
 */
    function clearPropertiesRef() {
        Object.keys(walletFields).filter((property) => !walletFields[property].isField).forEach((property) =>  wallet[property].value = null);
    }

/**
 * Recuperación del símbolo de la moneda del tipo de cuenta
 */
    const walletCurrency = computed(() => props.wallet_account_types.find((type) => type.id === wallet.wallet_account_type_id.value.value)?.wallet_currency?.symbol || '' );

/**
 * Mostrara campos de beneficios
 */

    const isCashless = computed(() => wallet.wallet_account_type_id.value.value == 1 );

    watch( () => wallet.wallet_account_type_id.value.value, (newValue) => {

        if (newValue != 1) {
            wallet.name.setValue('');
            wallet.code.setValue('');
            wallet.percentage_cashback.setValue('');
            wallet.description.setValue('');
        }
    });

/**
 * Obtención de Tickets por Temporada
 */
    const ticketsBySeasonList = ref([]);

    function getTicketsBySeason(global_season_id) {

        axios.get(route('show.tickets.by.season', global_season_id)).then((response) => {

            ticketsBySeasonList.value = response.data.map((value) => {
                value.full_name = `${formatTitleCase(value.full_name)} | ${value.seat_catalogue.code}`;
                return value;
            })

        })
        .catch((error) => {
            console.error('Error:', error);
        })
        .finally(() => {});
    }

    watch(() => wallet.global_season_id.value, (global_season_id) => {
        if (global_season_id) {
            getTicketsBySeason(global_season_id);
        }
    });

    const searchQuerySubscribe = ref('');

    function customFilterSubscriber (value, query, item) {

        const lowerCaseQuery = query.toLowerCase();

        const fullNameTemp = item.raw.full_name?.toLowerCase() || '';
        const emailTemp = item.raw.holder_email?.toLowerCase() || '';

        return fullNameTemp.includes(lowerCaseQuery) || emailTemp.includes(lowerCaseQuery);
    }

    const customTitleSubscriber = computed(() => {

        const query = searchQuerySubscribe.value.toLowerCase() || '';

        return  query.includes('@') ? 'holder_email' : 'full_name';
    });

/**
 * Obtención de Tickets por Temporada
 */
    const users = ref([]);

    function getUsersAll() {

        axios.get(route('show.all.users')).then((response) => {

            users.value = response.data;

        })
        .catch((error) => {
            console.error('Error:', error);
        })
        .finally(() => {});
    }

    watch(tabDialogForm, (valueTab) => {
        if (valueTab === valuesTabDialog.user) {
            getUsersAll()
        }
    });

    const searchQueryUser = ref('');

    function customFilterUser (value, query, item) {

        const lowerCaseQuery = query.toLowerCase();

        const fullNameTemp = item.raw.full_name?.toLowerCase() || '';
        const emailTemp = item.raw.holder_email?.toLowerCase() || '';

        return fullNameTemp.includes(lowerCaseQuery) || emailTemp.includes(lowerCaseQuery);
    }

    const customTitleUser = computed(() => {

        const query = searchQueryUser.value.toLowerCase() || '';

        return  query.includes('@') ? 'holder_email' : 'full_name';
    });

/**
 * Rutas y métodos para guardar o actualizar
 */
    const routeAndMethod = {
        save:{
            route:'wallets.store',
            method:'post'
        },
        update:{
            route:'wallets.update',
            method:'put'
        }
    };

/**
 * Función para asignar valores del formulario al objeto al formulario
 */
    function addValuesFormRequest(dataForm) {
        Object.keys(walletFields).filter((property) => walletFields[property].isField).forEach((property) => dataFormWallet[property] = dataForm[property]);
    }

/**
 * Manejo del envío de datos
 */
    const saveDataWallet = handleSubmit((dataForm) => {

        addValuesFormRequest(dataForm);

        const dataRequest =  isCreating.value ? routeAndMethod.save : routeAndMethod.update;

        dataFormWallet[dataRequest.method](route(dataRequest.route, dataForm.id), {
            onSuccess: (page) => {
                closeFormWallet();
            },
            onError: (errors) => {
            },
            onFinish: () => {}
        });
    });

    function addValuesFormDialog(walletAccount, account) {

        const { wallet_account_types, ...walletAccountTemp } = walletAccount;

        account.wallet_account = cloneObject(walletAccountTemp);
        account.id = account.pivot.id; // Se reasigna el id de la WalletAccount a el del WalletAccountWalletAccountType para hacer su actualización;

        Object.keys(walletFields).forEach((property) => {

            if (property === 'user_id' || property === 'season_ticket_id') {
                selectTabDialogForm(property, findPropertyInObject(account, property));
            }

            if (walletFields[property].isField) {

                let valueProperty = findPropertyInObject(account, property);

                if ('is_active' === property) {

                    valueProperty = valueProperty ? true : false

                }else if ('expiration_date' === property) {

                    valueProperty = new Date(valueProperty);

                }

                wallet[property].setValue(valueProperty);
            }else{

                wallet[property].value = findPropertyInObject(account, property);
            }
        });
    }

    const editWallet = (walletAccount, account) => {

        addValuesFormDialog(walletAccount,account);

        editedWalletIndex.value = props.wallet_accounts.indexOf(walletAccount);
        dialogFormWalletIsOpen.value = true;
    }

/**
 *  Cierre y reset del formulario
 */
    const closeFormWallet = () => {
        resetForm();
        clearPropertiesRef();
        dialogFormWalletIsOpen.value = false;
        editedWalletIndex.value = -1;
    };

</script>

<template>
    <Head title="Administracion" />
    <SuccessSession />
    <AppLayout>
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de Monederos</span>
        </BreadcrumbAppSecondary>

        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-data-table v-model:expanded="expanded" :headers="headerAccount" :items="wallet_accounts" item-value="id" show-expand>
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title class="tw-uppercase">Monedero</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogFormWalletIsOpen" max-width="900px" persistent>
                            <template v-slot:activator="{ props }">
                                <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"
                                    v-bind="props">
                                    Nuevo Monedero
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">{{ formTitleWallet }}</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                                            <v-tabs bg-color="" fixed-tabs v-model="tabDialogForm" :disabled="!isCreating">
                                                <v-tab :value="valuesTabDialog.subscribe">
                                                    {{ valuesTabDialog.subscribe }}
                                                </v-tab>
                                                <v-tab :value="valuesTabDialog.user">
                                                    {{ valuesTabDialog.user }}
                                                </v-tab>
                                                <v-tab :value="valuesTabDialog.generic">
                                                    {{ valuesTabDialog.generic }}
                                                </v-tab>
                                            </v-tabs>
                                            <v-tabs-window>
                                                <v-form  class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                                                    <div v-if="tabDialogForm == valuesTabDialog.subscribe" class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Temporada
                                                            </p>
                                                            <v-select
                                                                label="Temporada"
                                                                hint="Selecciona la temporada"
                                                                :items="global_seasons"
                                                                :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                item-value="id"
                                                                v-model="wallet.global_season_id.value"
                                                                :disabled="!isCreating">
                                                            </v-select>
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Abono
                                                            </p>
                                                            <v-autocomplete
                                                                :items="ticketsBySeasonList"
                                                                v-model="wallet.season_ticket_id.value.value"
                                                                clearable
                                                                label="Abonado"
                                                                hint="Buscar abonado por nombre o correo electrónico"
                                                                item-value="id"
                                                                v-model:search="searchQuerySubscribe"
                                                                :item-title="customTitleSubscriber"
                                                                :custom-filter="customFilterSubscriber"
                                                                :disabled="!isCreating"
                                                            >
                                                            <template v-slot:item="{ props, item }">
                                                                <v-list-item
                                                                    v-bind="props"
                                                                    :title="item.raw.full_name"
                                                                    :subtitle="item.raw.holder_email"
                                                                ></v-list-item>
                                                            </template>
                                                        </v-autocomplete>
                                                        </div>
                                                    </div>
                                                    <div v-if="tabDialogForm === valuesTabDialog.user" class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Usuario
                                                            </p>
                                                            <v-autocomplete
                                                                :items="users.map((value) => { value.full_name = formatTitleCase(value.full_name); return value; })"
                                                                v-model="wallet.user_id.value.value"
                                                                clearable
                                                                label="Usuario"
                                                                hint="Buscar usuario por nombre o correo electrónico"
                                                                item-value="id"
                                                                v-model:search="searchQueryUser"
                                                                :item-title="customTitleUser"
                                                                :custom-filter="customFilterUser"
                                                                :disabled="!isCreating"
                                                            >
                                                                <template v-slot:item="{ props, item }">
                                                                    <v-list-item
                                                                        v-bind="props"
                                                                        :title="item.raw.full_name"
                                                                        :subtitle="item.raw.email"
                                                                    ></v-list-item>
                                                                </template>
                                                            </v-autocomplete>
                                                        </div>
                                                    </div>
                                                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Rol de cuenta
                                                            </p>
                                                            <v-select
                                                                label="Rol de cuenta"
                                                                hint="Selecciona el rol de cuenta"
                                                                :items="wallet_account_roles"
                                                                :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                item-value="id"
                                                                v-model="wallet.role_type_id.value.value"
                                                                :error-messages="wallet.role_type_id.errorMessage.value"
                                                                :disabled="!isCreating"
                                                                >
                                                            </v-select>
                                                            <InputError :message="dataFormWallet.errors.role_type_id" />
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Tipo de Cuenta
                                                            </p>
                                                            <v-select
                                                                label="Tipo de Cuenta"
                                                                hint="Selecciona el tipo de cuenta"
                                                                :items="wallet_account_types"
                                                                :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                                item-value="id"
                                                                v-model="wallet.wallet_account_type_id.value.value"
                                                                :error-messages="wallet.wallet_account_type_id.errorMessage.value"
                                                                :disabled="!isCreating"
                                                                >
                                                            </v-select>
                                                            <InputError :message="dataFormWallet.errors.wallet_account_type_id" />
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>Tipo de Moneda
                                                            </p>
                                                            <v-text-field
                                                                label="Moneda"
                                                                :model-value="walletCurrency"
                                                                readonly
                                                                disabled>
                                                            </v-text-field>
                                                        </div>
                                                    </div>

                                                    <div v-if="isCashless" class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>Nombre del Beneficio
                                                            </p>
                                                            <v-text-field
                                                                color="primary"
                                                                label="Nombre del Beneficio"
                                                                placeholder="Courtside"
                                                                hint="Ingresa el nombre del beneficio"
                                                                v-model="wallet.name.value.value"
                                                                :disabled="!isCreating"
                                                            >
                                                            </v-text-field>
                                                            <InputError :message="dataFormWallet.errors.name" />
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>Código del Beneficio
                                                            </p>
                                                            <v-text-field
                                                                color="primary"
                                                                label="Código del Beneficio"
                                                                placeholder="COTS"
                                                                hint="Ingresa el código del beneficio"
                                                                v-model="wallet.code.value.value"
                                                                :disabled="!isCreating"
                                                            >
                                                            </v-text-field>
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>Porcentaje de Cashback (%)
                                                            </p>
                                                            <v-text-field
                                                                color="primary"
                                                                label="Porcentaje de Cashback (%)"
                                                                placeholder="0%"
                                                                hint="Ingresa el Porcentaje de Cashback (%)"
                                                                v-model="wallet.percentage_cashback.value.value"
                                                                :disabled="!isCreating"
                                                            >
                                                            </v-text-field>
                                                        </div>
                                                    </div>
                                                    <div v-if="isCashless" class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                            <div class="tw-w-full">
                                                                <p class="tw-font-medium tw-mb-1">Descripción del beneficio</p>
                                                                <v-textarea color="primary" label="Descripción del beneficio" rows="2"
                                                                    variant="filled" auto-grow
                                                                    v-model="wallet.description.value.value"></v-textarea>
                                                            </div>
                                                    </div>
                                                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>
                                                                <v-badge content="?" @click="tooltip.account_number = !tooltip.account_number">
                                                                    <span class="tw-pe-3">
                                                                        Número de Cuenta
                                                                    </span>
                                                                    <v-tooltip activator="parent" location="end">
                                                                        El número de cuenta se generará automáticamente si dejas el campo vacío
                                                                    </v-tooltip>
                                                                </v-badge>
                                                            </p>
                                                            <v-text-field
                                                                color="primary"
                                                                label="Número de Cuenta"
                                                                placeholder="**********2024"
                                                                hint="Ingresa el número de cuenta"
                                                                v-model="wallet.account_number.value.value"
                                                                :error-messages="wallet.account_number.errorMessage.value"
                                                                :disabled="!isCreating"
                                                            >
                                                            </v-text-field>
                                                            <InputError :message="dataFormWallet.errors.account_number" />
                                                        </div>
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span>Saldo Inicial
                                                            </p>
                                                            <v-text-field
                                                                color="primary"
                                                                label="Saldo Inicial"
                                                                placeholder="$0.00"
                                                                hint="Ingresa el saldo inicial"
                                                                v-model="wallet.current_balance.value.value"
                                                                :error-messages="wallet.current_balance.errorMessage.value"
                                                                :disabled="!isCreating"
                                                            >
                                                            </v-text-field>
                                                            <InputError :message="dataFormWallet.errors.current_balance" />
                                                        </div>
                                                    </div>
                                                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500"></span> Fecha de expiración
                                                            </p>
                                                            <v-date-input
                                                                density="compact"
                                                                color="primary"
                                                                clearable
                                                                label="Fecha de expiración"
                                                                hint="Selecciona la fecha de expiración de la cuenta"
                                                                v-model="wallet.expiration_date.value.value"
                                                                :error-messages="wallet.expiration_date.errorMessage.value">
                                                            </v-date-input>
                                                            <InputError :message="dataFormWallet.errors.expiration_date" />
                                                        </div>

                                                    </div>
                                                    <div v-if="!isCreating" class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                        <div class="tw-w-full">
                                                            <p class="tw-font-medium tw-mb-1">
                                                                <span class="tw-text-red-500">*</span>Estatus
                                                            </p>
                                                            <v-switch
                                                                :label="`${wallet.is_active.value.value ? 'Activa' : 'Inactiva'}`"
                                                                color="indigo" inset
                                                                v-model="wallet.is_active.value.value">
                                                            </v-switch>
                                                        </div>
                                                    </div>
                                                </v-form>
                                            </v-tabs-window>
                                        </div>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="!tw-mb-4">
                                    <v-spacer></v-spacer>
                                    <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none" @click="closeFormWallet">
                                        Cancelar
                                    </v-btn>
                                    <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated" @click="saveDataWallet">
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.full_name="{ item }">
                    {{ formatTitleCase(item.user_information?.full_name )}}
                </template>
                <template v-slot:item.code="{ item }">
                    {{ item.user_information?.code }}
                </template>
                <template v-slot:item.email="{ item }">
                    {{ item.user_information?.email }}
                </template>
                <template v-slot:item.phone_number="{ item }">
                    {{ item.user_information?.email?.phone_number }}
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ formatDateForDisplay(item.created_at) }}
                </template>
                <template v-slot:expanded-row="{ columns, item }">
                    <td :colspan="headerWallet.length" class="tw-px-14">
                        <v-data-table :headers="headerWallet" :items="item.wallet_account_types" item-value="id" hide-default-footer items-per-page="-1">
                            <template v-slot:item.name="{ item: subItem  }">
                                {{ formatTitleCase(subItem.name )}}
                            </template>
                            <template v-slot:item.current_balance="{ item: subItem }">
                                {{ subItem.pivot.current_balance }}
                            </template>
                            <template v-slot:item.expiration_date="{ item: subItem }">
                                {{ subItem.pivot.expiration_date }}
                            </template>
                            <template v-slot:item.is_active="{ item: subItem }">
                                <v-chip :color="subItem.pivot.is_active ? 'green' : 'red'">
                                    {{ subItem.pivot.is_active ? 'Activa' : 'Inactiva' }}
                                </v-chip>
                            </template>
                            <template v-slot:item.actions="{ item: subItem }">
                                <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editWallet(item,subItem)">
                                    mdi-pencil
                                </v-icon>
                            </template>
                        </v-data-table>

                    </td>
                </template>

            </v-data-table>
        </div>
    </AppLayout>
</template>

<style scoped></style>
