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
  <div class="tw-bg-white tw-w-full">
    <main class="tw-flex tw-items-center tw-justify-center tw-py-5 lg:tw-py-0 tw-relative tw-bg-white lg:tw-w-[60%] !tw-mx-auto">
    <div class="tw-px-5 lg:tw-px-0 lg:tw-py-5">
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
        <h2 class="tw-text-center tw-shadow-2xl tw-font-bold tw-text-xl lg:tw-text-3xl tw-p-7 tw-rounded-xl tw-block tw-w-full">Políticas de privacidad</h2>
        <h4 class="tw-text-center  tw-mt-12 tw-inline-block tw-mx-auto">Ultima actualización: {{ formattedDate }}</h4>
      </div>
      <div class="tw-mt-7 tw-leading-7">
        <div class="tw-font-medium  tw-opacity-90">
        <main class="tw-min-h-screen tw-w-full">
        <div class="tw-w-full tw-mx-auto tw-bg-white tw-py-5 tw-px-4 md:tw-px-10">
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-4">Políticas de Privacidad – Halcones de Xalapa</h1>

            <p class="tw-text-gray-700">
            Cuando usa Halcones de Xalapa, nos confía datos como su nombre, correo electrónico y dirección para cumplir con el objetivo principal de la plataforma.
            </p>
            <p class="tw-text-gray-700 tw-mt-2">
            Halcones de Xalapa no comparte esta información con terceros y los datos no son de dominio público.
            </p>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">1. Recopilación y uso de datos</h2>
            <p class="tw-text-gray-700 tw-mt-2">
            Información de perfil de usuario: Recopilamos datos cuando los usuarios crean o actualizan sus cuentas de Halcones de Xalapa. Esto puede incluir:
            </p>
            <ul class="tw-list-disc tw-list-inside tw-text-gray-700 tw-mt-2">
            <li>Nombre</li>
            <li>Correo electrónico</li>
            <li>Domicilio</li>
            <li>Fotografía de perfil</li>
            </ul>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">2. Políticas de privacidad de credencialización</h2>
            <p class="tw-text-gray-700 tw-mt-2">
            Club Halcones de Xalapa (en adelante HXLP), tiene la convicción de darle seguridad a sus afiliados sobre la protección de la información personal (en adelante “Datos Personales”) que compartan con HXLP, siendo responsable de su tratamiento conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.
            </p>

            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800 tw-mt-4">a. Datos que se podrán recabar:</h3>
            <ul class="tw-list-disc tw-list-inside tw-text-gray-700 tw-mt-2">
            <li>Nombre completo</li>
            <li>Fecha de nacimiento</li>
            <li>Número de celular</li>
            <li>Correo electrónico</li>
            <li>Sexo</li>
            <li>Fotografía para la credencial</li>
            </ul>

            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800 tw-mt-4">b. Finalidad del tratamiento de los datos:</h3>
            <ul class="tw-list-disc tw-list-inside tw-text-gray-700 tw-mt-2">
            <li>Dudas y aclaraciones relacionadas con la credencialización.</li>
            <li>Enviar información, publicidad y promociones de HXLP, sus patrocinadores y aliados comerciales.</li>
            <li>Segmentación de afiliados para dinámicas y envío de información específica.</li>
            <li>Otras acciones que cumplan con la ley y mantengan la confidencialidad de los datos.</li>
            </ul>
            <p class="tw-text-gray-700 tw-mt-2">
            La información puede enviarse por medios electrónicos como correo, teléfono o mensajería (Ej. WhatsApp).
            </p>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">3. Privacidad y consentimiento</h2>
            <p class="tw-text-gray-700 tw-mt-2">
            La credencialización con HXLP implica el consentimiento pleno y sin reservas de los usuarios para el tratamiento de sus datos personales, conforme a este Aviso de Privacidad. Se tomarán medidas para salvaguardar estos datos.
            </p>
            <p class="tw-text-gray-700 tw-mt-2">
            El titular de los datos declara ser mayor de edad al momento de proporcionar la información. En caso de menores o personas con incapacidad legal, solo se permitirá la afiliación con consentimiento del tutor legal.
            </p>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">4. Medidas de seguridad</h2>
            <p class="tw-text-gray-700 tw-mt-2">
            HXLP cuenta con medidas técnicas, físicas y administrativas adecuadas para proteger la confidencialidad de los datos personales.
            </p>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">5. Derechos del titular de los datos personales</h2>
            <ul class="tw-list-disc tw-list-inside tw-text-gray-700 tw-mt-2">
            <li>Acceder a sus datos personales.</li>
            <li>Rectificarlos cuando sean inexactos o incompletos.</li>
            <li>Revocar el consentimiento para su tratamiento y cancelar la credencialización.</li>
            </ul>

            <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mt-8">6. Cambios al aviso de privacidad</h2>
            <p class="tw-text-gray-700 tw-mt-2">
            HXLP se reserva el derecho de modificar este aviso de privacidad. Cualquier cambio que requiera su consentimiento le será notificado por medios físicos o digitales.
            </p>
            <p class="tw-text-gray-700 tw-mt-2">
            Fecha de realización del presente Aviso de Privacidad: <strong>27 de febrero de 2023</strong>.
            </p>
            <p class="tw-text-gray-700 tw-mt-2">
            Contacto: <a href="mailto:soporte@halconesdexalapa.com.mx" class="underline text-blue-500">soporte@halconesdexalapa.com.mx</a>
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
