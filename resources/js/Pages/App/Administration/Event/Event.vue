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
import EventSeatCatalogPromotion from '@/Components/../Pages/App/Administration/Offer/EventSeatCatalogPromotion.vue';
import EventSeatCatalogPrices from '@/Components/../Pages/App/Administration/Offer/EventSeatCatalogPrices.vue';
import EventSeatCatalogStatus from '@/Components/../Pages/App/Administration/Offer/EventSeatCatalogStatus.vue';
import useStringFormat from '@/composables/stringFormat';
import { VTimePicker } from 'vuetify/labs/VTimePicker';
import useDateFormat from '@/composables/dateFormat';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';

const { formatDateForDataInput, formatHourForTimePicker, combineDateTimeForDatabase } = useDateFormat();
const { formatFirstLetterUppercase } = useStringFormat();

const props = defineProps({
    series: { type: Array, required: true },
    global_seasons: { type: Array, required: true },
    event_types: { type: Array, required: true },
    events_for_type: { type: Array, required: true },
    events_visibility_types : { type: Array, required: true },
})

const tabEvent = ref(null);
const headersEvent = [
    { title: 'Temporada', align: 'start', sortable: true, key: 'global_season.name' },
    { title: 'Nombre', align: 'start', sortable: true, key: 'name' },
    { title: 'Descripción', align: 'start', sortable: true, key: 'description' },
    { title: 'Fecha/Hora Inicio', align: 'start', sortable: true, key: 'start_date' },
    { title: 'Fecha/Hora Finalización', align: 'start', sortable: true, key: 'end_date' },
    { title: 'Estatus', align: 'start', sortable: true, key: 'is_active' },
    { title: 'Precio de Asientos', key: 'seatPrices', sortable: false },
    { title: 'Promociones', key: 'promotion', sortable: false },
    { title: 'Bloqueo/Reserva', key: 'block_reserve', sortable: false },
    { title: 'Acciones', key: 'actions', sortable: false }
];

const dialogFormEvent = ref(false);
const editedIndexEvent = ref(-1);
const formTitleEvent = computed(() => editedIndexEvent.value === -1 ? 'Nuevo evento' : 'Editar evento');
const { handleSubmit, resetForm } = useForm({
    validationSchema: eventSchema,
    initialValues: {
        id: null,
        is_active: true,
    },
});

const event = {
    id: useField('id'),
    global_season_id: useField('global_season_id'),
    event_type_id: useField('event_type_id'),
    serie_id: useField('serie_id'),
    global_image: useField('global_image'),
    name: useField('name'),
    event_visibility_type_id: useField('event_visibility_type_id'),
    description: useField('description'),
    start_date: useField('start_date'),
    start_time: useField('start_time'),
    start_time_picker: ref(false),
    end_date: useField('end_date'),
    end_time: useField('end_time'),
    end_time_picker: ref(false),
    is_active: useField('is_active'),
}

const dataEvent = useFormInertia({
    id: '',
    global_season_id: '',
    event_type_id: '',
    serie_id: '',
    global_image: '',
    name: '',
    event_visibility_type_id: '',
    description: '',
    start_date: '',
    start_time: '',
    end_date: '',
    end_time: '',
    is_active: '',
});

watch(tabEvent, (value) => {
    event.event_type_id.setValue(value);
});

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

const imageUrlEvent = ref(null);
const isLoading = ref(false);

