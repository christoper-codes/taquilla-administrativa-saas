<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, useField } from 'vee-validate'
import { serieSchema } from '@/validation/Administration/Event/serie-schema';
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import useStringFormat from '@/composables/stringFormat';

const { formatFirstLetterUppercase } = useStringFormat();

const props = defineProps({
    series: { type: Array, required: true },
    global_seasons: { type: Array, required: true }
})

const headersSerie = [
    { title: 'Nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'Descripción', align: 'start', sortable: true, key: 'description' },
    { title: 'Fecha Inicio', align: 'start', sortable: true, key: 'start_date' },
    { title: 'Fecha Finalización', align: 'start', sortable: true, key: 'end_date' },
    { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'Acciones', key: 'actions', sortable: false }
];

const dialogFormSerie = ref(false);
const editedSerieIndex = ref(-1);

const formTitleSerie = computed(() => editedSerieIndex.value === -1 ? 'Nueva serie' : 'Editar serie');

const { handleSubmit, resetForm } = useForm({
    validationSchema: serieSchema,
    initialValues: {
        id: null,
        is_active: true
    },
});

const serie = {
    id: useField('id'),
    global_season_id: useField('global_season_id'),
    name: useField('name'),
    description: useField('description'),
    start_date: useField('start_date'),
    end_date: useField('end_date'),
    is_active: useField('is_active')
}

const dataFormSerie = useFormInertia({
    id: '',
    global_season_id: '',
    name: '',
    description: '',
    start_date: '',
    end_date: '',
    is_active: '',
});

const saveDataSerie = handleSubmit((dataForm) => {

    dataFormSerie.id = dataForm.id;
    dataFormSerie.global_season_id = dataForm.global_season_id;
    dataFormSerie.name = dataForm.name;
    dataFormSerie.description = dataForm.description;
    dataFormSerie.start_date =  dataForm.start_date;
    dataFormSerie.end_date = dataForm.end_date;
    dataFormSerie.is_active = dataForm.is_active;

    if (editedSerieIndex.value > -1) {

        dataFormSerie.put(route('series.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormSerie();
            },
            onError: (errors) => {},
            onFinish: () => {}
        });

    } else {

        dataFormSerie.post(route('series.store'), {
            onSuccess: (page) => {
                closeFormSerie();
            },
            onError: (errors) => {},
            onFinish: () => {}
        });
    }
});

const resetFormSerie = () => {
    resetForm();
    editedSerieIndex.value = -1;
};

const closeFormSerie = () => {
    dialogFormSerie.value = false;
    resetFormSerie();
};

const dialogDeleteSerie = ref(false);

const deleteSerie = (selectedSerie) => {

    serie.id.setValue(selectedSerie.id);
    serie.name.setValue(selectedSerie.name);
    serie.description.setValue(selectedSerie.description);
    dialogDeleteSerie.value = true;
}

const deleteSerieConfirmation = () => {

    dataFormSerie.delete(route('series.destroy', serie.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationSerie();
        },
        onError: (errors) => {},
        onFinish: () => {}
    });
}

const closeDeleteConfirmationSerie = () => {
    dialogDeleteSerie.value = false;
    resetFormSerie();
}

const editSerie = (selectedSerie) => {

    serie.id.setValue(selectedSerie.id);
    serie.global_season_id.setValue(selectedSerie.global_season_id);
    serie.name.setValue(selectedSerie.name);
    serie.description.setValue(selectedSerie.description);
    serie.start_date.setValue(new Date(selectedSerie.start_date));
    serie.end_date.setValue(new Date(selectedSerie.end_date));
    serie.is_active.setValue(selectedSerie.is_active ? true : false);

    editedSerieIndex.value = props.series.indexOf(selectedSerie);
    dialogFormSerie.value = true;
}

</script>

