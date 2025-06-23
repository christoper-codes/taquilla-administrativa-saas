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

const sortedEvents = computed(() =>
    props.events.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
);
const loadingEventId = ref(null);
const showEvent = (eventId) => {
    loadingEventId.value = eventId;
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
            <span>Resumen por partido</span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-w-full tw-block tw-overflow-hidden tw-mt-10">
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-7">
                <div v-for="event in sortedEvents" :key="event.id" class="tw-shadow-xl tw-w-full tw-p-5 tw-rounded-3xl tw-bg-white">
                    <div class="tw-h-48 tw-w-full tw-rounded-2xl tw-overflow-hidden tw-relative">
                        <img :src="`/storage/${event.global_image.file_path}`" class="tw-w-full tw-h-full tw-object-cover" alt="Event Image">
                    </div>
                    <div class="tw-mt-3">
                        <h3 class="tw-text-lg tw-font-bold tw-mt-3">{{ event.name }}</h3>
                        <p class="tw-text-sm">{{ dateFormat(event.start_date) }}</p>
                    </div>
                    <Link :href="route('indicators.show', { slug: event.slug, id: event.id } )" @click="showEvent(event.id)" class="mt-3 tw-block">
                        <PrimaryButton heightbtn="!tw-h-[60px]" paddingbtn="!tw-w-full !tw-block" :loading="loadingEventId === event.id">
                            <span>Ver indicadores</span>
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
