<script setup>
import { ref, computed, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, useField } from 'vee-validate'
import { promotionSchema } from '@/validation/Administration/Offer/promotion-schema';
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import useStringFormat from '@/composables/stringFormat'

const { formatFirstLetterUppercase } = useStringFormat();

const props = defineProps({
    stadiums: { type: Array, required: true },
    promotion_types: { type: Array, required: true },
    promotions: { type: Array, required: true }
})

console.log(props.promotions)

const headersPromotions = [
    { title: 'Tipo Prom.', align: 'start', sortable: true, key: 'promotion_type.name' },
    { title: 'Nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'Desc.', align: 'start', sortable: true, key: 'description' },
    { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'Asientos Nec.', align: 'start', sortable: true, key: 'generic_seats_allowed' },
    { title: 'Asientos Reg.', align: 'start', sortable: true, key: 'promotional_seats_allowed' },
    { title: 'Prom. Permit.', align: 'start', sortable: true, key: 'maximun_promotions_allowed' },
    { title: '% Desc.', align: 'start', sortable: true, key: 'percent_allow' },
    { title: 'Aplica', align: 'start', sortable: true, key: 'is_active_online' },
    { title: 'Para', align: 'start', sortable: true, key: 'availability_sale' },
    { title: 'Acciones', key: 'actions', sortable: false }
];

const dialogFormPromotion = ref(false);
const editedPromotionIndex = ref(-1);

const formTitlePromotion = computed(() => editedPromotionIndex.value === -1 ? 'Nueva promoción' : 'Editar promoción');

const { handleSubmit, resetForm } = useForm({
    validationSchema: promotionSchema,
    initialValues: {
        id: null,
        is_active: false,
        is_active_online: null,
        generic_seats_allowed:null,
        promotional_seats_allowed:null,
        maximun_promotions_allowed:null,
        percent_allow:null,
        availability_sale: 1
    },
});

const promotion = {
    id: useField('id'),
    promotion_type_id: useField('promotion_type_id'),
    stadium_id: useField('stadium_id'),
    name: useField('name'),
    description: useField('description'),
    generic_seats_allowed: useField('generic_seats_allowed'),
    promotional_seats_allowed: useField('promotional_seats_allowed'),
    maximun_promotions_allowed: useField('maximun_promotions_allowed'),
    percent_allow: useField('percent_allow'),
    is_active_online: useField('is_active_online'),
    is_active: useField('is_active'),
    availability_sale: useField('availability_sale'),
}

const dataFormPromotion = useFormInertia({
    id: '',
    promotion_type_id: '',
    stadium_id: '',
    name: '',
    description: '',
    generic_seats_allowed: '',
    promotional_seats_allowed: '',
    maximun_promotions_allowed: '',
    percent_allow: '',
    is_active_online: '',
    is_active: '',
    availability_sale: 1
});

const saveDataPromotion = handleSubmit((dataForm) => {

    dataFormPromotion.id = dataForm.id;
    dataFormPromotion.promotion_type_id = dataForm.promotion_type_id;
    dataFormPromotion.stadium_id = dataForm.stadium_id;
    dataFormPromotion.name = dataForm.name;
    dataFormPromotion.description = dataForm.description;
    dataFormPromotion.generic_seats_allowed = dataForm.generic_seats_allowed;
    dataFormPromotion.promotional_seats_allowed = dataForm.promotional_seats_allowed;
    dataFormPromotion.maximun_promotions_allowed = dataForm.maximun_promotions_allowed;
    dataFormPromotion.percent_allow = dataForm.percent_allow;
    dataFormPromotion.is_active_online = dataForm.is_active_online;
    dataFormPromotion.is_active = dataForm.is_active;
    dataFormPromotion.availability_sale = dataForm.availability_sale;

    if (editedPromotionIndex.value > -1) {

        dataFormPromotion.put(route('promotions.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormPromotion();
            },
            onError: (errors) => { },
            onFinish: () => { }
        });

    } else {

        dataFormPromotion.post(route('promotions.store'), {
            onSuccess: (page) => {
                closeFormPromotion();
            },
            onError: (errors) => { },
            onFinish: () => { }
        });
    }
});

const resetFormPromotion = () => {
    resetForm();
    editedPromotionIndex.value = -1;
};

const closeFormPromotion = () => {
    dialogFormPromotion.value = false;
    resetFormPromotion();
};

const dialogDeletePromotion = ref(false);

const deletePromotion = (selectedPromotion) => {

    promotion.id.setValue(selectedPromotion.id);
    dialogDeletePromotion.value = true;
}

const deletePromotionConfirmation = () => {

    dataFormPromotion.delete(route('promotions.destroy', promotion.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationPromotion();
        },
        onError: (errors) => { },
        onFinish: () => { }
    });
}

const closeDeleteConfirmationPromotion = () => {
    dialogDeletePromotion.value = false;
    resetFormPromotion();
}

