<script setup>
import { ref } from 'vue'
import { Bar } from 'vue-chartjs'

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
)

const stats = ref({
  totalItems: '6389',
  category: '3',
  admins: '2'
})

const chartData = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    {
      label: 'Serviceable',
      data: [49, 45, 44, 45, 45, 60],
      backgroundColor: '#10B981'
    },
    {
      label: 'Non-Serviceable',
      data: [20, 20, 20, 25, 25, 25],
      backgroundColor: '#EF4444'
    },
    {
      label: 'Under Maintenance',
      data: [10, 12, 11, 13, 12, 10],
      backgroundColor: '#F59E0B'
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 20
      }
    },
    title: {
      display: true,
      text: 'Item Status Overview',
      padding: {
        bottom: 30
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      }
    },
    y: {
      beginAtZero: true,
      grid: {
        drawBorder: false
      }
    }
  }
})
</script>

<template>
  <div>
    <!-- Welcome Message -->
    <h2 class="text-xl text-gray-600 mb-6">Hello, JGludo</h2>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
      <!-- Total Items -->
      <div class="bg-white rounded-lg p-4 lg:p-6 flex items-center">
        <div class="p-3 bg-orange-50 rounded-lg">
          <span class="material-icons-outlined text-orange-600">inventory_2</span>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-500">Total Items</p>
          <h3 class="text-xl lg:text-2xl font-semibold text-gray-900">{{ stats.totalItems }}</h3>
        </div>
      </div>

      <!-- Category -->
      <div class="bg-white rounded-lg p-4 lg:p-6 flex items-center">
        <div class="p-3 bg-green-50 rounded-lg">
          <span class="material-icons-outlined text-green-600">category</span>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-500">Category</p>
          <h3 class="text-xl lg:text-2xl font-semibold text-gray-900">{{ stats.category }}</h3>
        </div>
      </div>

      <!-- Admins -->
      <div class="bg-white rounded-lg p-4 lg:p-6 flex items-center">
        <div class="p-3 bg-blue-50 rounded-lg">
          <span class="material-icons-outlined text-blue-600">people</span>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-500">Admins</p>
          <h3 class="text-xl lg:text-2xl font-semibold text-gray-900">{{ stats.admins }}</h3>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
      <button class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-700 transition-colors">
        <span class="material-icons-outlined mr-2">qr_code_2</span>
        QR Generation Validation
      </button>
      <button class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-700 transition-colors">
        <span class="material-icons-outlined mr-2">assessment</span>
        Reporting
      </button>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg p-4 lg:p-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 space-y-4 lg:space-y-0">
        <h3 class="text-lg font-semibold text-gray-800">Inventory Condition Overview</h3>
        <div class="flex flex-wrap items-center gap-4">
          <div class="flex items-center">
            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
            <span class="text-sm text-gray-600">Serviceable</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
            <span class="text-sm text-gray-600">Non-Serviceable</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
            <span class="text-sm text-gray-600">Under Maintenance</span>
          </div>
        </div>
      </div>
      <div class="h-[300px] lg:h-[400px]">
        <Bar 
          :data="chartData" 
          :options="chartOptions"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.material-icons-outlined {
  font-size: 24px;
}
</style>