<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, useField } from 'vee-validate'
import { agreementSchema } from '@/validation/Administration/Offer/agreement-schema';
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import useStringFormat from '@/composables/stringFormat';

const { formatFirstLetterUppercase } = useStringFormat();

const props = defineProps({
    institutions: { type: Array, required: true },
    global_seasons: { type: Array, required: true },
    agreements: { type: Array, required: true },
    promotions: { type: Array, required: true }
})

const headersAgreement = [
    { title: 'Temporada', align: 'start', sortable: true, key: 'global_season.name' },
    { title: 'Nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'Descripción', align: 'start', sortable: true, key: 'description' },
    { title: 'Inicio', align: 'start', sortable: true, key: 'start_date' },
    { title: 'Fin', align: 'start', sortable: true, key: 'end_date' },
    { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'Acciones', key: 'actions', sortable: false }
];

const agreementGroupBy = [
    {
        key: 'institution.name',
        order: 'asc',
    }
];

const dialogFormAgreement = ref(false);
const editedAgreementIndex = ref(-1);

const formTitleAgreement = computed(() => editedAgreementIndex.value === -1 ? 'Nuevo Convenio' : 'Editar Convenio');

const { handleSubmit, resetForm } = useForm({
    validationSchema: agreementSchema,
    initialValues: {
        id: null,
        is_active: true
    },
});

const agreement = {
    id: useField('id'),
    institution_id: useField('institution_id'),
    global_season_id: useField('global_season_id'),
    name: useField('name'),
    description: useField('description'),
    start_date: useField('start_date'),
    end_date: useField('end_date'),
    is_active: useField('is_active'),
    promotions: useField('promotions')
}

const dataFormAgreement = useFormInertia({
    id: '',
    institution_id: '',
    global_season_id: '',
    name: '',
    description: '',
    start_date: '',
    end_date: '',
    is_active: '',
    promotions: '',
});

const saveDataAgreement = handleSubmit((dataForm) => {
    dataFormAgreement.id = dataForm.id;
    dataFormAgreement.institution_id = dataForm.institution_id;
    dataFormAgreement.global_season_id = dataForm.global_season_id;
    dataFormAgreement.name = dataForm.name;
    dataFormAgreement.description = dataForm.description;
    dataFormAgreement.start_date = dataForm.start_date;
    dataFormAgreement.end_date = dataForm.end_date;
    dataFormAgreement.is_active = dataForm.is_active;
    dataFormAgreement.promotions = dataForm.promotions;

    if (editedAgreementIndex.value > -1) {

        dataFormAgreement.put(route('agreements.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormAgreement();
            },
            onError: (errors) => { },
            onFinish: () => { }
        });

    } else {

        dataFormAgreement.post(route('agreements.store'), {
            onSuccess: (page) => {
                closeFormAgreement();
            },
            onError: (errors) => { },
            onFinish: () => { }
        });
    }
});

const resetFormAgreement = () => {
    resetForm();
    editedAgreementIndex.value = -1;
};

const closeFormAgreement = () => {
    dialogFormAgreement.value = false;
    resetFormAgreement();
};

const dialogDeleteAgreement = ref(false);

const deleteAgreement = (selectedAgreement) => {

    agreement.id.setValue(selectedAgreement.id);
    dialogDeleteAgreement.value = true;
}

const deleteAgreementConfirmation = () => {

    dataFormAgreement.delete(route('agreements.destroy', agreement.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationSerie();
        },
        onError: (errors) => { },
        onFinish: () => { }
    });
}

const closeDeleteConfirmationSerie = () => {
    dialogDeleteAgreement.value = false;
    resetFormAgreement();
}

const editAgreement = (selectedAgreement) => {
    agreement.id.setValue(selectedAgreement.id);
    agreement.institution_id.setValue(selectedAgreement.institution_id);
    agreement.global_season_id.setValue(selectedAgreement.global_season_id);
    agreement.name.setValue(selectedAgreement.name);
    agreement.description.setValue(selectedAgreement.description);
    agreement.start_date.setValue(new Date(selectedAgreement.start_date));
    agreement.end_date.setValue(new Date(selectedAgreement.end_date));
    agreement.is_active.setValue(selectedAgreement.is_active ? true : false);
    agreement.promotions.setValue(selectedAgreement.promotions.map((value) => value.id));

    editedAgreementIndex.value = props.agreements.indexOf(selectedAgreement);
    dialogFormAgreement.value = true;
}

</script>

