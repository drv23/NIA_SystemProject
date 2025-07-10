<template>
  <div class="usage-overview-container">
    <!-- Back Button -->
    <button @click="goBack" class="back-button mb-4">
      <i class="fas fa-arrow-left mr-2"></i>
      Back
    </button>

    <h1 class="text-2xl font-bold mb-8">Usage of Items Overview</h1>

    <!-- Quarterly Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Q1 Chart -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Jan - Mar Usage</h2>
        <Bar :data="q1Data" :options="barOptions" />
      </div>

      <!-- Q2 Chart -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Apr - Jun Usage</h2>
        <Bar :data="q2Data" :options="barOptions" />
      </div>

      <!-- Q3 Chart -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Jul - Sep Usage</h2>
        <Bar :data="q3Data" :options="barOptions" />
      </div>

      <!-- Q4 Chart -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Oct - Dec Usage</h2>
        <Bar :data="q4Data" :options="barOptions" />
      </div>
    </div>

    <!-- Forecast Section -->
    <div class="bg-white rounded-lg p-6 shadow-sm">
      <h2 class="text-xl font-semibold mb-6">Forecast for Next 3 Months</h2>
      <div class="w-full max-w-md mx-auto">
        <Pie :data="forecastData" :options="pieOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js'
import { Bar, Pie } from 'vue-chartjs'

const router = useRouter()

const goBack = () => {
  router.back()
}

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
)

const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      max: 70,
      grid: {
        color: '#E5E7EB',
        drawBorder: false
      },
      ticks: {
        stepSize: 10
      }
    },
    x: {
      grid: {
        display: false
      }
    }
  },
  plugins: {
    legend: {
      display: false
    }
  }
}

const pieOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}

const items = ['Flashdrives', 'Ballpens', 'Bondpapers']

const q1Data = {
  labels: items,
  datasets: [{
    data: [40, 30, 50],
    backgroundColor: ['#3B82F6', '#34D399', '#F59E0B'],
    barPercentage: 0.5,
    categoryPercentage: 0.7
  }]
}

const q2Data = {
  labels: items,
  datasets: [{
    data: [55, 65, 48],
    backgroundColor: ['#3B82F6', '#34D399', '#F59E0B'],
    barPercentage: 0.5,
    categoryPercentage: 0.7
  }]
}

const q3Data = {
  labels: items,
  datasets: [{
    data: [70, 40, 60],
    backgroundColor: ['#3B82F6', '#34D399', '#F59E0B'],
    barPercentage: 0.5,
    categoryPercentage: 0.7
  }]
}

const q4Data = {
  labels: items,
  datasets: [{
    data: [35, 50, 45],
    backgroundColor: ['#3B82F6', '#34D399', '#F59E0B'],
    barPercentage: 0.5,
    categoryPercentage: 0.7
  }]
}

const forecastData = {
  labels: items,
  datasets: [{
    data: [20, 35, 45],
    backgroundColor: ['#4C51BF', '#059669', '#DC2626']
  }]
}
</script>

<style scoped>
.usage-overview-container {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
  background-color: #f9fafb;
}

.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
}

.bg-white {
  background-color: white;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  height: 400px;
  display: flex;
  flex-direction: column;
}

.text-2xl {
  font-size: 1.75rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 2rem;
}

.text-lg {
  font-size: 1.25rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 1rem;
}

.chart-container {
  flex: 1;
  position: relative;
  width: 100%;
}

.w-full {
  width: 100%;
  height: 300px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.max-w-md {
  max-width: 32rem;
}

.mb-8 {
  margin-bottom: 2rem;
}

.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.back-button {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  background-color: #f3f4f6;
  border: none;
  border-radius: 0.375rem;
  color: #374151;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.back-button:hover {
  background-color: #e5e7eb;
}

.mr-2 {
  margin-right: 0.5rem;
}
</style> 