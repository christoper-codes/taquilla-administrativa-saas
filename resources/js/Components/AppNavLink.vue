<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'text-none !tw-h-[40px] !tw-w-full !tw-text-white !tw-bg-slate-200/10 !tw-justify-start'
        : 'text-none !tw-h-[40px] !tw-w-full !tw-text-gray-300  !tw-bg-transparent !tw-justify-start'
);
</script>

<template>
    <Link :href="href">
        <v-btn variant="text" :class="classes" rounded="xl">
            <slot />
        </v-btn>
    </Link>
</template>
