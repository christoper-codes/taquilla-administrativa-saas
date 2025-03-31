<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessSession from '@/Components/SuccessSession.vue';
import ErrorSession from '@/Components/ErrorSession.vue';
import BreadcrumbAppSecondary from '@/Components/BreadcrumbAppSecondary.vue';
import { Head, usePage, useForm as useFormInertia } from '@inertiajs/vue3';
import { useForm, useField } from 'vee-validate';
import { ref } from 'vue';

const props = defineProps({
    "user": {
        Type: Object,
        Required: true
    },
})

</script>

<template>

    <Head title="mis tarjetas"/>
    <SuccessSession />
    <AppLayout >
        <ErrorSession />
        <BreadcrumbAppSecondary>
            <span>Mis tarjetas digitales</span>
        </BreadcrumbAppSecondary>

        <div class="tw-relative tw-min-h-screen tw-w-full tw-block tw-overflow-hidden tw-bg-center tw-bg-cover tw-bg-[url('https://alithemes.com/assets/imgs/bg-blur.png')]">
            <div class="tw-p-10 tw-flex tw-items-center tw-justify-center tw-gap-10">

           <!--  <div data-aos="fade-up" data-aos-duration="2500" class="tw-w-full tw-p-7 tw-text-white tw-shadow-xl tw-rounded-3xl tw-bg-gradient-to-r tw-from-red-400 tw-to-pink-300 tw-border-4 tw-border-white">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div  class="tw-flex tw-items-center tw-gap-3">
                        <img class="tw-w-14" src="https://upload.wikimedia.org/wikipedia/en/3/36/Logo_Halcones_Xalapa.png?Halcones_de_Xalapa1147183265" />
                       <div>
                        <p class="tw-font-bold">Card number</p>
                        <p>8752**** **** 6325</p>
                       </div>
                    </div>
                    <div class="tw-text-right">
                        <p class="tw-font-bold">Cashless</p>
                        <span class="material-symbols-outlined tw-text-2xl">credit_card</span>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-mt-5">
                    <div class=" tw-bg-red-500 tw-flex tw-items-center tw-gap-1 tw-justify-center tw-px-4 tw-py-[1px] tw-rounded-full">
                        <span class="tw-block tw-text-xs">Activa</span>
                        <span class="tw-block material-symbols-outlined tw-text-base">check_circle</span>
                    </div>
                    <div class="tw-font-bold tw-text-3xl">
                        $100.00 MXC
                    </div>
                    <div class=" tw-bg-pink-400 tw-flex tw-items-center tw-gap-1 tw-justify-center tw-px-4 tw-py-1.5 tw-rounded-full">
                        <span class="tw-block tw-text-xs">Valido: 02/07/2026</span>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-justify-center tw-mt-5">
                    <div>
                        <img class="tw-w-24 tw-rounded-lg" src="https://th.bing.com/th/id/OIP.oPbEFPk2i3o7eO-EkTEFkAHaHa?rs=1&pid=ImgDetMain"/>
                    </div>
                </div>

            </div>

            <div data-aos="fade-left" data-aos-duration="2500" class="tw-w-full tw-p-7 tw-text-white tw-shadow-xl tw-rounded-3xl tw-bg-gradient-to-r tw-from-purple-400 tw-to-blue-400 tw-border-4 tw-border-white">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div  class="tw-flex tw-items-center tw-gap-3">
                        <img class="tw-w-14" src="https://upload.wikimedia.org/wikipedia/en/3/36/Logo_Halcones_Xalapa.png?Halcones_de_Xalapa1147183265" />
                       <div>
                        <p class="tw-font-bold">Card number</p>
                        <p>8752**** **** 6325</p>
                       </div>
                    </div>
                    <div class="tw-text-right">
                        <p class="tw-font-bold">Cashback</p>
                        <span class="material-symbols-outlined tw-text-2xl">credit_card</span>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-mt-5">
                    <div class=" tw-bg-purple-500 tw-flex tw-items-center tw-gap-1 tw-justify-center tw-px-4 tw-py-[1px] tw-rounded-full">
                        <span class="tw-block tw-text-xs">Activa</span>
                        <span class="tw-block material-symbols-outlined tw-text-base">check_circle</span>
                    </div>
                    <div class="tw-font-bold tw-text-3xl">
                        $100.00 MXC
                    </div>
                    <div class=" tw-bg-blue-500 tw-flex tw-items-center tw-gap-1 tw-justify-center tw-px-4 tw-py-1.5 tw-rounded-full">
                        <span class="tw-block tw-text-xs">Valido: 02/07/2026</span>
                    </div>
                </div>
                <div class="tw-flex tw-items-center tw-justify-center tw-mt-5">
                    <div>
                        <img class="tw-w-24 tw-rounded-lg" src="https://th.bing.com/th/id/OIP.oPbEFPk2i3o7eO-EkTEFkAHaHa?rs=1&pid=ImgDetMain"/>
                    </div>
                </div>

            </div> -->

            </div>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
