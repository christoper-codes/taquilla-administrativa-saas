<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import { userRolesSchema } from '@/validation/auth/user-roles-schema';


const { handleSubmit, resetForm } = useForm({validationSchema : userRolesSchema});

const first_name = useField('first_name');
const last_name = useField('last_name');
const middle_name = useField('middle_name');
const user_gender = useField('user_gender');
const birthdate = useField('birthdate');
const email = useField('email');
const rolesField = useField('roles');
const data  = useFormInertia({
    user_gender: '',
    first_name: '',
    last_name: '',
    middle_name: '',
    username: '',
    birthdate: '',
    email: '',
    global_image: '',
    roles: [],
});

const loading = ref(false);
const loadingUpdate = ref(false);
const users_list = [];

const acept = () => {
    loading.value = !loading.value
    data.get(route('create.users'), {});
}

const cancelForm = () =>{

    if (updateOrCreate.value) {
        dialogCreateUser.value = !dialogCreateUser.value
        resetForm()
    }else{
        dialogUpdateUser.value = !dialogUpdateUser.value
        resetForm()
    }
}

const submit = handleSubmit((values) => {

    loadImage();
    data.user_gender = user_gender.value;
    data.first_name = first_name.value;
    data.last_name = last_name.value;
    data.middle_name = middle_name.value;
    data.username = (first_name.value._value + '-' + last_name.value._value).toLowerCase();
    data.birthdate = birthdate.value;
    data.email = email.value;
    data.global_image = imageUrl.value

    rolesField.value._value.forEach( x => {
        props.roles.forEach( z => {
            if (x == z['name']) {
                data.roles.push(z);
            }

        });
    });

    if (updateOrCreate.value) {

        data.post(route('create.user.with.roles'), {
            onSuccess: (response) => {
                emailNewUser.value = response.props.flash.message.data.email
                userNameNewUser.value =  response.props.flash.message.data.username
                passwordNewUser.value =  response.props.flash.message.data.password
            },
            onFinish: () => {
                dialogCreateUser.value = !dialogCreateUser.value
                resetForm();
                loading.value =  !loading.value

            },
            onError:(error)=> {
                console.log(error);
            }
        });
    }else{
        data.post(route('update.user.with.roles'),{
            onSuccess: (response)=>{},
            onFinish: () => {
                dialogUpdateUser.value = !dialogUpdateUser.value
                resetForm();
                loadingUpdate.value =  !loadingUpdate.value
            },
            onError: (error) => {
                console.log(error)
            }
        });

    }

});

const aceptUpdate = () => {
    loadingUpdate.value = !loadingUpdate.value
    data.get(route('create.users'), {});
}

var emailNewUser = ref('');
var userNameNewUser = ref('');
var passwordNewUser = ref('');

const imageUrl = ref(null);
const user = usePage().props.auth.user;

const loadImage = async () => {
    const filePath = '/img/user-img.svg';
    try {
    const response = await fetch(filePath);

    if (!response.ok) {
        throw new Error(`Error al cargar el archivo: ${response.status} ${response.statusText}`);
    }

    const fileName = 'user-img.svg';
    const file = new File([await response.blob], fileName, { type: 'image/svg+xml' });

    onFileChange(file);

    cleanup();
    } catch (error) {
        console.log(error);
    }

}
const cleanup = () => {
    imageUrl.value = '';
};
const xy = () => {
    loadImage();
}

const onFileChange = (event) => {
    if (event) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrl.value = e.target.result;
        };
        reader.readAsDataURL(event);
    }
};

var searchTab = ref('');
var userSelect = ref('');
var dialogCreateUser = ref(false);
var dialogUpdateUser = ref(false);
var updateOrCreate = ref(true);

const userCreateRol = ()=>{

    updateOrCreate.value = true

    if (userSelect.value == '') {
        dialogCreateUser.value = !dialogCreateUser.value;
    }


}

var headersTab = ref([
    {align: 'start',key: 'name',sortable: false,title: 'Nombre',},
    { key: 'lastName', title: 'Apell. Paterno' },
    { key: 'middleName', title: 'Apell. Materno' },
    { key: 'email', title: 'Correo electrónico' },
    { key: 'username', title: 'Username' },
    { key: 'rol', title: 'Rol' },]
);

const roles = ref([]);


const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
    "users": {
        Type: Object,
        Required: true
    },
    "roles": {
        Type: Object,
        Required: true
    },
})


const userChangeRol = () => {

    updateOrCreate.value = false
    dialogUpdateUser.value = !dialogUpdateUser.value

    users_list.forEach(element => {
        if (userSelect.value == element.username) {
            user_gender.value.value = element.gender;
            first_name.value.value = element.name;
            last_name.value.value = element.lastName
            middle_name.value.value = element.middleName
            birthdate.value.value = element.birthdate
            email.value.value = element.email
        }
    });

}


const userRoles = (roles) => {
    let rol = ''
    roles.forEach(element => {
        rol = rol + element['name'] + ', '
    });
    return  rol;
}

props.roles.forEach(element => {

    roles.value.push(element['name']);


})

