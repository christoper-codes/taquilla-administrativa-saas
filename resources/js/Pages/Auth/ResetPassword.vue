<script setup>
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import { ref } from 'vue';
import ErrorSession from '@/Components/ErrorSession.vue';

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

const loading = ref(false);
const show = ref(false);

const submit = () => {
    loading.value = true;
    form.post(route('password.store'), {
        onSuccess: () => {
            toast("Contraseña Restablecida", {
                theme: "auto",
                type: "success",
                autoClose: false,
                dangerouslyHTMLString: true
            });
        },
        onError: (errors) => {
            console.error(errors);
            toast(errors.password || errors.password_confirmation || errors.email, {
                theme: "auto",
                type: "error",
                dangerouslyHTMLString: true
            });
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation')
            loading.value = false;
        },
    });
};
</script>

<template>
    <Head title="Restablecer contraseña" />
    <AuthenticationCard>
        <div class="tw-w-full lg:tw-w-[70%] tw-h-auto tw-mx-auto tw-px-5 lg:tw-px-0 tw-py-10 lg:tw-py-0">
            <div class="">
                <ErrorSession />
                <Link :href="route('events.index')" class="tw-inline-block">
                    <div class="tw-size-10 tw-shadow-md tw-rounded-full tw-bg-primary tw-p-2 tw-flex tw-items-center tw-justify-center tw-mb-3">
                        <span class="material-symbols-outlined tw-text-white">arrow_back</span>
                    </div>
                </Link>
                <h2 class="tw-font-bebas tw-text-4xl tw-font-bold lg:tw-text-5xl">Restablecer contraseña</h2>
                <p class="tw-text-gray-600 tw-text-base tw-mt-2">Ingresa la nueva contraseña para restablecerla.</p>
            </div>

            <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                    <div class="tw-flex tw-flex-col">
                        <v-text-field
                            id="password"
                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show ? 'text' : 'password'"
                            @click:append="show = !show"
                            color="purple"
                            placeholder="●●●●●●●●"
                            label="Contraseña"
                            autocomplete="new-password"
                            hint="Ingresa tu contraseña nueva"
                            v-model="form.password"
                            variant="outlined"
                            class="!tw-rounded-2xl"
                            :error-messages="form.errors.password"
                        ></v-text-field>
                        <v-text-field
                            id="password_confirmation"
                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show ? 'text' : 'password'"
                            @click:append="show = !show"
                            color="purple"
                            placeholder="●●●●●●●●"
                            label="Confirmar contraseña"
                            autocomplete="new-password"
                            hint="Confirma tu contraseña nueva"
                            v-model="form.password_confirmation"
                            variant="outlined"
                            class="!tw-rounded-2xl"
                            :error-messages="form.errors.password_confirmation"
                        ></v-text-field>
                    </div>
                    <div class="tw-flex lg:tw-flex-col tw-flex-col tw-justify-between">
                        <PrimaryButton @click="submit" heightbtn="!tw-h-[60px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-10" :loading="loading" :disabled="form.processing">
                            <span class="material-symbols-outlined tw-text-2xl !tw-w-1/2">fingerprint</span>Restablecer
                        </PrimaryButton>
                    </div>
                </v-form>
            </div>
        </div>
    </AuthenticationCard>
</template>
