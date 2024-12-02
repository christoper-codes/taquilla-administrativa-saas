<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm as useFormInertia } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import { useForm, useField } from 'vee-validate'
import { ref } from 'vue';
import { loginSchema } from '@/validation/auth/login-schema';
import ErrorSession from '@/Components/ErrorSession.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';

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
    <Head title="Log in" />
        <AuthenticationCard>
            <NavigationDrawer />
            <div class="tw-w-full lg:tw-w-[55%] tw-h-auto tw-mx-auto tw-px-4 lg:tw-px-0 tw-py-10 lg:tw-py-0">
                <div class="">
                    <ErrorSession />
                    <h2 class="tw-text-3xl tw-font-bold">Iniciar sesion</h2>
                    <h3 class="">Ingresa tus credenciales para acceder.</h3>
                </div>

                <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                    <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                        <div>
                            <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> E-mail</p>
                            <v-text-field
                                color="primary"
                                label="E-mail"
                                placeholder="user@gmail.com"
                                autocomplete="email"
                                hint="Ingresa tu correo electronico"
                                v-model="email.value.value"
                                :error-messages="email.errorMessage.value"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.email" />
                        </div>
                        <div>
                            <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Contraseña</p>
                            <v-text-field
                            placeholder="Hdx-36109"
                            color="primary"
                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show ? 'text' : 'password'"
                            hint="Almenos 8 caracteres"
                            label="Contraseña"
                            name="input-10-1"
                            counter
                            @click:append="show = !show"
                            v-model="password.value.value"
                            :error-messages="password.errorMessage.value"
                        ></v-text-field>
                            <InputError class="" :message="data.errors.password" />
                        </div>

                        <div class="tw-flex lg:tw-flex-row tw-flex-col lg:tw-items-center tw-justify-between">
                            <v-checkbox
                                v-model="remember.value.value"
                                color="primary"
                                label="Recordar contraseña"
                                hint="Mantener la sesion iniciada"
                                ></v-checkbox>
                            <v-btn  @click="submit" :loading="loading" :class="{ 'tw-opacity-25': data.processing }" :disabled="data.processing" variant="elevated" class="text-none !tw-text-white !tw-bg-gradient-to-r !tw-from-purple-600 !tw-to-pink-400" rounded="xl" size="large"><span class="material-symbols-outlined tw-text-xl !tw-w-1/2">person</span>Iniciar sesion</v-btn>
                            <div class="lg:tw-hidden tw-mt-5">
                                ¿Aun no tienes cuenta?
                                <Link :href="route('register', { slug: slug, id: id})">
                                    <span class="tw-text-purple-600 tw-underline">Registrarte ahora</span>
                                </Link>
                            </div>
                        </div>
                        <div v-if="slug && loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-4 lg:tw-mt-0 tw-animate-pulse">
                            <p class="tw-font-bold tw-text-xs">Preparando las zonas para el evento...</p>
                            <iframe class="tw-size-20  tw-rotate-45" src="https://lottie.host/embed/bf6d5e1b-537a-436b-8464-3d074f070d76/SAdIq1oqT7.json"></iframe>
                        </div>
                        <div class="tw-hidden lg:tw-block tw-mt-5">
                            ¿Aun no tienes cuenta?
                            <Link :href="route('register', { slug: slug, id: id})">
                                <span class="tw-text-purple-600 tw-underline">Registrarte ahora</span>
                            </Link>
                        </div>
                    </v-form>

                </div>
            </div>
        </AuthenticationCard>
</template>

