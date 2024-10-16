<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { drawerNavState } from '@/composables/drawersStates';

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
        ? 'text-none !tw-h-[40px] lg:!tw-rounded-none !tw-rounded-full lg:!tw-h-[80px] !tw-w-full !tw-justify-start lg:!tw-justify-center lg:!tw-w-24 !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-purple-400'
        : 'text-none !tw-h-[40px] lg:!tw-rounded-none !tw-rounded-full lg:!tw-h-[80px] !tw-w-full !tw-justify-start lg:!tw-justify-center lg:!tw-w-24 !tw-text-gray-600 !tw-bg-transparent'
);

const toggloeDrawerNavState = () => {
    if(window.innerWidth < 700) {
        drawerNavState.value = !drawerNavState.value;
    }
};

</script>

<template>
    <Link :href="href">
        <v-btn variant="text" :class="classes" @click="toggloeDrawerNavState">
            <slot />
        </v-btn>
    </Link>
</template>
