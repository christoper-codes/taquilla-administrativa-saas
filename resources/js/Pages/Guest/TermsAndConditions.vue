<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3';

const date = new Date();
const formattedDate = new Intl.DateTimeFormat('es-MX', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
}).format(date);
</script>

<template>
<Head title="Políticas de privacidad" />
  <div class="tw-bg-white">
    <main class="tw-flex tw-items-center tw-justify-center tw-py-5 lg:tw-py-0 tw-relative tw-bg-white lg:tw-w-[60%] !tw-mx-auto">
    <div class="tw-px-5 lg:tw-px-0 lg:tw-py-5 tw-w-full">
      <div class="tw-flex tw-tw-items-center tw-justify-between">
        <div class="tw-flex tw-items-center tw-gap-3">
        <Link :href="route('welcome')" class="tw-block">
            <div class="lg:tw-h-[50px] tw-h-[45px] lg:tw-w-[50px] tw-w-[45px] tw-bg-gradient-to-r tw-from-primary tw-to-primary tw-text-white tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-fill-current tw-w-7" viewBox="0 0 24 24"><path d="M12.707 17.293 8.414 13H18v-2H8.414l4.293-4.293-1.414-1.414L4.586 12l6.707 6.707z"></path></svg>
            </div>
        </Link>
        </div>
        <Link :href="route('welcome')" class="tw-block">
            <img class="tw-w-16 lg:tw-w-16 tw-h-auto" src="/storage/public/hdx-logo.png" alt="img logo">
        </Link>
      </div>
      <div class="tw-flex tw-flex-col tw-items-center tw-w-full tw-mt-5 lg:tw-mt-6">
        <h2 class="tw-text-center tw-shadow-2xl tw-font-bold tw-text-xl lg:tw-text-3xl tw-p-7 tw-rounded-xl tw-block tw-w-full">Términos y Condiciones del Boleto</h2>
        <h4 class="tw-text-center  tw-mt-12 tw-inline-block tw-mx-auto">Ultima actualización: {{ formattedDate }}</h4>
      </div>
      <div class="tw-mt-7 tw-leading-7">
        <div class="tw-font-medium  tw-opacity-90">
        <main class="tw-min-h-screen tw-w-full">
            <div class="tw-w-full tw-mx-auto tw-bg-white tw-py-5 tw-px-4 md:tw-px-10">
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-4">Términos y Condiciones del Boleto – Halcones de Xalapa</h1>
                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">1. Reembolsos</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El boleto no está sujeto a reembolso, cambio o reposición.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">2. Acceso</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El boleto te da derecho a un acceso al inmueble.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">3. Asiento asignado</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El boleto te da derecho a un lugar específico.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">4. Reingreso</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  No está permitido el reingreso al inmueble.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">5. Validez del boleto</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El boleto es válido sólo para el evento y asiento indicado.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">6. Revisión de ingreso</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  La admisión está sujeta a revisión para evitar el ingreso de:
                </p>
                <ul class="tw-list-disc tw-list-inside tw-text-gray-700 tw-mt-2">
                  <li>Alimentos, bebidas alcohólicas</li>
                  <li>Drogas, armas</li>
                  <li>Mochilas, maletas</li>
                  <li>Tabaco, vapeadores</li>
                  <li>Cámaras, grabadoras</li>
                  <li>Otros artículos no autorizados</li>
                </ul>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">7. Derecho de admisión</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El titular se reserva el derecho de admisión.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">8. Conducta</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  Se podrá retirar a personas con conducta ofensiva.
                </p>

                <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">9. Normas del inmueble</h2>
                <p class="tw-text-gray-700 tw-mt-2">
                  El espectador debe cumplir con todas las reglas del inmueble.
                </p>
            </div>
        </main>
        </div>
      </div>
      <div class="tw-mt-16 tw-flex tw-flex-col tw-items-center tw-justify-center">
        <Link :href="route('welcome')" class="block">
            <img class="!tw-w-20 tw-h-auto" src="/storage/public/hdx-logo.png" alt="img logo">
        </Link>
        <p class="mt-5 lg:mt-7 inline-block mx-auto">Halcones de Xalapa | {{ formattedDate }}</p>
      </div>
    </div>
  </main>
  </div>
</template>
