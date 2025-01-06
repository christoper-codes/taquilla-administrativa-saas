<script setup>
import { ref, watch } from 'vue'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

const props = defineProps({
  saleTicketsPerWeek: {
    type: Object,
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
  plugins: {
    title: {
      display: true,
      text: 'Boletos Vendidos por Semana',
    },
    tooltip: {
      enabled: true,
    },
  }
})


watch(() => props.saleTicketsPerWeek, (newData) => {
  chartData.value.labels = newData.weeks.map((week, index) => `Semana ${index + 1}`)
  chartData.value.datasets[0].data = newData.weeks.map(week => week.tickets)
}, { immediate: true })

</script>

<template>
    <div class="tw-flex tw-items-start tw-justify-between tw-gap-7">
        <div class="tw-w-2/3 tw-shadow-g">
            <Line :data="chartData" :options="chartOptions" class="!tw-w-full !tw-h-auto" />
        </div>
        <div class="tw-shadow-lg tw-w-1/3 tw-h-60">

        </div>
    </div>
</template>

  <style scoped>

  </style>
