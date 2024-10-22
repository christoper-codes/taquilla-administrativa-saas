<script setup>
import  GuestLayout  from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import NavigationDrawer from '@/Components/NavigationDrawer.vue';
import { ref } from 'vue';
import { QrcodeStream } from 'vue-qrcode-reader';
import Footer from '@/Components/Footer.vue';


const decodedContent = ref('')

const onDecode = () => {
};

const onInit = () =>{
    promise.then(() => {
        console.log('listo');
    }).catch(error => {
        console.error('Error', error);
    });
};

const activar = ref(false)
const showCamara = ref(true);
const selectCamara = ref('rear')

const scanner = () => {
    activar.value = !activar.value;
    showCamara.value = !showCamara.value;
}

const switchCamera = () => {
    selectCamara.value = selectCamara.value === 'rear' ? 'front' : 'rear';
}


const handleError = (error)=> {
    console.error('Error', error);
}

const preferredCamera = {
        facingMode: "environment",
        width: { min: 640, ideal: 1280 },
        height: { min: 480, ideal: 720 }
      }

</script>



<template>
    <Head title="Verificar"/>
    <GuestLayout />
    <NavigationDrawer />

    <div class="tw-items-center">
        <div class="tw-py-9">
            <h3 class="tw-text-3xl tw-font-bold tw-text-center lg:tw-text-[40px]">Verificar boletos</h3>
        </div>
       <div class="center-container">
            <v-btn @click="scanner" variant="elevated" class="text-none !tw-bg-tw-primary-500 !tw-text-white !tw-px-7" size="large" rounded="xl">
                <span class="material-symbols-outlined tw-text-xl">scanner</span>{{showCamara ? 'Desactivar':'Escanear'}}
            </v-btn>
        </div>
        <div class="center-container tw-my-5">
            <v-btn @click="switchCamera" variant="tonal" color="red" rounded="xl"  class="tw-py-2 text-none" >Cambiar Cámara</v-btn>
        </div>
        <div class="center-container tw-my-5">
            <div class="tw-py-2" v-if="showCamara">
                <qrcode-stream class="tw-border-[10px] tw-rounded-2xl" @decode="onDecode" @init="onInit" :paused="activar" :camera="selectCamara" @unrecoverable-error="handleError"/>
            </div>
            <p>{{ decodedContent }}</p>
        </div>
    </div>

    <Footer />

</template>

<style lang="scss" scoped>

.center-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>
