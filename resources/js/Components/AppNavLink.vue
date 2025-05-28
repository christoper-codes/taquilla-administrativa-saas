<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { draweAppNavState } from '@/composables/drawersStates';

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
        ? 'text-none !tw-h-[50px] !tw-rounded-2xl !tw-w-full !tw-bg-gradient-to-r !tw-from-primary !tw-to-cyan-500 !tw-text-white !tw-justify-start !tw-flex !tw-items-center'
        : 'text-none !tw-h-[50px] !tw-rounded-2xl !tw-w-full !tw-bg-transparent !tw-justify-start !tw-flex !tw-items-center'
);

const toggledraweAppNavState = () => {
    if(window.innerWidth < 700) {
        draweAppNavState.value = !draweAppNavState.value;
    }
}

</script>

<template>
    <Link :href="href">
        <v-btn variant="text" :class="classes" @click="toggledraweAppNavState">
            <slot />
        </v-btn>
    </Link>
</template>
