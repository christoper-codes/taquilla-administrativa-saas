<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import EventsIndex from '@/Components/IndicatorsCharts/EventsIndex.vue';
import useDateFormat from '@/composables/dateFormat';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/buttons/SecondaryButton.vue';
import axios from 'axios';
import { toast } from 'vue3-toastify'

const { dateFormat } = useDateFormat();

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
    "events": {
        Type: Array,
        Required: true
    },
})

const events = ref(props.events);
const loadingEventId = ref(null);
const showEvent = (eventId) => {
    loadingEventId.value = eventId;

    axios.post(route('events.release.reserved.seats', eventId))
        .then((response) => {
            if(response.data.success) {
                toast(response.data.message, {
                    "theme": "auto",
                    "type": "success",
                    "dangerouslyHTMLString": true
                })
                events.value = response.data.data;
            }
        })
        .catch((error) => {
            toast(error.data.message, {
                "theme": "auto",
                "type": "error",
                "autoClose": 10000,
                "dangerouslyHTMLString": true
            })
        })
        .finally(() => {
           loadingEventId.value = null;
        });
};

const globalPayementTypeProps = (item) => {
  return {
    title: item.name,
    subtitle: dateFormat(item.start_date),
  };
};
</script>

<template>

    <Head title="indicadores"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Transito por evento</span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-w-full tw-block tw-overflow-hidden tw-mt-10 tw-pb-10">
            <div v-if="events.length > 0" class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-7">
                <div v-for="event in events" :key="event.id" class="tw-shadow-xl tw-w-full tw-p-5 tw-rounded-3xl tw-bg-white">
                    <div class="tw-h-48 tw-w-full tw-rounded-2xl tw-overflow-hidden tw-relative">
                        <img :src="`/storage/${event.global_image.file_path}`" class="tw-w-full tw-h-full tw-object-cover" alt="Event Image">
                    </div>
                    <div class="tw-mt-3">
                        <h3 class="tw-text-lg tw-font-bold tw-mt-3">{{ event.name }}</h3>
                        <p class="tw-text-sm">{{ dateFormat(event.start_date) }}</p>
                        <p class="tw-text-sm tw-font-bold tw-text-primary">{{ event.traffic_seats_count }} Asientos en tránsito</p>
                    </div>
                    <div @click="showEvent(event.id)" class="mt-3 tw-block">
                        <SecondaryButton heightbtn="!tw-h-[60px]" paddingbtn="!tw-w-full !tw-block" :loading="loadingEventId === event.id">
                            <span>Liberar</span>
                        </SecondaryButton>
                    </div>
                    <p class="tw-text-xs tw-mt-3 tw-text-center tw-text-red-500">Seran liberados asientos reservados hace mas de 20 minutos</p>
                </div>
            </div>
            <div v-else class="tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-5 tw-mt-10">
                <div class="tw-text-center tw-flex tw-items-center tw-justify-center tw-flex-col tw-gap-5">
                    <img class="tw-w-40 lg:tw-w-72 tw-h-auto" src="/storage/public/empty-cart.webp" alt="Webiste image">
                    <span>¡No hay eventos con asientos en transito!</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