const editPromotion = (selectedPromotion) => {

    promotion.id.setValue(selectedPromotion.id);
    promotion.promotion_type_id.setValue(selectedPromotion.promotion_type_id);
    promotion.stadium_id.setValue(selectedPromotion.stadium_id);
    promotion.name.setValue(selectedPromotion.name);
    promotion.description.setValue(selectedPromotion.description);
    promotion.generic_seats_allowed.setValue(selectedPromotion.generic_seats_allowed);
    promotion.promotional_seats_allowed.setValue(selectedPromotion.promotional_seats_allowed);
    promotion.maximun_promotions_allowed.setValue(selectedPromotion.maximun_promotions_allowed);
    promotion.percent_allow.setValue(selectedPromotion.percent_allow);
    promotion.is_active.setValue(selectedPromotion.is_active ? true : false);
    promotion.is_active_online.setValue(selectedPromotion.is_active_online === null ? null : ( selectedPromotion.is_active_online ? true : false));
    promotion.availability_sale.setValue(selectedPromotion.availability_sale);

    editedPromotionIndex.value = props.promotions.indexOf(selectedPromotion);
    dialogFormPromotion.value = true;
}

const multiplePurchases = "descuento_por_compra_multiple";
const optionPromotionTypes = ref({
    multiple_purchases:false,
    percentage_discount:false
});

watch(promotion.promotion_type_id.value, (id) => {

    const promotion_type_temp = props.promotion_types.find((promotion_type) => promotion_type.id == id);

    if (promotion_type_temp != undefined) {
        if (promotion_type_temp.name === multiplePurchases) {

            optionPromotionTypes.value.multiple_purchases = true;
            optionPromotionTypes.value.percentage_discount = false;

            promotion.percent_allow.resetField();

        }else{
            optionPromotionTypes.value.percentage_discount = true;
            optionPromotionTypes.value.multiple_purchases = false;

            promotion.generic_seats_allowed.resetField();
            promotion.promotional_seats_allowed.resetField();
            promotion.maximun_promotions_allowed.resetField();
        }
    }else{

        optionPromotionTypes.value.percentage_discount = false;
        optionPromotionTypes.value.multiple_purchases = false;

        promotion.generic_seats_allowed.resetField();
        promotion.promotional_seats_allowed.resetField();
        promotion.maximun_promotions_allowed.resetField();
        promotion.percent_allow.resetField();
    }
});


</script>

