<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'


const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {

        onSuccess: () => {
            if (props.status) {
                toast(props.status, {
                    theme: "auto",
                    type: "success",
                    dangerouslyHTMLString: true
                });
            }
        },
        onError: (errors) => {
            if (errors) {
                toast(errors, {
                    theme: "auto",
                    type: "error",
                    dangerouslyHTMLString: true
                });
            } else {
                toast("Ha ocurrido un error. Inténtalo de nuevo.", {
                    theme: "auto",
                    type: "error"
                });
            }
        }
    });
};
</script>

<template>
    <GuestLayout/>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600 tw-mt-6 tw-mx-11 tw-my-6 tw-font-bold">
            ¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.
        </div>


        <div class="tw-flex tw-justify-center tw-items-center">
            <form @submit.prevent="submit" class="tw-w-full tw-max-w-md tw-space-y-4">
                <div>
                    <v-text-field
                        id="email"
                        type="email"
                        color="purple"
                        placeholder="user@gmail.com"
                        label="Correo electrónico"
                        autocomplete="email"
                        hint="Ingresa tu correo electrónico"
                        v-model="form.email"
                        variant="outlined"
                        class="!tw-rounded-2xl"
                    ></v-text-field>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="tw-flex tw-justify-center">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="!tw-bg-purple-600 hover:!tw-bg-purple-700 !tw-text-white !tw-px-10 !tw-h-[60px] !tw-text-base !tw-rounded-2xl">
                        Restablecer tu contraseña
                    </PrimaryButton>
                </div>
            </form>
        </div>

</template>
