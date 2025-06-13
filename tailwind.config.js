import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    prefix: 'tw-',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                bebas: ['Bebas Neue', ...defaultTheme.fontFamily.sans],
                metal: ['Metal Mania', ...defaultTheme.fontFamily.sans],
            },
            colors: {

                'primary': '#9333ea',
                'secondary': '#06b6d4',

                /*
                * |--------------------------------------------------------------------------
                * | Colors variabels | primary, secondary
                * |--------------------------------------------------------------------------
                * | Companies | colors
                */

                /*
                * Global primary colors
                */
                'tw-primary-950': 'var(--tw-primary-950)',
                'tw-primary-900': 'var(--tw-primary-900)',
                'tw-primary-800': 'var(--tw-primary-800)',
                'tw-primary-700': 'var(--tw-primary-700)',
                'tw-primary-600': 'var(--tw-primary-600)',
                'tw-primary-500': 'var(--tw-primary-500)',
                'tw-primary-400': 'var(--tw-primary-400)',
                'tw-primary-300': 'var(--tw-primary-300)',
                'tw-primary-200': 'var(--tw-primary-200)',
                'tw-primary-100': 'var(--tw-primary-100)',
                'tw-primary-50': 'var(--tw-primary-50)',
                /*
                * end Global primary colors
                */

                /*
                * Global secondary colors
                */
                'tw-secondary-950': 'var(--tw-secondary-950)',
                'tw-secondary-900': 'var(--tw-secondary-900)',
                'tw-secondary-800': 'var(--tw-secondary-800)',
                'tw-secondary-700': 'var(--tw-secondary-700)',
                'tw-secondary-600': 'var(--tw-secondary-600)',
                'tw-secondary-500': 'var(--tw-secondary-500)',
                'tw-secondary-400': 'var(--tw-secondary-400)',
                'tw-secondary-300': 'var(--tw-secondary-300)',
                'tw-secondary-200': 'var(--tw-secondary-200)',
                'tw-secondary-100': 'var(--tw-secondary-100)',
                'tw-secondary-50': 'var(--tw-secondary-50)',
                /*
                * end Global secondary colors
                */

                /*
                * |--------------------------------------------------------------------------
                * | Colors variabels | primary, secondary
                * |--------------------------------------------------------------------------
                * | Global | colors
                */

                /*
                * text Primary
                */
                'tw-text-primary-700': '#374151',
                'tw-text-primary-600': '#4b5563',
                'tw-text-primary-500': '#6b7280',
                'tw-text-primary-400': '#9ca3af',
                'tw-text-primary-300': '#d1d5db',
                'tw-text-primary-200': '#e5e7eb',
                'tw-text-primary-100': '#f3f4f6',
                'tw-text-primary-50': '#f9fafb',
                /*
                * end text Primary
                */

                /*
                * success color Primary
                */
                'tw-success-primary-700': '#15803d',
                'tw-success-primary-600': '#16a34a',
                'tw-success-primary-500': '#22c55e',
                'tw-success-primary-400': '#4ade80',
                'tw-success-primary-300': '#86efac',
                'tw-success-primary-200': '#bbf7d0',
                'tw-success-primary-100': '#dcfce7',
                /*
                * end success color Primary
                */

                /*
                * success color secondary
                */
                'tw-success-secondary-700': '#1d4ed8',
                'tw-success-secondary-600': '#2563eb',
                'tw-success-secondary-500': '#3b82f6',
                'tw-success-secondary-400': '#60a5fa',
                'tw-success-secondary-300': '#93c5fd',
                'tw-success-secondary-200': '#bae6fd',
                'tw-success-secondary-100': '#e0f2fe',

                /*
                * danger color Primary
                */
                'tw-danger-primary-700': '#b91c1c',
                'tw-danger-primary-600': '#dc2626',
                'tw-danger-primary-500': '#ef4444',
                'tw-danger-primary-400': '#f87171',
                'tw-danger-primary-300': '#fca5a5',
                'tw-danger-primary-200': '#fecaca',
                'tw-danger-primary-100': '#fee2e2',
                'tw-danger-primary-50': '#fef2f2',
                /*
                * end danger color Primary
                */

                /*
                * warning color Primary
                */
                'tw-warning-primary-700': '#a16207',
                'tw-warning-primary-600': '#ca8a04',
                'tw-warning-primary-500': '#f59e0b',
                'tw-warning-primary-400': '#fbbf24',
                'tw-warning-primary-300': '#fcd34d',
                'tw-warning-primary-200': '#fde68a',
                'tw-warning-primary-100': '#fef3c7',
                /*
                * end warning color Primary
                */

            },
        },
    },

    plugins: [forms],
};
