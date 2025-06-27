<script setup>
import { ref, watch } from 'vue'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Interaction } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

const props = defineProps({
    salesPerDay: {
        type: Array,
        required: true
    }
})

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: 'Boletos vendidos',
      data: [],
      borderColor: 'rgba(255, 99, 132, 1)',
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      pointStyle: 'circle',
      pointRadius: 15,
      pointHoverRadius: 15,
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  animation: false,
  hover: {
    animationDuration: 0
  },
  plugins: {
    title: {
      display: true,
      text: 'Boletos Vendidos por día',
    },
    tooltip: {
      enabled: true,
      animation: false,
      displayColors: false,
      backgroundColor: 'rgba(0,0,0,0.8)',
      titleColor: 'white',
      bodyColor: 'white'
    },
    legend: {
      display: true
    }
  },
})

watch(() => props.salesPerDay, (newData) => {
  chartData.value.labels = newData.map((dayData, index) => `Dia ${index + 1}`);
  chartData.value.datasets[0].data = newData.map(dayData => dayData.tickets);
}, { immediate: true });

</script>

<template>
    <div class="tw-w-full tw-h-auto">
        <div class="tw-w-full">
            <Line :data="chartData" :options="chartOptions" class="!tw-w-full !tw-h-auto" />
        </div>
    </div>
</template>
