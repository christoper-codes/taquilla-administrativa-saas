<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, useField } from 'vee-validate'
import { institutionSchema } from '@/validation/Administration/Offer/institution-schema';
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import useStringFormat from '@/composables/stringFormat';

const { formatFirstLetterUppercase }  = useStringFormat();

const props = defineProps({
    institutions: { type: Array, required: true },
    stadiums: { type: Array, required: true },
})

const headersInstitutions = [
    { title: 'Estadio', align: 'start', sortable: true, key: 'stadium.name' },
    { title: 'Nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'Descripción', align: 'start', sortable: true, key: 'description' },
    { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'Acciones', key: 'actions', sortable: false }
];



const dialogFormInstitution = ref(false);
const editedInstitutionIndex = ref(-1);

const formTitleInstitution = computed(() => editedInstitutionIndex.value === -1 ? 'Nueva Institución' : 'Editar Institución');

const { handleSubmit, resetForm } = useForm({
    validationSchema: institutionSchema,
    initialValues: {
        id: null,
        is_active: true
    },
});

const institution = {
    id: useField('id'),
    stadium_id: useField('stadium_id'),
    global_image: useField('global_image'),
    name: useField('name'),
    description: useField('description'),
    is_active: useField('is_active')
}

const dataFormInstitution = useFormInertia({
    id: '',
    stadium_id: '',
    global_image: '',
    name: '',
    description: '',
    is_active: '',
});

const imageUrlInstitution = ref(null);

const onFileChangeInstitution = (fileChange) => {
    const file = fileChange.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrlInstitution.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onFileClearInstitution = () => {
    imageUrlInstitution.value = null;
};

const saveDataInstitution = handleSubmit((dataForm) => {

    dataFormInstitution.id = dataForm.id;
    dataFormInstitution.stadium_id = dataForm.stadium_id;
    dataFormInstitution.global_image = dataForm.global_image;
    dataFormInstitution.name = dataForm.name;
    dataFormInstitution.description = dataForm.description;
    dataFormInstitution.is_active = dataForm.is_active

    if (editedInstitutionIndex.value > -1) {

        console.log(dataFormInstitution);

        dataFormInstitution.post(route('institutions.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormInstitution();
            },
            onError: (errors) => {
            },
            onFinish: () => { }
        });

    } else {

        dataFormInstitution.post(route('institutions.store'), {
            onSuccess: (page) => {
                closeFormInstitution();
            },
            onError: (errors) => { },
            onFinish: () => { }
        });
    }
});

const resetFormInstitution = () => {
    resetForm();
    editedInstitutionIndex.value = -1;
};

const closeFormInstitution = () => {
    dialogFormInstitution.value = false;
    resetFormInstitution();
};

const dialogDeleteInstitution = ref(false);

const deleteInstitution = (selectedInstitution) => {

    institution.id.setValue(selectedInstitution.id);
    dialogDeleteInstitution.value = true;
}

const deleteInstitutionConfirmation = () => {

    dataFormInstitution.delete(route('institutions.destroy', institution.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationInstitution();
        },
        onError: (errors) => { },
        onFinish: () => { }
    });
}

const closeDeleteConfirmationInstitution = () => {
    dialogDeleteInstitution.value = false;
    resetFormInstitution();
}

const editInstitution = (selectedInstitution) => {
    institution.id.setValue(selectedInstitution.id);
    institution.stadium_id.setValue(selectedInstitution.stadium_id);
    institution.name.setValue(selectedInstitution.name);
    institution.description.setValue(selectedInstitution.description);
    institution.is_active.setValue(selectedInstitution.is_active ? true : false);

    if (selectedInstitution.global_image) {

        imageUrlInstitution.value = `/storage/${selectedInstitution.global_image.file_path}`;
    }

    editedInstitutionIndex.value = props.institutions.indexOf(selectedInstitution);
    dialogFormInstitution.value = true;
}

</script>