props.users.forEach(element => {

    if (user['username'] != element['username']) {
        users_list.push(
            {
                id: element['id'],
                name: element['first_name'],
                lastName:  element['last_name'],
                middleName: element['middle_name'],
                email: element['email'],
                gender: element['user_gender_id'],
                birthdate: element['birthdate'],
                username: element['username'],
                rol: userRoles(element['user_roles'])
            }
        )
    }

});

</script>

<template>

    <Head title="Usuarios"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Usuarios</span>
        </BreadcrumbAppSecondary>

        <v-dialog v-model="loading" max-width="800px" persistent>
            <v-card title="Nuevo Usuario">
                <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3 tw-mx-5">
                    <p>Email: {{emailNewUser}}</p>
                    <p>Username: {{userNameNewUser}}</p>
                    <p>Contraseña: {{passwordNewUser}}</p>
                </div>
                <template v-slot:actions>
                    <v-btn
                        color="purple"
                        rounded="xl"
                        class="!tw-px-4 text-none"
                        variant="elevated"
                        @click="acept">
                        Aceptar
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>

        <v-dialog v-model="loadingUpdate" max-width="800px" persistent>
            <v-card title="¡Usuario Actualizado!">
                <template v-slot:actions>
                    <v-btn
                        color="purple"
                        rounded="xl"
                        class="!tw-px-4 text-none"
                        variant="elevated"
                        @click="aceptUpdate">
                        Aceptar
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>


        <v-dialog
            v-model="dialogUpdateUser"
            max-width="800px"
            persistent
        >
        <v-card
            title="Actualizar Usuario"
        >
            <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3 tw-mx-5">
                <v-form class="tw-mt-5 tw-flex tw-flex-col tw-gap-1">
                    <div class="tw-w-full">
                        <v-select
                            v-model="rolesField.value.value"
                            :items="roles"
                            label="Selecciona los roles"
                            chips
                            multiple
                            :error-messages="rolesField.errorMessage.value"
                        ></v-select>
                        <InputError :message="data.errors.rolesField" />
                    </div>
                </v-form>
            </div>
            <template v-slot:actions>
                <v-btn
                    color="red"
                    rounded="xl"
                    class="!tw-px-4 text-none"
                    variant="elevated"
                    @click="cancelForm"
                >
                    Cancelar
                </v-btn>
                <v-btn
                    color="purple"
                    rounded="xl"
                    class="!tw-px-4 text-none"
                    variant="elevated"
                    @click="submit">
                    Crear
                </v-btn>
            </template>
        </v-card>


        </v-dialog>



        <v-dialog
            v-model="dialogCreateUser"
            max-width="800px"
            persistent
        >
            <v-card
                title="Crear Usuario"
            >
            <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-3 tw-mx-5">
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
                    <div class="tw-w-full">
                        <v-select
                            v-model="rolesField.value.value"
                            :items="roles"
                            label="Selecciona los roles"
                            chips
                            multiple
                            :error-messages="rolesField.errorMessage.value"
                        ></v-select>
                        <InputError :message="data.errors.rolesField" />
                    </div>
                </v-form>

            </div>

                <template v-slot:actions>
                    <v-btn
                        color="red"
                        rounded="xl"
                        class="!tw-px-4 text-none"
                        variant="elevated"
                        @click="cancelForm"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="purple"
                        rounded="xl"
                        class="!tw-px-4 text-none"
                        variant="elevated"
                        @click="submit">
                        Crear
                    </v-btn>
                </template>
            </v-card>

        </v-dialog>


        <div class="tw-w-full tw-px-4 lg:tw-p-10 tw-h-auto tw-mx-auto tw-py-10">
            <v-card
                title=""
                flat
            >

                    <template v-slot:text>
                        <v-text-field
                            v-model="searchTab"
                            label="Buscar usuario..."
                            prepend-inner-icon="mdi-magnify"
                            variant="outlined"
                            color="orange-darken-2"
                            hide-details
                            single-line
                        ></v-text-field>
                    </template>

                    <v-row justify="end">
                        <div v-if="userSelect == ''">
                            <v-btn
                                variant="tonal"
                                class="mb-2 !tw-mr-5 text-none tw-my-3 tw-mx-3"
                                color="purple"
                                rounded="xl"
                                @click="userCreateRol"
                            >
                                Crear Usuario
                            </v-btn>
                        </div>
                        <div v-else>
                            <v-btn
                                variant="tonal"
                                class="mb-2 !tw-mr-5 text-none tw-my-3 tw-mx-3"
                                color="purple"
                                rounded="xl"
                                @click="userChangeRol"
                            >
                                Actualizar Usuario
                            </v-btn>
                        </div>
                    </v-row>



                <v-data-table
                    :headers="headersTab"
                    :items="users_list"
                    :search="searchTab"
                    v-model="userSelect"
                    item-value="username"
                    select-strategy="single"
                    show-select
                ></v-data-table>
            </v-card>
        </div>


    </AppLayout>


</template>

<style scoped>
.container{
    align-items: center;
    width: 70%;
}
</style>