<template>

    <Head title="Administracion" />
    <SuccessSession />
    <AppLayout>
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de Convenios</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-data-table :group-by="agreementGroupBy" :headers="headersAgreement" :items="agreements"
                item-value="name">
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title class="tw-uppercase">Convenios</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogFormAgreement" max-width="800px">
                            <template v-slot:activator="{ props }">
                                <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"
                                    v-bind="props">
                                    Nuevo Convenio
                                </v-btn>
                            </template>
                            <v-card>

                                <v-card-title>
                                    <span class="text-h5">{{ formTitleAgreement }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                                            <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span>Institución</p>
                                                        <v-select label="Institución" :items="institutions"
                                                            :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                            item-value="id"
                                                            v-model="agreement.institution_id.value.value"
                                                            :error-messages="agreement.institution_id.errorMessage.value"></v-select>
                                                        <InputError
                                                            :message="dataFormAgreement.errors.institution_id" />
                                                    </div>

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span>Temporada</p>
                                                        <v-select label="Temporada" :items="global_seasons"
                                                            :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                            item-value="id"
                                                            v-model="agreement.global_season_id.value.value"
                                                            :error-messages="agreement.global_season_id.errorMessage.value"></v-select>
                                                        <InputError
                                                            :message="dataFormAgreement.errors.global_season_id" />
                                                    </div>

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span>Nombre</p>
                                                        <v-text-field color="primary" label="Nombre"
                                                            placeholder="Convenio 1"
                                                            hint="Ingresa el nombre del convenio"
                                                            v-model="agreement.name.value.value"
                                                            :error-messages="agreement.name.errorMessage.value"></v-text-field>
                                                        <InputError :message="dataFormAgreement.errors.name" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Descripción</p>
                                                        <v-textarea color="primary" label="Descripción" rows="3"
                                                            variant="filled" auto-grow
                                                            v-model="agreement.description.value.value"
                                                            :error-messages="agreement.description.errorMessage.value"></v-textarea>
                                                        <InputError :message="dataFormAgreement.errors.description" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span>Fecha de inicio
                                                        </p>
                                                        <v-date-input density="compact" color="primary" clearable
                                                            label="Fecha de inicio" hint="Selecciona tu fecha de inicio"
                                                            v-model="agreement.start_date.value.value"
                                                            :error-messages="agreement.start_date.errorMessage.value"></v-date-input>
                                                        <InputError :message="dataFormAgreement.errors.start_date" />
                                                    </div>

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Fecha de
                                                            terminación
                                                        </p>
                                                        <v-date-input density="compact" color="primary" clearable
                                                            label="Fecha de terminación"
                                                            hint="Selecciona tu fecha de terminación"
                                                            v-model="agreement.end_date.value.value"
                                                            :error-messages="agreement.end_date.errorMessage.value"></v-date-input>
                                                        <InputError :message="dataFormAgreement.errors.end_date" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <v-select v-model="agreement.promotions.value.value"
                                                        :items="promotions"
                                                        :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                        hint="Seleccione las promociones" label="Promoción" multiple
                                                        item-value="id" persistent-hint chips>
                                                    </v-select>
                                                </div>
                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1">
                                                            <span class="tw-text-red-500">*</span>Estatus
                                                        </p>
                                                        <v-switch
                                                            :label="`${agreement.is_active.value.value ? 'Activa' : 'Inactiva'}`"
                                                            color="indigo" inset
                                                            v-model="agreement.is_active.value.value"></v-switch>
                                                    </div>
                                                </div>
                                            </v-form>
                                        </div>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="!tw-mb-4">
                                    <v-spacer></v-spacer>
                                    <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none"
                                        @click="closeFormAgreement">
                                        Cancelar
                                    </v-btn>
                                    <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated"
                                        @click="saveDataAgreement">
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDeleteAgreement" max-width="500px">
                            <v-card>
                                <v-card-title class="">¿Esta seguro de eliminar este convenio?</v-card-title>
                                <v-card-actions class="!tw-my-2">
                                    <v-spacer></v-spacer>
                                    <v-btn @click="closeDeleteConfirmationSerie" color="red" rounded="xl"
                                        class="!tw-px-4 text-none" variant="tonal">
                                        Cancelar
                                    </v-btn>
                                    <v-btn @click="deleteAgreementConfirmation" color="purple" rounded="xl"
                                        class="!tw-px-4 text-none" variant="elevated">
                                        Eliminar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>

                <template v-slot:group-header="{ item, columns, toggleGroup, isGroupOpen }">
                    <tr>
                        <td :colspan="columns.length">
                            <VBtn :icon="isGroupOpen(item) ? '$expand' : '$next'" size="small" variant="text"
                                @click="toggleGroup(item)"></VBtn>
                            Institución {{ formatFirstLetterUppercase(item.value) }}
                        </td>
                    </tr>
                </template>

                <template v-slot:item.name="{ item }">
                    {{ formatFirstLetterUppercase(item.name) }}
                </template>
                <template v-slot:item.description="{ item }">
                    {{ formatFirstLetterUppercase(item.description) }}
                </template>
                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'">
                        {{ item.is_active ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editAgreement(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon class="!tw-text-red-600" size="small" @click="deleteAgreement(item)">
                        mdi-delete
                    </v-icon>
                </template>

            </v-data-table>
        </div>
    </AppLayout>



</template>

<style scoped></style>
