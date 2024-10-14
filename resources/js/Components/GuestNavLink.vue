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
        ? 'text-none !tw-h-[80px] !tw-w-24 !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-purple-400'
        : 'text-none !tw-h-[80px] !tw-w-24 !tw-text-gray-600 !tw-bg-transparent'
);
</script>

<template>
    <Link :href="href">
        <v-btn variant="text" :class="classes" rounded="0">
            <slot />
        </v-btn>
    </Link>
</template>