const saveDataEvent = handleSubmit((dataForm) => {
    isLoading.value = true;
    dataEvent.id = dataForm.id;
    dataEvent.global_season_id = dataForm.global_season_id;
    dataEvent.event_type_id = dataForm.event_type_id;
    dataEvent.serie_id = dataForm.serie_id;
    dataEvent.global_image = dataForm.global_image;
    dataEvent.name = dataForm.name;
    dataEvent.event_visibility_type_id = dataForm.event_visibility_type_id;
    dataEvent.description = dataForm.description;
    dataEvent.start_date = combineDateTimeForDatabase(dataForm.start_date, dataForm.start_time);
    dataEvent.end_date = combineDateTimeForDatabase(dataForm.end_date,dataForm.end_time);
    dataEvent.is_active = dataForm.is_active;

    if (editedIndexEvent.value > -1) {
        dataEvent.post(route('event.management.update', dataForm.id), {
            onSuccess: (page) => {
                closeFormEvent();
            },
            onError: (errors) => { },
            onFinish: () => {
                isLoading.value = false;
            }
        });
    } else {
        dataEvent.post(route('event.management.store'), {
            onSuccess: (page) => {
                closeFormEvent();
            },
            onError: (errors) => { },
            onFinish: () => {
                isLoading.value = false;
            }
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
    event.name.setValue(selectedEvent.name);
    event.description.setValue(selectedEvent.description);
    dialogDeleteEvent.value = true;
}

const deleteEventConfirmation = () => {
    dataEvent.delete(route('event.management.destroy', event.id.value.value), {
        onSuccess: (page) => {
            closeDeleteConfirmationEvent();
        },
        onError: (errors) => { },
        onFinish: () => { }
    });
}

const closeDeleteConfirmationEvent = () => {
    dialogDeleteEvent.value = false;
    resetFormEvent();
}

const editEvent = (selectedEvent) => {
    event.id.setValue(selectedEvent.id);
    event.global_season_id.setValue(selectedEvent.global_season_id);
    event.event_type_id.setValue(selectedEvent.event_type_id);
    event.serie_id.setValue(selectedEvent.serie_id);
    event.name.setValue(selectedEvent.name);
    event.event_visibility_type_id.setValue(selectedEvent.event_visibility_type_id);
    event.description.setValue(selectedEvent.description);
    event.start_date.setValue(formatDateForDataInput(selectedEvent.start_date));
    event.start_time.setValue(formatHourForTimePicker(selectedEvent.start_date));
    event.end_date.setValue(formatDateForDataInput(selectedEvent.end_date));
    event.end_time.setValue(formatHourForTimePicker(selectedEvent.end_date));
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

const eventSelected = ref(null);
const dialogSeatPrices = ref(false);
const openDialogSeatPrices = (event, open) => {
    eventSelected.value = event;
    dialogSeatPrices.value = open;
}
const dialogPromotion = ref(false);
const openDialogPromotion = (event, open) => {
    eventSelected.value = event;
    dialogPromotion.value = open;
}

const dialogBlockAndReserve = ref(false);
const openDialogBlockAndReserve = (event, open) => {
    eventSelected.value = event;
    dialogBlockAndReserve.value = open;
}
</script>

<template>
    <Head title="Administracion" />
    <AppLayout>
        <SuccessSession />
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Administración de eventos</span>
        </BreadcrumbAppSecondary>
        <div class="tw-px-4 tw-py-10 lg:tw-p-10">
            <v-tabs v-model="tabEvent" align-tabs="center" color="deep-purple-accent-4">
                <v-tab v-for="event_for_type in events_for_type" :key="event_for_type.event_type_id"
                    :value="event_for_type.event_type_id"> {{ event_for_type.event_type }}</v-tab>
            </v-tabs>
            <v-tabs-window v-model="tabEvent">
                <v-tabs-window-item v-for="event_for_type in events_for_type" :key="event_for_type.event_type_id"
                    :value="event_for_type.event_type_id">
                    <v-data-table :headers="headersEvent" :items="event_for_type.events" class="!tw-rounded-xl">
                        <template v-slot:top>
                            <v-toolbar flat class="!tw-p-5 !tw-rounded-xl !tw-shadow-lg !tw-mb-7">
                                <v-toolbar-title>Eventos</v-toolbar-title>
                                <v-dialog v-model="dialogFormEvent" max-width="800px">
                                    <template v-slot:activator="{ props }">
                                        <PrimaryButton v-bind="props" heightbtn="!tw-h-[60px]">
                                            <span>Nuevo evento</span>
                                        </PrimaryButton>
                                    </template>
                                    <v-card>
                                        <v-card-text>
                                            <v-container>
                                                <div class="tw-flex tw-flex-col tw-gap-3">
                                                    <v-form class="tw-flex tw-flex-col tw-gap-1">
                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                                                            <div class="tw-w-full tw-mb-7">
<!--                                                                 <v-file-input accept="image/*" color="primary" clearable
                                                                    label="Imagen de portada"
                                                                    hint="Selecciona la imagen de portada"
                                                                    prepend-icon="mdi-camera" variant="filled"
                                                                    @change="onFileChangeEvent"
                                                                    @click:clear="onFileClearEvent"
                                                                    v-model="event.global_image.value.value"
                                                                    :error-messages="event.global_image.errorMessage.value"></v-file-input> -->
                                                                    <v-file-upload
                                                                        v-model="event.global_image.value.value"
                                                                        :error-messages="event.global_image.errorMessage.value"
                                                                        density="compact" variant="compact"
                                                                        title="" @change="onFileChangeEvent">
                                                                    </v-file-upload>
                                                                    <!-- <div class="tw-mx-auto tw-flex tw-items-center tw-justify-center">
                                                                        <v-img v-if="imageUrlEvent" :src="imageUrlEvent" alt="Preview" max-width="100" rounded="lg"></v-img>
                                                                    </div> -->
                                                                <InputError :message="event.global_image.errorMessage.value" />
                                                            </div>
                                                        </div>

                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                            <div class="tw-w-full">
                                                                <v-select label="Serie" :items="series" variant="outlined"
                                                                    item-title="name" item-value="id"
                                                                    v-model="event.serie_id.value.value"
                                                                    :error-messages="event.serie_id.errorMessage.value"></v-select>
                                                                <InputError :message="dataEvent.errors.serie_id" />
                                                            </div>

                                                            <div class="tw-w-full">
                                                                <v-select label="Temporada" :items="global_seasons" variant="outlined"
                                                                    item-title="name" item-value="id"
                                                                    v-model="event.global_season_id.value.value"
                                                                    :error-messages="event.global_season_id.errorMessage.value"></v-select>
                                                                <InputError :message="dataEvent.errors.global_season_id" />
                                                            </div>

                                                        </div>
                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                            <div class="tw-w-full">
                                                                <v-text-field color="primary" label="Nombre" variant="outlined"
                                                                    placeholder="Evento 1"
                                                                    hint="Ingresa el nombre del evento"
                                                                    v-model="event.name.value.value"
                                                                    :error-messages="event.name.errorMessage.value"></v-text-field>
                                                                <InputError :message="dataEvent.errors.name" />
                                                            </div>
                                                            <div class="tw-w-full">
                                                                <v-select
                                                                    color="purple"
                                                                    label="Visivilidad de evento"
                                                                    :items="events_visibility_types.map(item => ({ title: item.name, value: item.id }))"
                                                                    variant="outlined"
                                                                    v-model="event.event_visibility_type_id.value.value"
                                                                    :error-messages="event.event_visibility_type_id.errorMessage.value"
                                                                ></v-select>
                                                                <InputError :message="dataEvent.errors.event_visibility_type_id" />
                                                            </div>
                                                        </div>
                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                            <div class="tw-w-full">
                                                                <v-textarea color="primary" label="Descripción" rows="3"
                                                                    variant="outlined" auto-grow
                                                                    v-model="event.description.value.value"
                                                                    :error-messages="event.description.errorMessage.value"></v-textarea>
                                                                <InputError :message="dataEvent.errors.description" />
                                                            </div>
                                                        </div>
                                                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                            <div class="tw-w-full">
                                                                <v-date-input density="compact" color="primary" clearable variant="outlined"
                                                                    label="Fecha de inicio"
                                                                    v-model="event.start_date.value.value"
                                                                    :error-messages="event.start_date.errorMessage.value"></v-date-input>
                                                                <InputError :message="dataEvent.errors.start_date" />
                                                            </div>
                                                            <div class="tw-w-full">
                                                                <v-text-field v-model="event.start_time.value.value" variant="outlined"
                                                                    :active="event.start_time_picker.value"
                                                                    label="Hora de inicio"
                                                                    prepend-icon="mdi-clock-time-four-outline"
                                                                    :error-messages="event.start_time.errorMessage.value"
                                                                    readonly>
                                                                    <v-menu v-model="event.start_time_picker.value"
                                                                        :close-on-content-click="false" activator="parent"
                                                                        transition="scale-transition">
                                                                        <v-time-picker v-if="event.start_time_picker.value"
                                                                            v-model="event.start_time.value.value" format="24hr"
                                                                            full-width scrollable>
                                                                            <template v-slot:title>
                                                                                Hora de inicio
                                                                            </template>
                                                                        </v-time-picker>
                                                                    </v-menu>
                                                                </v-text-field>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-mb-2">
                                                            <div class="tw-w-full">
                                                                <v-date-input density="compact" color="primary" clearable variant="outlined"
                                                                    label="Fecha de finalización"
                                                                    v-model="event.end_date.value.value"
                                                                    :error-messages="event.end_date.errorMessage.value"></v-date-input>
                                                                <InputError :message="dataEvent.errors.end_date" />
                                                            </div>
                                                            <div class="tw-w-full">
                                                                <v-text-field v-model="event.end_time.value.value" variant="outlined"
                                                                    :active="event.end_time_picker.value"
                                                                    label="Hora de finalización"
                                                                    prepend-icon="mdi-clock-time-four-outline"
                                                                    :error-messages="event.end_time.errorMessage.value" readonly>
                                                                    <v-menu v-model="event.end_time_picker.value"
                                                                        :close-on-content-click="false" activator="parent"
                                                                        transition="scale-transition">
                                                                        <v-time-picker v-if="event.end_time_picker.value"
                                                                            v-model="event.end_time.value.value" format="24hr"
                                                                            full-width scrollable>
                                                                            <template v-slot:title>
                                                                                Hora de finalización
                                                                            </template>
                                                                        </v-time-picker>
                                                                    </v-menu>
                                                                </v-text-field>
                                                            </div>
                                                        </div>
                                                        <div v-if="editedIndexEvent != -1"
                                                            class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5 tw-my-2">
                                                            <div class="tw-w-full">
                                                                <p class="tw-font-medium tw-mb-1"><span
                                                                        class="tw-text-red-500">*</span> Estatus
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
                                            <SecondaryButton @click="closeFormEvent" heightbtn="!tw-h-[60px]">
                                                <span>Cancelar</span>
                                            </SecondaryButton>
                                            <PrimaryButton @click="saveDataEvent" :loading="isLoading" heightbtn="!tw-h-[60px]">
                                                <span>Guardar</span>
                                            </PrimaryButton>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                                <v-dialog v-model="dialogDeleteEvent" max-width="500px">
                                    <v-card>
                                        <v-card-title class="">
                                            <div class="tw-grid tw-justify-items-center tw-gap-y-6">
                                                <div class="tw-pt-6">
                                                    ¿Estas seguro de eliminar este evento?
                                                </div>
                                                <div>
                                                    <div class="tw-grid tw-justify-items-center tw-gap-y-2">
                                                        <div>
                                                            {{ formatFirstLetterUppercase(event.name.value.value) }}
                                                        </div>
                                                        <div>
                                                            {{ formatFirstLetterUppercase(event.description.value.value) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </v-card-title>
                                        <v-card-actions class="!tw-my-2">
                                            <v-spacer></v-spacer>
                                            <v-btn @click="closeDeleteConfirmationEvent" color="red" rounded="xl"
                                                class="!tw-px-4 text-none" variant="tonal">
                                                Cancelar
                                            </v-btn>
                                            <v-btn @click="deleteEventConfirmation" color="purple" rounded="xl"
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
                        <template v-slot:item.is_active="{ item }">
                            <v-chip :color="item.is_active ? 'green' : 'red'">
                                {{ item.is_active ? 'Activa' : 'Inactiva' }}
                            </v-chip>
                        </template>
                        <template v-slot:item.seatPrices="{ item }">
                            <v-btn @click="openDialogSeatPrices(item, true)">
                                <span class="material-symbols-outlined tw-text-lg">finance_chip</span>
                            </v-btn>
                        </template>
                        <template v-slot:item.promotion="{ item }">
                            <v-btn @click="openDialogPromotion(item, true)">
                                <span class="material-symbols-outlined tw-text-lg">featured_seasonal_and_gifts</span>
                            </v-btn>
                        </template>
                        <template v-slot:item.block_reserve="{ item }">
                            <v-btn @click="openDialogBlockAndReserve(item, true)">
                                <span class="material-symbols-outlined tw-text-lg">detector_status</span>
                            </v-btn>
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

        <v-dialog v-model="dialogSeatPrices" transition="dialog-bottom-transition" fullscreen>
            <v-card>

                <v-toolbar>
                    <v-toolbar-title class="tw-w-full">Asignación de Precios</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon="mdi-close" @click="openDialogSeatPrices(eventSelected, false)">
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <div class="tw-overflow-y-auto tw-w-full tw-h-full">
                    <EventSeatCatalogPrices :event="eventSelected" />
                </div>

            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogPromotion" transition="dialog-bottom-transition" fullscreen>
            <v-card>

                <v-toolbar>
                    <v-toolbar-title class="tw-w-full">Asignación de Promociones</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon="mdi-close" @click="openDialogPromotion(eventSelected, false)">
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <div class="tw-overflow-y-auto tw-w-full tw-h-full">
                    <EventSeatCatalogPromotion :event="eventSelected" />
                </div>

            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogBlockAndReserve" transition="dialog-bottom-transition" fullscreen>
            <v-card>
                <v-toolbar>
                    <v-toolbar-title class="tw-w-full">Asignación de Estus de Asientos</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon="mdi-close" @click="openDialogBlockAndReserve(eventSelected, false)">
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <div class="tw-overflow-y-auto tw-w-full tw-h-full">
                    <EventSeatCatalogStatus :event="eventSelected" />
                </div>
            </v-card>
        </v-dialog>



    </AppLayout>

</template>
