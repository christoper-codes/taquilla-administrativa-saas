<script setup>
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({ email: ''});
const loading = ref(false);

const submit = () => {
    loading.value = true;
    form.post(route('password.email'), {
        onSuccess: () => {
            toast('Hemos enviado un enlace de restablecimiento a tu correo electrónico', {
                theme: "auto",
                type: "success",
                autoClose: false,
                dangerouslyHTMLString: true
            });
        },
        onError: (errors) => {
            toast(errors.email, {
                theme: "auto",
                type: "error",
                dangerouslyHTMLString: true
            });
        },
        onFinish: () => {
            form.reset('email');
            loading.value = false;
        },
    });
};
</script>

<template>
    <Head title="Recuperar contraseña"/>
    <AuthenticationCard>
        <div class="tw-w-full lg:tw-w-[70%] tw-h-auto tw-mx-auto tw-px-5 lg:tw-px-0 tw-py-10 lg:tw-py-0">
            <div class="">
                <ErrorSession />
                <Link :href="route('events.index')" class="tw-inline-block">
                    <div class="tw-size-10 tw-shadow-md tw-rounded-full tw-bg-primary tw-p-2 tw-flex tw-items-center tw-justify-center tw-mb-3">
                        <span class="material-symbols-outlined tw-text-white">arrow_back</span>
                    </div>
                </Link>
                <h2 class="tw-font-bebas tw-text-4xl tw-font-bold lg:tw-text-5xl">Recuperar contraseña</h2>
                <p class="tw-text-gray-600 tw-text-base tw-mt-2">Ingresa tu correo electronico para recibir un enlace de restablecimiento de contraseña.</p>
            </div>

            <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                    <div>
                        <v-text-field
                            type="email"
                            color="purple"
                            placeholder="user@gmail.com"
                            label="Correo electronico"
                            autocomplete="email"
                            v-model="form.email"
                            variant="outlined"
                            class="!tw-rounded-2xl"
                            :error-messages="form.errors.email"
                        ></v-text-field>
                    </div>
                    <div class="tw-flex lg:tw-flex-col tw-flex-col tw-justify-between">
                        <PrimaryButton @click="submit" heightbtn="!tw-h-[60px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-10" :loading="loading">
                            <span class="material-symbols-outlined tw-text-2xl !tw-w-1/2">fingerprint</span>Enviar enlace de restablecimiento
                        </PrimaryButton>
                    </div>
                </v-form>
            </div>
        </div>
    </AuthenticationCard>
</template>
