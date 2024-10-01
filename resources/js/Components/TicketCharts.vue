<script setup>
import { Bar, Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LineElement, PointElement, LinearScale } from 'chart.js'
import { ref } from 'vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, PointElement, CategoryScale, LinearScale);

const chartData = ref({
  labels: ['January', 'February', 'March'],
  datasets: [
    {
      label: 'Data One',
      backgroundColor: '#984CDC',
      borderColor: '#eab308',
      borderRadius: 10,
      data: [35, 45, 15]
    }
  ]
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    tooltip: {
      enabled: true
    }
  },
  scales: {
    y: {
      beginAtZero: true
    }
  }
});

const chartData2 = ref({
  labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
  datasets: [
    {
      label: 'Boletos comprados en los ultimos 4 meses',
      backgroundColor: 'rgba(128, 0, 128, 0.2)',
      borderColor: '#A654F4',
      borderWidth: 2,
      pointBackgroundColor: '#E3CCE3',
      pointBorderColor: '#E3CCE3',
      pointBorderWidth: 2,
      pointRadius: 5,
      borderRadius: 10,
      pointHoverRadius: 7,
      data: [1, 5, 2, 3]
    }
  ]
});

const chartOptions2 = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    tooltip: {
      enabled: true
    }
  },
  scales: {
    y: {
      beginAtZero: true
    }
  }
});
</script>

<template>
    <div class="tw-h-[300px]">
        <Bar :data="chartData2" :options="chartOptions2" />
    </div>
</template>

<style scoped>

</style>