<template>

    <Head title="Administracion" />
    <SuccessSession />
    <AppLayout>
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de series</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-data-table :headers="headersSerie" :items="series">
                <template v-slot:top>
                    <v-toolbar flat>

                        <v-toolbar-title class="tw-uppercase">Series</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogFormSerie" max-width="800px">
                            <template v-slot:activator="{ props }">
                                <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"
                                    v-bind="props">
                                    Nueva serie
                                </v-btn>
                            </template>
                            <v-card>

                                <v-card-title>
                                    <span class="text-h5">{{ formTitleSerie }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                                            <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Temporada</p>
                                                        <v-select label="Temporada" :items="global_seasons"
                                                            item-title="name" item-value="id"
                                                            v-model="serie.global_season_id.value.value"
                                                            :error-messages="serie.global_season_id.errorMessage.value"></v-select>
                                                        <InputError :message="dataFormSerie.errors.global_season_id" />
                                                    </div>

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Nombre</p>
                                                        <v-text-field color="primary" label="Nombre"
                                                            placeholder="serie 1" hint="Ingresa el nombre de la serie"
                                                            v-model="serie.name.value.value"
                                                            :error-messages="serie.name.errorMessage.value"></v-text-field>
                                                        <InputError :message="dataFormSerie.errors.name" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Descripción</p>
                                                        <v-textarea color="primary" label="Descripción" rows="3"
                                                            variant="filled" auto-grow
                                                            v-model="serie.description.value.value"
                                                            :error-messages="serie.description.errorMessage.value"></v-textarea>
                                                        <InputError :message="dataFormSerie.errors.description" />
                                                    </div>
                                                </div>

                                                <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1">
                                                            <span class="tw-text-red-500">*</span> Fecha de
                                                            inicio
                                                        </p>
                                                        <v-date-input density="compact" color="primary" clearable
                                                            label="Fecha" hint="Selecciona la fecha"
                                                            v-model="serie.start_date.value.value"
                                                            :error-messages="serie.start_date.errorMessage.value"></v-date-input>
                                                        <InputError :message="dataFormSerie.errors.start_date" />
                                                    </div>
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1">
                                                            <span class="tw-text-red-500">*</span> Fecha de
                                                            finalización
                                                        </p>
                                                        <v-date-input density="compact" color="primary" clearable
                                                            label="Fecha" hint="Selecciona la fecha"
                                                            v-model="serie.end_date.value.value"
                                                            :error-messages="serie.end_date.errorMessage.value"></v-date-input>
                                                        <InputError :message="dataFormSerie.errors.end_date" />
                                                    </div>
                                                </div>
                                                <div v-if="editedSerieIndex !== -1"
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Estatus </p>
                                                        <v-switch
                                                            :label="`${serie.is_active.value.value ? 'Activa' : 'Inactiva'}`"
                                                            color="indigo" inset
                                                            v-model="serie.is_active.value.value"></v-switch>
                                                    </div>
                                                </div>

                                            </v-form>
                                        </div>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="!tw-mb-4">
                                    <v-spacer></v-spacer>
                                    <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none"
                                        @click="closeFormSerie">
                                        Cancelar
                                    </v-btn>
                                    <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated"
                                        @click="saveDataSerie">
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDeleteSerie" max-width="500px">
                            <v-card>
                                <v-card-title>
                                    <div class="tw-grid tw-justify-items-center tw-gap-y-6">
                                        <div class="tw-pt-6">
                                            ¿Esta seguro de eliminar la serie?
                                        </div>
                                        <div>
                                            <div class="tw-grid tw-justify-items-center tw-gap-y-2">
                                                <div>
                                                    {{ formatFirstLetterUppercase(serie.name.value.value) }}
                                                </div>
                                                <div>
                                                    {{ formatFirstLetterUppercase(serie.description.value.value) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </v-card-title>
                                <v-card-actions class="tw-my-4">
                                    <v-spacer></v-spacer>
                                    <v-btn @click="closeDeleteConfirmationSerie" color="red" rounded="xl"
                                        class="!tw-px-4 text-none" variant="tonal">
                                        Cancelar
                                    </v-btn>
                                    <v-btn @click="deleteSerieConfirmation" color="purple" rounded="xl"
                                        class="!tw-px-4 text-none" variant="elevated">
                                        Eliminar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'">
                        {{ item.is_active ? 'Activa' : 'Inactiva' }}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editSerie(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon class="!tw-text-red-600" size="small" @click="deleteSerie(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </div>


    </AppLayout>



</template>

<style scoped></style>
