<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';


</script>

<template>
      <div class="tw-inline-flex tw-flex-col tw-items-center tw-w-full lg:tw-w-96 tw-justify-center tw-bg-center tw-bg-cover tw-text-gray-600">
        <div class="tw-w-full lg:tw-w-96 tw-bg-tw-primary-500 tw-rounded-3xl">
            <div class="tw-flex tw-flex-col">
                <div class="tw-bg-white tw-relative tw-drop-shadow-2xl tw-rounded-3xl tw-p-4 tw-m-4">
                <div class="tw-flex-none sm:tw-flex">
                    <div class="tw-flex-auto tw-justify-evenly">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-my-1">
                            <ApplicationLogo class="tw-w-11 tw-h-auto tw-fill-current" />
                        </div>
                        <button>
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-w-8 tw-p-1 tw-text-gray-600 tw-rounded-md tw-bg-gray-100" viewBox="0 0 24 24">
                            <path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path>
                        </svg>
                        </button>
                    </div>
                    <div class="tw-flex tw-items-center tw-justify-between tw-text-xs tw-mt-1 tw-mb-3">
                        <p>John Doe</p>
                        <p>john.doe@example.com</p>
                    </div>
                    <div class="tw-border-dashed tw-border-b-2 tw-mb-5"></div>
                    <div class="tw-flex tw-items-center">
                        <div class="tw-flex tw-flex-col">
                        <div class="tw-flex-auto tw-text-xs tw-text-gray-400 tw-my-1">
                            <span class="tw-mr-1">2024 -</span><span>2025</span>
                        </div>
                        <div class="tw-w-full tw-flex-none tw-text-lg tw-text-tw-primary-700 tw-font-bold tw-leading-none">Valido</div>
                        <div class="tw-text-xs">Status</div>
                        </div>
                        <div class="tw-flex tw-flex-col tw-mx-auto">
                           <!--  <img class="tw-h-auto tw-w-[65px]" src="https://img.icons8.com/3d-fluency/94/bank-cards.png" alt="bank-cards" /> -->
                           <v-btn
                                class="text-green"
                                icon="mdi-check-bold"
                                variant="tonal"
                                @click="fav = !fav"
                            ></v-btn>
                        </div>
                        <div class="tw-flex tw-flex-col">
                        <div class="tw-flex-auto tw-text-xs tw-text-gray-400 tw-my-1">
                            <span class="tw-mr-1">2024 -</span><span>2025</span>
                        </div>
                        <div class="tw-w-full tw-flex-none tw-text-lg tw-text-tw-primary-700 tw-font-bold tw-leading-none">Valido</div>
                        <div class="tw-text-xs">Status</div>
                        </div>
                    </div>
                    <div class="tw-border-dashed tw-border-b-2 tw-my-1 tw-pt-5">
                        <div class="tw-absolute tw-rounded-full tw-w-5 tw-h-5 tw-bg-tw-primary-500 -tw-mt-2 -tw-left-2"></div>
                        <div class="tw-absolute tw-rounded-full tw-w-5 tw-h-5 tw-bg-tw-primary-500 -tw-mt-2 -tw-right-2"></div>
                    </div>
                    <div class="tw-mt-5 tw-text-sm tw-h-[130px] tw-overflow-y-auto">
                        <div class="tw-w-full">
                        <table class="tw-table-auto tw-w-full tw-text-left tw-whitespace-no-wrap tw-table">
                            <thead>
                            <tr>
                                <th class="tw-px-4 tw-py-2 tw-text-xs">Producto</th>
                                <th class="tw-px-4 tw-py-2 tw-text-xs">Cantidad</th>
                                <th class="tw-px-4 tw-py-2 tw-text-xs">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="tw-border-y">
                                <td class="tw-px-4 tw-py-2 tw-text-xs">Guitar</td>
                                <td class="tw-px-4 tw-py-2 tw-text-xs">1</td>
                                <td class="tw-px-4 tw-py-2 tw-text-xs">$1000</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="tw-flex tw-flex-col tw-mt-5 tw-justify-center tw-text-sm">
                            <h6 class="tw-font-bold tw-text-center">Total: $1500</h6>
                        </div>
                        </div>
                    </div>
                    <div class="tw-border-dashed tw-border-b-2 tw-my-1 tw-pt-5">
                        <div class="tw-absolute tw-rounded-full tw-w-5 tw-h-5 tw-bg-tw-primary-500 -tw-mt-2 -tw-left-2"></div>
                        <div class="tw-absolute tw-rounded-full tw-w-5 tw-h-5 tw-bg-tw-primary-500 -tw-mt-2 -tw-right-2"></div>
                    </div>
                    <div class="tw-flex tw-items-center tw-px-5 tw-pt-3 tw-text-sm tw-justify-between">
                        <div class="tw-flex tw-flex-col">
                        <span class="tw-font-semibold">Hora</span>
                        <div>12:30:45</div>
                        </div>
                        <div class="tw-flex tw-flex-col">
                        <span class="tw-font-semibold">Dia</span>
                        <div>15</div>
                        </div>
                        <div class="tw-flex tw-flex-col">
                        <span class="tw-font-semibold">Mes</span>
                        <div>Enero</div>
                        </div>
                        <div class="tw-flex tw-flex-col">
                        <span class="tw-font-semibold">Año</span>
                        <div>2024</div>
                        </div>
                    </div>
                    <div class="tw-flex tw-pt-5 tw-pb-3 tw-justify-center tw-items-center tw-text-sm">
                        <img class="tw-size-32 " src="https://pngimg.com/uploads/qr_code/qr_code_PNG6.png" alt="">
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
