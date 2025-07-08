<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm as useFormInertia  } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import { useForm, useField } from 'vee-validate'
import { onMounted, ref } from 'vue';
import { registerSchema } from '@/validation/auth/register-schema';
import ErrorSession from '@/Components/ErrorSession.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';

const { handleSubmit } = useForm({validationSchema : registerSchema});
const first_name = useField('first_name');
const last_name = useField('last_name');
const middle_name = useField('middle_name');
const username = useField('username');
const user_gender = useField('user_gender');
const birthdate = useField('birthdate');
const global_image = useField('global_image');
const email = useField('email');
const password = useField('password');
const password_confirmation = useField('password_confirmation');
const data  = useFormInertia({
    user_gender: '',
    first_name: '',
    last_name: '',
    middle_name: '',
    username: '',
    birthdate: '',
    global_image: '',
    email: '',
    password: '',
    password_confirmation: '',
    slug: '',
    id: '',
});

const show = ref(false);
const show2 = ref(false);
const loading = ref(false);

const submit = handleSubmit((values) => {
    loading.value = true;
    data.user_gender = user_gender.value;
    data.first_name = first_name.value;
    data.last_name = last_name.value;
    data.middle_name = middle_name.value;
    data.username = username.value.value.trim().toLowerCase().replace(/\s+/g, '-');
    data.birthdate = birthdate.value;
    data.global_image = global_image.value;
    data.email = email.value.value.trim().toLowerCase();
    data.password = password.value;
    data.password_confirmation = password_confirmation.value;
    data.slug = props.slug;
    data.id = props.id;

    data.post(route('register'), {
        onFinish: () => {
            loading.value = false;
        }
    });
});

const props = defineProps({
    slug: {
        type: String,
    },
    id: {
        type: Number,
    },
});
</script>

<template>
    <Head title="Log in" />
        <AuthenticationCard>
            <div class="tw-w-full tw-px-5 lg:tw-px-0 lg:tw-w-[80%] tw-h-auto tw-mx-auto tw-py-10 lg:tw-py-0">
                <div class="">
                    <ErrorSession />
                    <Link :href="route('events.index')">
                        <div class="tw-size-10 tw-shadow-md tw-rounded-full tw-bg-primary tw-p-2 tw-flex tw-items-center tw-justify-center tw-mb-3">
                            <span class="material-symbols-outlined tw-text-white">arrow_back</span>
                        </div>
                    </Link>
                    <h2 class="tw-font-bebas tw-text-4xl tw-font-bold lg:tw-text-5xl">Registrarse</h2>
                </div>

                <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                    <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between lg:tw-gap-5">
                            <div class="tw-w-full">
                                <v-text-field
                                    color="purple"
                                    label="Primer nombre"
                                    placeholder="Italia"
                                    hint="Ingresa tu primer nombre"
                                    v-model="first_name.value.value"
                                    :error-messages="first_name.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError :message="data.errors.first_name" />
                            </div>
                            <div class="tw-w-full">
                                <v-text-field
                                    color="purple"
                                    label="Apellido paterno"
                                    placeholder="Luna"
                                    hint="Ingresa tu apellido paterno"
                                    v-model="last_name.value.value"
                                    :error-messages="last_name.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError :message="data.errors.last_name" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between lg:tw-gap-5">
                            <div class="tw-w-full">
                                <v-text-field
                                    color="purple"
                                    label="Apellido materno"
                                    placeholder="Luna"
                                    hint="Ingresa tu apellido materno"
                                    v-model="middle_name.value.value"
                                    :error-messages="middle_name.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError :message="data.errors.middle_name" />
                            </div>
                            <div class="tw-w-full">
                                <v-text-field
                                    color="purple"
                                    type="email"
                                    label="Correo electrónico"
                                    placeholder="user@gmail.com"
                                    hint="Ingresa tu correo electrónico"
                                    v-model="email.value.value"
                                    :error-messages="email.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.email" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between lg:tw-gap-5">
                            <div class="tw-w-full">
                                <v-select
                                    color="purple"
                                    clearable
                                    label="Género identificado"
                                    hint="Selecciona tu género"
                                    :items="['Masculino', 'Femenino', 'No binario', 'Otro']"
                                    variant="outlined"
                                    v-model="user_gender.value.value"
                                    :error-messages="user_gender.errorMessage.value"
                                ></v-select>
                                <InputError :message="data.errors.user_gender" />
                            </div>
                             <div class="tw-w-full">
                                <v-text-field
                                    color="purple"
                                    label="Nickname"
                                    placeholder="Luna"
                                    v-model="username.value.value"
                                    :error-messages="username.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError :message="data.errors.username" />
                            </div>
                        </div>
                        <div class="tw-w-full">
                            <v-date-input
                                density="compact"
                                color="purple"
                                label="Fecha de nacimiento"
                                hint="Selecciona tu fecha de nacimiento"
                                variant="outlined"
                                v-model="birthdate.value.value"
                                :error-messages="birthdate.errorMessage.value"
                            ></v-date-input>
                            <InputError :message="data.errors.birthdate" />
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between lg:tw-gap-5">
                            <div class="tw-w-full">
                                <v-file-upload
                                    v-model="global_image.value.value"
                                    :error-messages="global_image.errorMessage.value"
                                    density="compact" variant="compact"
                                    icon="mdi-account-circle"
                                    title="">
                                </v-file-upload>
                                <InputError :message="global_image.errorMessage.value" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between lg:tw-gap-5 tw-mt-6">
                            <div class="tw-w-full">
                                <v-text-field
                                    placeholder="Hdx-36109"
                                    color="purple"
                                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="show ? 'text' : 'password'"
                                    hint="Almenos 8 caracteres"
                                    label="Contraseña"
                                    counter
                                    @click:append="show = !show"
                                    v-model="password.value.value"
                                    :error-messages="password.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.password" />
                            </div>
                            <div class="tw-w-full">
                                <v-text-field
                                    placeholder="Hdx-36109"
                                    color="purple"
                                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="show2 ? 'text' : 'password'"
                                    hint="Almenos 8 caracteres"
                                    label="Confirmar contraseña"
                                    counter
                                    @click:append="show2 = !show2"
                                    v-model="password_confirmation.value.value"
                                    :error-messages="password_confirmation.errorMessage.value"
                                    variant="outlined"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.password_confirmation" />
                            </div>
                        </div>

                        <div class="tw-mt-5 lg:tw-mt-0 tw-w-full">
                             <PrimaryButton @click="submit" :disabled="data.processing" heightbtn="!tw-h-[60px] !tw-text-base !tw-w-full" paddingbtn="!tw-px-10" :loading="loading">
                                <span class="material-symbols-outlined tw-text-2xl !tw-w-1/2">fingerprint</span>Registrarse ahora
                            </PrimaryButton>
                        </div>
                         <div class="tw-mt-5">
                            ¿Ya tienes una cuenta? <Link :href="route('login')"><span class="tw-text-purple-600 tw-underline">Iniciar sesión</span></Link>
                        </div>
                        <div v-if="slug && loading" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-mt-4 lg:tw-mt-5 tw-animate-pulse">
                            <p class="tw-font-bold tw-text-xs">Preparando las zonas para el evento...</p>
                            <iframe class="tw-size-20  tw-rotate-45" src="https://lottie.host/embed/bf6d5e1b-537a-436b-8464-3d074f070d76/SAdIq1oqT7.json"></iframe>
                        </div>
                    </v-form>

                </div>
            </div>
        </AuthenticationCard>
</template>
