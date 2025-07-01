<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onSuccess: () => {
            toast("Contraseña Restablecida", {
                theme: "auto",
                type: "success",
                dangerouslyHTMLString: true
            });
        },
        onError: (errors) => {
            if (errors) {
                toast("Opps! intente de nuevo", {
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
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation')
        },
    });
};
</script>

<template>
    <GuestLayout/>
        <Head title="Reset Password"/>
        <div class="tw-flex tw-justify-center tw-items-center tw-my-8">
            <form @submit.prevent="submit" class="tw-w-full tw-max-w-md tw-space-y-3">

                <h1 class="tw-font-bold tw-text-xl">Restablecer contraseña</h1>
                <div>


                    <v-text-field
                        id="password"
                        type="password"
                        color="purple"
                        placeholder="●●●●●●●●"
                        label="Contraseña"
                        autocomplete="new-password"
                        hint="Ingresa tu contraseña nueva"
                        v-model="form.password"
                        variant="outlined"
                        class="!tw-rounded-2xl"
                    ></v-text-field>

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>

                    <v-text-field
                        id="password_confirmation"
                        type="password"
                        color="purple"
                        placeholder="●●●●●●●●"
                        label="Confirma tu contraseña"
                        autocomplete="new-password"
                        hint="Confirma tu contraseña nueva"
                        v-model="form.password_confirmation"
                        variant="outlined"
                        class="!tw-rounded-2xl"
                    ></v-text-field>

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div  class="tw-flex tw-justify-center">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="!tw-bg-purple-600 hover:!tw-bg-purple-700 !tw-text-white !tw-px-10 !tw-h-[60px] !tw-text-base !tw-rounded-2xl">
                       Confirmar Contraseña
                    </PrimaryButton>
                </div>
            </form>
        </div>
</template>
