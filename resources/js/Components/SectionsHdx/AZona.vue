<script setup>
import { onMounted } from 'vue';


const props = defineProps({
   seats: {
       type: Array,
       required: true,
   },
});

onMounted(() => {
  props.seats.forEach(seat => {
    const elemento = document.getElementById(seat.code);
    if (elemento) {
      switch (seat.status) {
        case 'disponible':
          elemento.classList.add('tw-cursor-pointer', 'tw-fill-green-500');
          elemento.classList.remove('tw-cursor-not-allowed', 'tw-fill-purple-500', 'tw-fill-red-500');
          break;
        case 'vendido':
          elemento.classList.add('tw-cursor-not-allowed', 'tw-fill-pink-600');
          elemento.classList.remove('tw-cursor-pointer', 'tw-fill-green-500', 'tw-fill-red-500');
          break;
        case 'bloqueado':
          elemento.classList.add('tw-cursor-not-allowed', 'tw-fill-red-500');
          elemento.classList.remove('tw-cursor-pointer', 'tw-fill-green-500', 'tw-fill-yellow-500');
          break;
      }
    }
  });
});

</script>

<template>

<svg v-for="seat in seats" :key="seat.code" class="tw-w-full tw-h-[450px]" id="Capa_1" data-name="Capa 1" viewBox="0 0 983 242" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <clipPath id="clip-path">
      <polyline class="cls-1" points="680.58 -487.43 680.58 -460.4 1645.58 -460.4 1645.58 -487.43"></polyline>
    </clipPath>
  </defs>
  <rect id="FA1" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="29.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect id="FA2" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="47.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect id="FA3" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="65.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect id="FA4" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="83.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect id="FA5" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="101.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect id="FA6" class="hover:tw-scale-[103%] tw-transition-transform tw-duration-300" x="119.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="29.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="47.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="65.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="83.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="101.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="119.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="137.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="155.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="173.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="191.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="209.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="227.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="245.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="263.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="281.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="299.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="317.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="335.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="353.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="371.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="389.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="407.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="425.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="443.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="461.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="479.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="497.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="515.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="533.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="551.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="569.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="587.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="605.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <path class="cls-5" d="M626.56,206.68c-1.93,0-3.5-.93-3.5-2.08V53a1.75,1.75,0,0,1,1-1.45,4.83,4.83,0,0,1,2.5-.63H646.3c1.93,0,3.5.93,3.5,2.08V204.6c0,1.15-1.57,2.08-3.5,2.08Z"></path>
  <rect class="cls-6" x="622.94" y="158.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="149.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="140.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="131.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="113.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="104.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="95.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="86.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="77.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="68.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="122.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="59.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.94" y="50.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.9" y="203.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.9" y="194.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.9" y="185.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.9" y="176.8" width="26.98" height="3"></rect>
  <rect class="cls-6" x="622.9" y="167.8" width="26.98" height="3"></rect>
  <rect class="cls-2" x="623.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="641.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="54.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="72.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="659.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="677.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="695.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="713.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="731.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="749.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="767.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="785.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="803.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="821.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="839.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="857.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="875.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="893.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="911.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="90.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="929.2" y="216.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="108.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="126.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="144.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="162.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="180.2" width="8.6" height="8.6" rx="2.21"></rect>
  <rect class="cls-2" x="947.2" y="198.2" width="8.6" height="8.6" rx="2.21"></rect>
  <polyline class="cls-7" points="335.02 45 335.02 81 245 81 245 72 326 72 326 45"></polyline>
  <polygon class="cls-7" points="20 234 20 45 947 45 947 81 964.91 81 964.91 234 974.17 234 974.17 72 956 72 956 36.04 10.78 36.04 10.78 234 20 234"></polygon>
  <polyline class="cls-7" points="649.92 45 649.92 80.8 722 80.8 722 72 659 72 659 45"></polyline>
  <path class="cls-8" d="M338.64,206.88c-1.93,0-3.5-1-3.5-2.32V36.44a2,2,0,0,1,1-1.62,4.45,4.45,0,0,1,2.49-.7h19.74c1.93,0,3.5,1.05,3.5,2.32V204.56c0,1.27-1.57,2.32-3.5,2.32Z"></path>
  <rect class="cls-6" x="335.02" y="159" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="150" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="141" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="132" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="114" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="105" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="96" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="87" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="78" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="69" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="123" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="60" width="26.98" height="3"></rect>
  <rect class="cls-6" x="335.02" y="51" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.98" y="203.99" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.98" y="194.99" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.98" y="185.99" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.98" y="176.99" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.98" y="167.99" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.91" y="42" width="26.98" height="3"></rect>
  <rect class="cls-6" x="334.91" y="33" width="26.98" height="3"></rect>
  <polygon class="cls-9" points="245 72 326 72 326 34 299 34 299 45 245 45 245 72"></polygon>
  <rect class="cls-6" x="269.19" y="56.81" width="26.63" height="3" transform="translate(224.19 340.81) rotate(-90)"></rect>
  <rect class="cls-6" x="260.19" y="56.81" width="26.63" height="3" transform="translate(215.19 331.81) rotate(-90)"></rect>
  <rect class="cls-6" x="251.19" y="56.81" width="26.63" height="3" transform="translate(206.19 322.81) rotate(-90)"></rect>
  <rect class="cls-6" x="242.19" y="56.81" width="26.63" height="3" transform="translate(197.19 313.81) rotate(-90)"></rect>
  <rect class="cls-6" x="233.19" y="56.81" width="26.63" height="3" transform="translate(188.19 304.81) rotate(-90)"></rect>
  <rect class="cls-6" x="278.19" y="56.81" width="26.63" height="3" transform="translate(233.19 349.81) rotate(-90)"></rect>
  <rect class="cls-6" x="299.25" y="42" width="26.5" height="3"></rect>
  <polygon class="cls-9" points="721.97 71.73 659 71.73 659 33.73 679.99 33.73 679.99 44.73 721.97 44.73 721.97 71.73"></polygon>
  <rect class="cls-6" x="671.29" y="56.68" width="26.36" height="3" transform="translate(626.29 742.65) rotate(-90)"></rect>
  <rect class="cls-6" x="680.29" y="56.68" width="26.36" height="3" transform="translate(635.29 751.65) rotate(-90)"></rect>
  <rect class="cls-6" x="689.29" y="56.68" width="26.36" height="3" transform="translate(644.29 760.65) rotate(-90)"></rect>
  <rect class="cls-6" x="698.29" y="56.68" width="26.36" height="3" transform="translate(653.29 769.65) rotate(-90)"></rect>
  <rect class="cls-6" x="707.29" y="56.68" width="26.36" height="3" transform="translate(662.29 778.65) rotate(-90)"></rect>
  <rect class="cls-6" x="659.19" y="41.73" width="20.6" height="3" transform="translate(1338.99 86.46) rotate(-180)"></rect>
  <polyline class="cls-9" points="10.78 36.04 10.78 9.04 956 9.04 956 36.04"></polyline>
  <g class="cls-10">
    <rect class="cls-11" x="681.04" y="-483.25" width="964.55" height="575.66"></rect>
    <rect class="cls-12" x="681.04" y="72.72" width="175.17" height="19.7"></rect>
    <rect class="cls-13" x="855.5" y="72.72" width="175.17" height="19.7"></rect>
    <rect class="cls-12" x="681.04" y="34.36" width="81.1" height="19.69"></rect>
    <rect class="cls-14" x="723.21" y="53.02" width="175.18" height="19.7"></rect>
    <rect class="cls-15" x="896.23" y="53.02" width="175.17" height="19.7"></rect>
    <rect class="cls-15" x="763.23" y="34.36" width="175.18" height="19.69"></rect>
    <rect class="cls-14" x="936.96" y="34.36" width="175.18" height="19.69"></rect>
    <rect class="cls-14" x="905.96" y="-2.96" width="175.18" height="19.7"></rect>
    <rect class="cls-15" x="732.94" y="-2.96" width="175.17" height="19.7"></rect>
    <rect class="cls-12" x="865.95" y="15.7" width="175.17" height="19.7"></rect>
    <rect class="cls-14" x="692.22" y="15.7" width="175.18" height="19.7"></rect>
    <path class="cls-16" d="M1613,55.26V-449.91H713.6v187.3H943.18V-132H713.6V55.26ZM1612.71-132H1383.13V-262.61h229.58Z"></path>
    <path class="cls-17" d="M1615.11,57.34V-452H711.52V57.34Zm-229.9-191.46V-260.53h.63c1.59,0,3.21.06,4.81.18v-.18H1401l-.41,1.72a62.23,62.23,0,0,1,9.47,3.06l1.59-3.84c-.85-.34-1.73-.63-2.59-.94h201.58V-134.1H1409.05c.83-.3,1.68-.57,2.5-.91l-1.59-3.84a63,63,0,0,1-9.47,3l.41,1.7h-10.24v-.18c-1.6.12-3.22.18-4.81.18Zm-4.16-.18A63.43,63.43,0,0,1,1323.7-185c-.29-4.08-.49-8.18-.49-12.33s.2-8.25.49-12.32a63.43,63.43,0,0,1,57.35-50.7Zm-55-94.33c14.83-81.46,86.4-143.44,172.25-143.44H1611v107.38H1493.59v-8.82h-8.31v8.82h-23.36v-8.82h-4.18v8.82h-25.42v-8.82h-4.18v8.82h-25.43v-8.82h-4.18v8.82h-17.48v.19A67.61,67.61,0,0,0,1326.09-228.63ZM1611-129.95V-22.57H1498.34c-85.85,0-157.42-62-172.25-143.45a67.6,67.6,0,0,0,55,35.88v.19h17.48v7.79h4.18v-7.79h25.43v7.79h4.18v-7.79h25.42v7.79h4.18v-7.79h23.36v7.79h8.31v-7.79Zm-449.82,47.3c-62.38-1.18-112.77-52.15-112.77-114.67S1098.75-310.81,1161.13-312ZM1165.5-312c62.38,1.17,112.77,52.13,112.77,114.66S1227.88-83.83,1165.5-82.65Zm-449.82,182h117v7.79H841v-7.79h23.36v7.79h4.18v-7.79H894v7.79h4.18v-7.79H923.6v7.79h4.18v-7.79h17.48v-.19a67.59,67.59,0,0,0,55-35.88C985.4-84.55,913.82-22.57,828-22.57H715.68ZM941.1-260.53v126.41l-.62,0c-1.6,0-3.22-.06-4.82-.18v.18H925.42l.4-1.7a63,63,0,0,1-9.47-3L914.76-135c.82.34,1.66.61,2.5.91H715.68V-260.54H917.26c-.86.31-1.74.6-2.58.94l1.59,3.84a61.52,61.52,0,0,1,9.47-3.06l-.42-1.72h10.33v.18c1.6-.12,3.22-.18,4.82-.18Zm4.16.19a63.42,63.42,0,0,1,57.35,50.7c.29,4.07.49,8.17.49,12.32s-.2,8.25-.49,12.33a63.42,63.42,0,0,1-57.35,50.69Zm-229.58-4.35V-372.07H828c85.85,0,157.43,62,172.25,143.44a67.6,67.6,0,0,0-55-35.87v-.19H927.78v-8.82H923.6v8.82H898.17v-8.82H894v8.82H868.57v-8.82h-4.18v8.82H841v-8.82h-8.31v8.82Zm0-183.14h445.45v131.7c-64.67,1.17-116.93,54-116.93,118.81s52.26,117.64,116.93,118.81V53.19H715.68V-18.42H828c94.52,0,172.13-73.38,178.77-166a67.22,67.22,0,0,0,1.24-12.85,67.14,67.14,0,0,0-1.24-12.85C1000.1-302.83,922.49-376.22,828-376.22H715.68Zm895.27,0v71.61H1498.34c-94.52,0-172.14,73.39-178.76,166.05a67.14,67.14,0,0,0-1.25,12.85,67.22,67.22,0,0,0,1.25,12.85c6.62,92.67,84.24,166.05,178.76,166.05H1611V53.19H1165.5V-78.51c64.67-1.17,116.93-54,116.93-118.81S1230.17-315,1165.5-316.13v-131.7Z"></path>
  </g>
</svg>

</template>

<style scoped>
.cls-1{fill:none;}
.cls-2,.cls-4{fill:#fdba4d;}
.cls-3,.cls-7{fill:#3c1053;}
.cls-4,.cls-9{stroke:#3c1053;}
.cls-4,.cls-7,.cls-9{stroke-miterlimit:10;}
.cls-4{stroke-width:0.4px;}
.cls-5{fill:#bcbcbc;}
.cls-6{fill:#7f7f7f;}
.cls-7{stroke:#000;stroke-width:0.25px;}
.cls-8,.cls-9{fill:#cecece;}
.cls-9{stroke-width:0.5px;}
.cls-10{clip-path:url(#clip-path);}
.cls-11{fill:#c2813a;}
.cls-12{fill:#b77132;}
.cls-13{fill:#cd8c46;}
.cls-14{fill:#c06e38;}
.cls-15{fill:#c57c38;}
.cls-16,.cls-17{fill:#fff;}
.cls-16{opacity:0.3;}
.reservado{fill:#ff000d;}
</style>
