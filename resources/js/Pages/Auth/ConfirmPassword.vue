<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
    <div class="pb-16">
        <div class="slider-container">
            <div class="slider-track">
                <div class="slider-group">
                    <img class="" src="https://modernize-nuxt3-main.netlify.app/images/landingpage/background/slider-group.png">
                </div>
                <div class="slider-group">
                    <img class="" src="https://modernize-nuxt3-main.netlify.app/images/landingpage/background/slider-group.png">
                </div>
                <!-- Repite los elementos según sea necesario -->
            </div>
        </div>
    </div>
</template>

<style>
    .slider-container {
        overflow: hidden;
        width: 100%;
        height: 100%; /* Altura fija de 300px */
    }

    .slider-track {
        display: flex;
        width: calc(100% * 3); /* Ajusta este valor según el número de imágenes */
        animation: slide 45s linear infinite;
    }

    .slider-group {
        flex: 0 0 auto;
        width: 100%;
        height: 100%; /* Asegura que las imágenes ocupen toda la altura del contenedor */
    }

    .slider-group img {
        object-fit: cover ; /* Ajusta la imagen para que se muestre completa */
    }

    @keyframes slide {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }
</style>
