<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm as useFormInertia } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import { useForm, useField } from 'vee-validate'
import { ref } from 'vue';
import { loginSchema } from '@/validation/auth/login-schema';
import ErrorSession from '@/Components/ErrorSession.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';

const { handleSubmit } = useForm({validationSchema : loginSchema});
const email = useField('email');
const password = useField('password');
const remember = useField('remember');
const data  = useFormInertia({
    email: '',
    password: '',
    remember: true,
    slug: '',
    id: '',
});

const show = ref(false);
const loading = ref(false);

const submit = handleSubmit((values) => {
    loading.value = true;
    data.email = email.value;
    data.password = password.value;
    data.remember = remember.value;
    data.slug = props.slug;
    data.id = props.id;

    data.post(route('login'), {
        onFinish: () => {
           loading.value = false;
        }
    });
});

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    slug: {
        type: String,
    },
    id: {
        type: String,
    },
});
</script>

<template>
    <Head title="Log in"/>
    <AuthenticationCard>
        <div class="tw-w-full lg:tw-w-[70%] tw-h-auto tw-mx-auto tw-px-5 lg:tw-px-0 tw-py-10 lg:tw-py-0">
            <div class="">
                <ErrorSession />
                <Link :href="route('events.index')">
                    <div class="tw-size-10 tw-shadow-md tw-rounded-full tw-bg-primary tw-p-2 tw-flex tw-items-center tw-justify-center tw-mb-3">
                        <span class="material-symbols-outlined tw-text-white">arrow_back</span>
                    </div>
                </Link>
                <h2 class="tw-font-bebas tw-text-4xl tw-font-bold lg:tw-text-5xl">Iniciar sesión</h2>
            </div>

            <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                    <div>
                        <v-text-field
                            color="purple"
                            type="email"
                            placeholder="user@gmail.com"
                            label="Correo electrónico"
                            autocomplete="email"
                            hint="Ingresa tu correo electrónico"
                            v-model="email.value.value"
                            variant="outlined"
                            :error-messages="email.errorMessage.value"
                            class="!tw-rounded-2xl"
                        ></v-text-field>
                        <InputError class="" :message="data.errors.email" />
                    </div>
                    <div>
                        <v-text-field
                        placeholder="●●●●●●●●"
                        label="Contraseña"
                        color="purple"
                        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show ? 'text' : 'password'"
                        hint="Almenos 8 caracteres"
                        name="input-10-1"
                        variant="outlined"
                        counter
                        @click:append="show = !show"
                        v-model="password.value.value"
                        :error-messages="password.errorMessage.value"
                    ></v-text-field>
                        <InputError class="" :message="data.errors.password" />
                    </div>

                    <div class="tw-flex lg:tw-flex-col tw-flex-col tw-justify-between">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <v-switch label="Recordar sesión" v-model="remember.value.value" color="purple"></v-switch>
                            <Link :href="route('password.request')" class="tw-text-primary tw-underline tw-mb-7">¿Olvidaste tu contraseña?</Link>
                        </div>
                        <PrimaryButton @click="submit" :disabled="data.processing" heightbtn="!tw-h-[60px] !tw-text-base !tw-w-full md:!tw-w-auto" paddingbtn="!tw-px-10" :loading="loading">
                            <span class="material-symbols-outlined tw-text-2xl !tw-w-1/2">fingerprint</span>Iniciar sesión
                        </PrimaryButton>
                        <div class="lg:tw-hidden tw-mt-5">
                            ¿Aún no tienes cuenta?
                            <Link :href="route('register', { slug: slug, id: id})">
                                <span class="tw-text-primary tw-underline">Registrarte ahora</span>
                            </Link>
                        </div>
                    </div>
                    <div v-if="slug && loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-4 tw-animate-pulse">
                        <p class="tw-font-bold tw-text-xs">Preparando las zonas para el evento...</p>
                    </div>
                    <div class="tw-hidden lg:tw-block tw-mt-7">
                        ¿Aún no tienes cuenta?
                        <Link :href="route('register', { slug: slug, id: id})">
                            <span class="tw-text-primary tw-underline">Registrarte ahora</span>
                        </Link>
                    </div>
                </v-form>

            </div>
        </div>
    </AuthenticationCard>
</template>

