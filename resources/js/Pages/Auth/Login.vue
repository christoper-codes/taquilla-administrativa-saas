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
    remember: false,
});

const show = ref(false);
const loading = ref(false);

const submit = handleSubmit((values) => {
    loading.value = true;
    data.email = email.value;
    data.password = password.value;
    data.remember = remember.value;

    data.post(route('login'), {
        onFinish: () => {
           loading.value = false;
        }
    });
});

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

</script>

<template>
    <Head title="Log in" />
        <AuthenticationCard>
            <NavigationDrawer />
            <div class="tw-w-full lg:tw-w-[55%] tw-h-auto tw-mx-auto tw-px-4 lg:tw-px-0 tw-py-32 lg:tw-py-0">
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
                            <v-btn @click="submit" :loading="loading" variant="elevated" :class="{ 'tw-opacity-25': data.processing }" :disabled="data.processing" class="text-none !tw-bg-tw-primary-500 !tw-text-white tw-w-full lg:tw-w-auto" size="large" rounded="lg">Iniciar sesion</v-btn>
                            <div class="lg:tw-hidden tw-mt-5">
                                ¿Aun no tienes cuenta? <Link :href="route('register')"><span class="tw-text-blue-600 tw-underline">Registrarte ahora</span></Link>
                            </div>
                        </div>
                    </v-form>

                </div>
            </div>
        </AuthenticationCard>
</template>