<template>

    <Head title="Administracion" />

    <SuccessSession />
    <AppLayout>
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de Instituciones</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-data-table :headers="headersInstitutions" :items="institutions">
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Instituciones</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogFormInstitution" max-width="800px">
                            <template v-slot:activator="{ props }">
                                <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"
                                    v-bind="props">
                                    Nueva Institución
                                </v-btn>
                            </template>
                            <v-card>

                                <v-card-title>
                                    <span class="text-h5">{{ formTitleInstitution }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                                            <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                                                    <div class="tw-w-full">
                                                        <div
                                                            class="tw-mx-auto tw-flex tw-items-center tw-justify-center">
                                                            <v-img v-if="imageUrlInstitution" :src="imageUrlInstitution"
                                                                alt="Preview" max-width="100" rounded="lg"></v-img>
                                                        </div>
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> imagen de
                                                            portada
                                                        </p>
                                                        <v-file-input accept="image/*" color="primary" clearable
                                                            label="imagen de portada"
                                                            hint="selecciona la imagen de portada"
                                                            prepend-icon="mdi-camera" variant="filled"
                                                            @change="onFileChangeInstitution" @click:clear="onFileClearInstitution"
                                                            v-model="institution.global_image.value.value"
                                                            :error-messages="institution.global_image.errorMessage.value"></v-file-input>
                                                        <InputError :message="dataFormInstitution.errors.global_image" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Estadio</p>
                                                        <v-select label="Estadio" :items="stadiums"
                                                            :item-title="(item) => formatFirstLetterUppercase(item.name)"
                                                            item-value="id" v-model="institution.stadium_id.value.value"
                                                            :error-messages="institution.stadium_id.errorMessage.value"></v-select>
                                                        <InputError :message="dataFormInstitution.errors.stadium_id" />
                                                    </div>

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Nombre</p>
                                                        <v-text-field color="primary" label="Nombre"
                                                            placeholder="Institución 1"
                                                            hint="Ingresa el nombre de la institución"
                                                            v-model="institution.name.value.value"
                                                            :error-messages="institution.name.errorMessage.value"></v-text-field>
                                                        <InputError :message="dataFormInstitution.errors.name" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Descripción</p>
                                                        <v-textarea color="primary" label="Descripción" rows="3"
                                                            variant="filled" auto-grow
                                                            v-model="institution.description.value.value"
                                                            :error-messages="institution.description.errorMessage.value"></v-textarea>
                                                        <InputError :message="dataFormInstitution.errors.description" />
                                                    </div>

                                                </div>

                                                <div
                                                    class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                    <div class="tw-w-full">
                                                        <p class="tw-font-medium tw-mb-1"><span
                                                                class="tw-text-red-500">*</span> Estatus
                                                        </p>
                                                        <v-switch
                                                            :label="`${institution.is_active.value.value ? 'Activa' : 'Inactiva'}`"
                                                            color="indigo" inset
                                                            v-model="institution.is_active.value.value"></v-switch>
                                                    </div>

                                                </div>

                                            </v-form>
                                        </div>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="!tw-mb-4">
                                    <v-spacer></v-spacer>
                                    <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none"
                                        @click="closeFormInstitution">
                                        Cancelar
                                    </v-btn>
                                    <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated"
                                        @click="saveDataInstitution">
                                        Guardar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDeleteInstitution" max-width="500px">
                            <v-card>
                                <v-card-title class="">¿Esta seguro de eliminar esta institución?</v-card-title>
                                <v-card-actions class="!tw-my-2">
                                    <v-spacer></v-spacer>
                                    <v-btn @click="closeDeleteConfirmationInstitution" color="red" rounded="xl"
                                        class="!tw-px-4 text-none" variant="tonal">
                                        Cancelar
                                    </v-btn>
                                    <v-btn @click="deleteInstitutionConfirmation" color="purple" rounded="xl"
                                        class="!tw-px-4 text-none" variant="elevated">
                                        Eliminar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.stadium.name="{ item }">
                    {{ formatFirstLetterUppercase(item.stadium.name) }}
                </template>
                <template v-slot:item.name="{ item }">
                    {{ formatFirstLetterUppercase(item.name) }}
                </template>
                <template v-slot:item.description="{ item }">
                    {{ formatFirstLetterUppercase(item.description) }}
                </template>
                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'">
                        {{ item.is_active ? 'Activa' : 'Inactiva' }}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editInstitution(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon class="!tw-text-red-600" size="small" @click="deleteInstitution(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </div>


    </AppLayout>



</template>

<style scoped></style>
