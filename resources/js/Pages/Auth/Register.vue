<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm as useFormInertia  } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import { useForm, useField } from 'vee-validate'
import { ref } from 'vue';
import { registerSchema } from '@/validation/auth/register-schema';
import ErrorSession from '@/Components/ErrorSession.vue';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';

const { handleSubmit } = useForm({validationSchema : registerSchema});
const first_name = useField('first_name');
const last_name = useField('last_name');
const middle_name = useField('middle_name');
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
    data.username = (first_name.value._value + '-' + last_name.value._value).toLowerCase();
    data.birthdate = birthdate.value;
    data.global_image = global_image.value;
    data.email = email.value;
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

const imageUrl = ref(null);

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imageUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

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
            <div class="tw-w-full tw-px-4 lg:tw-px-0 lg:tw-w-[75%] tw-h-auto tw-mx-auto tw-py-32 lg:tw-py-0">
                <div class="">

                    <ErrorSession />
                    <NavigationDrawer />


                    <h2 class="tw-text-3xl tw-font-bold">Registrase en la aplicacion</h2>
                    <h3 class="">Ingresa tus credenciales para acceder.</h3>
                </div>

                <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3">
                    <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-between tw-gap-5">
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Primer nombre</p>
                                <v-text-field
                                    color="primary"
                                    label="Primer nombre"
                                    placeholder="Italia"
                                    hint="Ingresa tu primer nombre"
                                    v-model="first_name.value.value"
                                    :error-messages="first_name.errorMessage.value"
                                    ></v-text-field>
                                    <InputError :message="data.errors.first_name" />
                            </div>
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Apellido paterno</p>
                                <v-text-field
                                    color="primary"
                                    label="Apellido paterno"
                                    placeholder="Luna"
                                    hint="Ingresa tu apellido paterno"
                                    v-model="last_name.value.value"
                                    :error-messages="last_name.errorMessage.value"
                                    ></v-text-field>
                                    <InputError :message="data.errors.last_name" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Apellido materno</p>
                                <v-text-field
                                    color="primary"
                                    label="Apellido materno"
                                    placeholder="Luna"
                                    hint="Ingresa tu apellido materno"
                                    v-model="middle_name.value.value"
                                    :error-messages="middle_name.errorMessage.value"
                                    ></v-text-field>
                                    <InputError :message="data.errors.middle_name" />
                            </div>
                            <div class="tw-w-full">
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
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Genero identificado</p>
                                <v-select
                                    color="primary"
                                    clearable
                                    label="Genero identificado"
                                    hint="Selecciona tu genero"
                                    :items="['masculino', 'femenino', 'no binario', 'otro']"
                                    v-model="user_gender.value.value"
                                    :error-messages="user_gender.errorMessage.value"
                                ></v-select>
                                <InputError :message="data.errors.user_gender" />
                            </div>
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Fecha de nacimiento</p>
                                <v-date-input
                                    density="compact"
                                    color="primary"
                                    clearable
                                    label="Fecha de nacimiento"
                                    hint="Selecciona tu fecha de nacimiento"
                                    v-model="birthdate.value.value"
                                    :error-messages="birthdate.errorMessage.value"
                                ></v-date-input>
                                <InputError :message="data.errors.birthdate" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Imagen de perfil</p>
                                <v-file-input
                                    accept="image/*"
                                    color="primary"
                                    clearable
                                    label="Imagen de perfil"
                                    hint="Selecciona tu imagen de perfil"
                                    prepend-icon="mdi-camera"
                                    variant="filled"
                                    @change="onFileChange"
                                    v-model="global_image.value.value"
                                    :error-messages="global_image.errorMessage.value"
                                ></v-file-input>
                                <div class="tw-mx-auto tw-flex tw-items-center tw-justify-center">
                                    <v-img
                                    v-if="imageUrl"
                                    :src="imageUrl"
                                    alt="Preview"
                                    max-width="100"
                                    rounded="lg"
                                    ></v-img>
                                </div>
                                <InputError :message="data.errors.global_image" />
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col lg:tw-flex-row  tw-items-center tw-justify-between tw-gap-5">
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span> Contraseña</p>
                                <v-text-field
                                    placeholder="Hdx-36109"
                                    color="primary"
                                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="show ? 'text' : 'password'"
                                    hint="Almenos 8 caracteres"
                                    label="Contraseña"
                                    counter
                                    @click:append="show = !show"
                                    v-model="password.value.value"
                                    :error-messages="password.errorMessage.value"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.password" />
                            </div>
                            <div class="tw-w-full">
                                <p class="tw-font-medium tw-mb-1"><span class="tw-text-red-500">*</span>Confirmacion contraseña</p>
                                <v-text-field
                                    placeholder="Hdx-36109"
                                    color="primary"
                                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="show2 ? 'text' : 'password'"
                                    hint="Almenos 8 caracteres"
                                    label="Confirmacion contraseña"
                                    counter
                                    @click:append="show2 = !show2"
                                    v-model="password_confirmation.value.value"
                                    :error-messages="password_confirmation.errorMessage.value"
                                ></v-text-field>
                                <InputError class="" :message="data.errors.password_confirmation" />
                            </div>
                        </div>

                        <div class="tw-mt-5 lg:tw-mt-0 tw-flex lg:tw-flex-row tw-flex-col-reverse tw-gap-7 lg:tw-gap-0 tw-items-center tw-justify-between">
                            <div>
                                ¿Ya tienes una cuenta? <Link :href="route('login')"><span class="tw-text-blue-600 tw-underline">Inicia sesion</span></Link>
                            </div>
                            <v-btn @click="submit" :loading="loading" variant="elevated" :class="{ 'tw-opacity-25': data.processing }" :disabled="data.processing" class="text-none !tw-bg-tw-primary-500 !tw-text-white tw-w-full lg:tw-w-auto" size="large" elevation="8"  rounded="lg">Registrase ahora</v-btn>
                        </div>
                    </v-form>

                </div>
            </div>
        </AuthenticationCard>
</template>