<template>
    <Head title="Administracion" />
    <SuccessSession />
    <AppLayout>
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de promociones</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-data-table :headers="headersPromotions" :items="promotions">
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title class="tw-uppercase">Promociones</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogFormPromotion" max-width="800px">
                            <template v-slot:activator="{ props }">
                                <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"
                                    v-bind="props">
                                    Nueva promoción
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">{{ formTitlePromotion }}</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                                            <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Estadio</p>
                                                        <v-select label="Estadio" :items="stadiums"
                                                            :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                            item-value="id" v-model="promotion.stadium_id.value.value"
                                                            :error-messages="promotion.stadium_id.errorMessage.value"></v-select>
                                                        <InputError :message="dataFormPromotion.errors.stadium_id" />
                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Tipo de promoción
                                                        </p>
                                                        <v-select label="Tipo de promoción" :items="promotion_types"
                                                            :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                            item-value="id"
                                                            v-model="promotion.promotion_type_id.value.value"
                                                            :error-messages="promotion.promotion_type_id.errorMessage.value"></v-select>
                                                        <InputError
                                                            :message="dataFormPromotion.errors.promotion_type_id" />
                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Nombre</p>
                                                        <v-text-field color="primary" label="Nombre"
                                                            placeholder=" 2 por 1"
                                                            hint="ingresa el nombre de la promotion"
                                                            v-model="promotion.name.value.value"
                                                            :error-messages="promotion.name.errorMessage.value"></v-text-field>
                                                        <InputError :message="dataFormPromotion.errors.name" />
                                                    </div>
                                                </div>
                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Descripción</p>
                                                        <v-textarea color="primary" label="Descripción" rows="3"
                                                            variant="filled" auto-grow
                                                            v-model="promotion.description.value.value"
                                                            :error-messages="promotion.description.errorMessage.value"></v-textarea>
                                                        <InputError :message="dataFormPromotion.errors.description" />
                                                    </div>
                                                </div>
                                                <div v-if="optionPromotionTypes.multiple_purchases"
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Asientos Nec.</p>
                                                        <v-text-field color="primary" label="Asientos Nec."
                                                            placeholder="1" hint="Asientos necesarios "
                                                            v-model="promotion.generic_seats_allowed.value.value"
                                                            :error-messages="promotion.generic_seats_allowed.errorMessage.value"></v-text-field>
                                                        <InputError
                                                            :message="dataFormPromotion.errors.generic_seats_allowed" />
                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Asientos Reg.</p>
                                                        <v-text-field color="primary" label="Asientos Reg"
                                                            placeholder="1" hint="Asientos regalados "
                                                            v-model="promotion.promotional_seats_allowed.value.value"
                                                            :error-messages="promotion.promotional_seats_allowed.errorMessage.value"></v-text-field>
                                                        <InputError
                                                            :message="dataFormPromotion.errors.promotional_seats_allowed" />
                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Promociones permit.
                                                        </p>
                                                        <v-text-field color="primary" label="Promociones permit."
                                                            placeholder="1" hint="Promociones permitidas"
                                                            v-model="promotion.maximun_promotions_allowed.value.value"
                                                            :error-messages="promotion.maximun_promotions_allowed.errorMessage.value"></v-text-field>
                                                        <InputError
                                                            :message="dataFormPromotion.errors.maximun_promotions_allowed" />
                                                    </div>
                                                </div>
                                                <div v-if="optionPromotionTypes.percentage_discount"
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> % Desc.</p>
                                                        <v-text-field color="primary" label="% Desc." placeholder="1"
                                                            hint="Porcentaje de descuento" suffix="%"
                                                            v-model="promotion.percent_allow.value.value"
                                                            :error-messages="promotion.percent_allow.errorMessage.value"></v-text-field>
                                                        <InputError :message="dataFormPromotion.errors.percent_allow" />
                                                    </div>
                                                </div>
                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Aplica
                                                        </p>
                                                        <v-radio-group v-model="promotion.is_active_online.value.value"
                                                            inline>
                                                            <v-radio label="Web y Mobil" :value="null"></v-radio>
                                                            <v-radio label="Web" :value="true"></v-radio>
                                                            <v-radio label="Mobil" :value="false"></v-radio>
                                                        </v-radio-group>

                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Para
                                                        </p>
                                                        <v-radio-group v-model="promotion.availability_sale.value.value"
                                                            inline>
                                                            <v-radio label="Taquilla" :value="1"></v-radio>
                                                            <v-radio label="En linea" :value="2"></v-radio>
                                                            <v-radio label="Ambas" :value="3"></v-radio>
                                                        </v-radio-group>

                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Estatus
                                                        </p>
                                                        <v-switch
                                                            :label="`${promotion.is_active.value.value ? 'Activa' : 'Inactiva'}`"
                                                            color="indigo" inset
                                                            v-model="promotion.is_active.value.value"></v-switch>
                                                    </div>
                                                </div>
                                            </v-form>
                                        </div>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="!tw-mb-4">
                                    <v-spacer></v-spacer>
                                    <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none"
                                        @click="closeFormPromotion">
                                        Cancelar
                                    </v-btn>
                                    <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated"
                                        @click="saveDataPromotion">
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDeletePromotion" max-width="500px">
                            <v-card>
                                <v-card-title class="">¿Esta seguro de eliminar esta promoción?</v-card-title>
                                <v-card-actions class="!tw-my-2">
                                    <v-spacer></v-spacer>
                                    <v-btn @click="closeDeleteConfirmationPromotion" color="red" rounded="xl"
                                        class="!tw-px-4 text-none" variant="tonal">
                                        Cancelar
                                    </v-btn>
                                    <v-btn @click="deletePromotionConfirmation" color="purple" rounded="xl"
                                        class="!tw-px-4 text-none" variant="elevated">
                                        Eliminar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.name="{ item }">
                    {{ formatFirstLetterUppercase(item.name) }}
                </template>
                <template v-slot:item.description="{ item }">
                    {{ formatFirstLetterUppercase(item.description) }}
                </template>
                <template v-slot:item.promotion_type.name="{ item }">
                    {{ formatFirstLetterUppercase(item.promotion_type.name) }}
                </template>
                <template v-slot:item.percent_allow="{ item }">
                    {{ item.percent_allow ? (item.percent_allow + ' %') : ''  }}
                </template>
                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'">
                        {{ item.is_active ? 'Activa' : 'Inactiva' }}
                    </v-chip>
                </template>
                <template v-slot:item.is_active_online="{ item }">
                    <v-chip :color="item.is_active_online == null ? 'primary' : ( item.is_active_online ? 'Secondary' : 'green' )">
                        {{ item.is_active_online == null ?  "Web y Mobil" : ( item.is_active_online ? 'Web' : 'Mobil' )}}
                    </v-chip>
                </template>
                <template v-slot:item.availability_sale="{ item }">
                    <v-chip :color="item.availability_sale == 1 ? 'primary' : ( item.availability_sale == 2 ? 'Secondary' : 'green' )">
                        {{ item.availability_sale == 1 ?  "Taquilla" : ( item.availability_sale == 2 ? 'En linea' : 'Ambas' )}}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editPromotion(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon class="!tw-text-red-600" size="small" @click="deletePromotion(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </div>
    </AppLayout>
</template>
<style scoped></style>
