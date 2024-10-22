<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import { computed, ref, watch } from 'vue';
import { eventSchema } from '@/validation/Administration/Event/event-schema';
import { useForm, useField } from 'vee-validate'
import { Head, useForm as useFormInertia } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';

const props = defineProps({
    series: { type: Array, required: true },
    global_seasons: { type: Array, required: true },
    event_types: { type: Array, required: true },
    events_for_type: { type: Array, required: true }
})


const tabEvent = ref(null);

const headersEvent = [
    { title: 'nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'descripción', align: 'start', sortable: true, key: 'description' },
    { title: 'inicio', align: 'start', sortable: true, key: 'start_date' },
    { title: 'fin', align: 'start', sortable: true, key: 'end_date' },
    { title: 'estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'acciones', key: 'actions', sortable: false }
];

const dialogFormEvent = ref(false);
const editedIndexEvent = ref(-1);

const formTitleEvent = computed(() => editedIndexEvent.value === -1 ? 'nuevo evento' : 'editar evento');

const { handleSubmit, resetForm } = useForm({
    validationSchema: eventSchema,
    initialValues: {
        id: null,
        is_active: false,
    },
});

const event = {
    id: useField('id'),
    event_type_id: useField('event_type_id'),
    serie_id: useField('serie_id'),
    global_image: useField('global_image'),
    name: useField('name'),
    slug: useField('slug'),
    description: useField('description'),
    start_date: useField('start_date'),
    end_date: useField('end_date'),
    is_active: useField('is_active'),
}

const dataEvent = useFormInertia({
    id: '',
    event_type_id: '',
    serie_id: '',
    global_image: '',
    name: '',
    slug: '',
    description: '',
    start_date: '',
    end_date: '',
    is_active: '',
});

watch(tabEvent, (value) => {
    event.event_type_id.setValue(value);
});

const imageUrlEvent = ref(null);

const onFileChangeEvent = (fileChange) => {
    const file = fileChange.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrlEvent.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onFileClearEvent = () => {
    imageUrlEvent.value = null;
};

const saveDataEvent = handleSubmit((dataForm) => {

    dataEvent.id = dataForm.id;
    dataEvent.event_type_id = dataForm.event_type_id;
    dataEvent.serie_id = dataForm.serie_id;
    dataEvent.global_image = dataForm.global_image;
    dataEvent.name = dataForm.name;
    dataEvent.slug = dataForm.slug;
    dataEvent.description = dataForm.description;
    dataEvent.start_date = dataForm.start_date;
    dataEvent.end_date = dataForm.end_date;
    dataEvent.is_active = dataForm.is_active;

    if (editedIndexEvent.value > -1) {

        dataEvent.put(route('event.management.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormEvent();
            },
            onError: (errors) => {},
            onFinish: () => {}
        });

    } else {

        dataEvent.post(route('event.management.store'), {
            onSuccess: (page) => {
                closeFormEvent();
            },
            onError: (errors) => {},
            onFinish: () => {}
        });
    }
});

const resetFormEvent = () => {

    resetForm();

    onFileClearEvent();

    event.event_type_id.setValue(tabEvent.value);

    editedIndexEvent.value = -1;
};

const closeFormEvent = () => {
    dialogFormEvent.value = false;
    resetFormEvent();
};

const dialogDeleteEvent = ref(false);

const deleteEvent = (selectedEvent) => {

    event.id.setValue(selectedEvent.id);

    dialogDeleteEvent.value = true;
}

const deleteEventConfirmation = () => {

    dataEvent.delete(route('event.management.destroy',event.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationEvent();
        },
        onError: (errors) => {},
        onFinish: () => {}
    });
}

const closeDeleteConfirmationEvent = () => {
    dialogDeleteEvent.value = false;
    resetFormEvent();
}

const editEvent = (selectedEvent) => {

    event.id.setValue(selectedEvent.id);
    event.event_type_id.setValue(selectedEvent.event_type_id);
    event.serie_id.setValue(selectedEvent.serie_id);
    event.global_image.setValue(selectedEvent.global_image);
    event.name.setValue(selectedEvent.name);
    event.description.setValue(selectedEvent.description);
    event.slug.setValue(selectedEvent.slug);
    event.start_date.setValue(selectedEvent.start_date);
    event.end_date.setValue(selectedEvent.end_date);

    event.is_active.setValue(selectedEvent.is_active ? true : false);

    if (selectedEvent.global_image) {

        imageUrlEvent.value = `/storage/${selectedEvent.global_image.file_path}`;
    }

    for (let i = 0; i < props.events_for_type.length; i++) {
        const event_types_aux = props.events_for_type[i];

        editedIndexEvent.value = event_types_aux.events.indexOf(selectedEvent);

        if (editedIndexEvent.value > -1) {
            break;
        }
    }

    dialogFormEvent.value = true;
}

</script>

<template>
    <Head title="Administracion" />
    <AppLayout>
        <SuccessSession />
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Aministración de eventos</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-tabs v-model="tabEvent" align-tabs="center" color="deep-purple-accent-4">
                    <v-tab v-for="event_for_type in events_for_type" :key="event_for_type.event_type_id" :value="event_for_type.event_type_id"> {{ event_for_type.event_type }}</v-tab>
                </v-tabs>
                <v-tabs-window v-model="tabEvent">
                    <v-tabs-window-item v-for="event_for_type in events_for_type" :key="event_for_type.event_type_id" :value="event_for_type.event_type_id">
                        <v-data-table :headers="headersEvent" :items="event_for_type.events">
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>eventos</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialogFormEvent" max-width="800px">
                                        <template v-slot:activator="{ props }">
                                            <v-btn variant="tonal" class="mb-2 !tw-mr-5 text-none" color="purple" rounded="xl"  v-bind="props">
                                                nuevo evento
                                            </v-btn>
                                        </template>
                                        <v-card>

                                            <v-card-title>
                                                <span class="text-h5">{{ formTitleEvent }}</span>
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
                                                                        <v-img v-if="imageUrlEvent" :src="imageUrlEvent" alt="Preview"
                                                                            max-width="100" rounded="lg"></v-img>
                                                                    </div>
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> imagen de
                                                                        portada
                                                                    </p>
                                                                    <v-file-input accept="image/*" color="primary" clearable
                                                                        label="imagen de portada"
                                                                        hint="selecciona la imagen de portada"
                                                                        prepend-icon="mdi-camera" variant="filled"
                                                                        @change="onFileChangeEvent" @click:clear="onFileClearEvent"
                                                                        v-model="event.global_image.value.value"
                                                                        :error-messages="event.global_image.errorMessage.value"></v-file-input>
                                                                    <InputError :message="dataEvent.errors.global_image" />
                                                                </div>
                                                            </div>


                                                            <div
                                                                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span>serie</p>
                                                                    <v-select label="serie" :items="series"
                                                                        item-title="name" item-value="id"
                                                                        v-model="event.serie_id.value.value"
                                                                        :error-messages="event.serie_id.errorMessage.value"></v-select>
                                                                    <InputError :message="dataEvent.errors.serie_id" />
                                                                </div>

                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> nombre</p>
                                                                    <v-text-field color="primary" label="nombre"
                                                                        placeholder="evento 1"
                                                                        hint="ingresa el nombre del evento"
                                                                        v-model="event.name.value.value"
                                                                        :error-messages="event.name.errorMessage.value"></v-text-field>
                                                                    <InputError :message="dataEvent.errors.name" />
                                                                </div>

                                                            </div>


                                                            <div
                                                                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">

                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> slug</p>
                                                                    <v-text-field color="primary" label="slug"
                                                                        placeholder="slug 1"
                                                                        hint="ingresa el slug del evento"
                                                                        v-model="event.slug.value.value"
                                                                        :error-messages="event.slug.errorMessage.value"></v-text-field>
                                                                    <InputError :message="dataEvent.errors.slug" />
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> description</p>
                                                                    <v-textarea color="primary" label="descripción" rows="3"
                                                                        variant="filled" auto-grow
                                                                        v-model="event.description.value.value"
                                                                        :error-messages="event.description.errorMessage.value"></v-textarea>
                                                                    <InputError :message="dataEvent.errors.description" />
                                                                </div>

                                                            </div>


                                                            <div
                                                                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">

                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> fecha de inicio
                                                                    </p>
                                                                    <v-date-input density="compact" color="primary"
                                                                        clearable label="fecha de inicio"
                                                                        hint="selecciona tu fecha de inicio"
                                                                        v-model="event.start_date.value.value"
                                                                        :error-messages="event.start_date.errorMessage.value"></v-date-input>
                                                                    <InputError :message="dataEvent.errors.start_date" />
                                                                </div>

                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> fecha de
                                                                        terminación
                                                                    </p>
                                                                    <v-date-input density="compact" color="primary"
                                                                        clearable label="fecha de terminación"
                                                                        hint="selecciona tu fecha de terminación"
                                                                        v-model="event.end_date.value.value"
                                                                        :error-messages="event.end_date.errorMessage.value"></v-date-input>
                                                                    <InputError :message="dataEvent.errors.end_date" />
                                                                </div>

                                                            </div>


                                                            <div
                                                                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                                <div class="tw-w-full">
                                                                    <p class="tw-font-medium tw-mb-1"><span
                                                                            class="tw-text-red-500">*</span> estatus
                                                                    </p>
                                                                    <v-switch
                                                                        :label="`${event.is_active.value.value ? 'Activo' : 'Inactivo'}`"
                                                                        color="indigo" inset
                                                                        v-model="event.is_active.value.value"></v-switch>
                                                                </div>
                                                            </div>


                                                        </v-form>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions class="!tw-mb-4">
                                                <v-spacer></v-spacer>
                                                <v-btn color="red" variant="tonal" rounded="xl" class="!tw-px-4 text-none" @click="closeFormEvent">
                                                    cancelar
                                                </v-btn>
                                                <v-btn color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated" @click="saveDataEvent">
                                                    guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="dialogDeleteEvent" max-width="500px">
                                        <v-card>
                                            <v-card-title class="">¿estas seguro de eliminar este evento?</v-card-title>
                                            <v-card-actions class="!tw-my-2">
                                                <v-spacer></v-spacer>
                                                <v-btn  @click="closeDeleteConfirmationEvent" color="red" rounded="xl" class="!tw-px-4 text-none" variant="tonal">
                                                    Cancelar
                                                </v-btn>
                                                <v-btn  @@click="deleteEventConfirmation" color="purple" rounded="xl" class="!tw-px-4 text-none" variant="elevated">
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
                                <v-icon class="me-2 !tw-text-purple-500" size="small" @click="editEvent(item)">
                                    mdi-pencil
                                </v-icon>
                                <v-icon class="!tw-text-red-600" size="small" @click="deleteEvent(item)">
                                    mdi-delete
                                </v-icon>
                            </template>
                        </v-data-table>


                    </v-tabs-window-item>
            </v-tabs-window>
        </div>

    </AppLayout>

</template>
<style scoped></style>
