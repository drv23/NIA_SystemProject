<template>
  <div class="analytics-container">
    <div class="header flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold">Predictive Analytics Overview</h1>
      <div class="flex gap-4">
        <select v-model="timeRange" class="px-4 py-2 border rounded-lg">
          <option value="3">Last 3 months</option>
          <option value="6">Last 6 months</option>
          <option value="12">Last 12 months</option>
        </select>
        <select v-model="selectedCategory" class="px-4 py-2 border rounded-lg">
          <option value="all">All Categories</option>
          <option value="supply">Supply</option>
          <option value="ict">ICT</option>
        </select>
        <select v-model="selectedStatus" class="px-4 py-2 border rounded-lg">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="low">Low Stock</option>
        </select>
      </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Total Consumables -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
            <span class="material-icons-outlined text-blue-600">description</span>
          </div>
          <div>
            <p class="text-gray-600 mb-2">Total Consumables Used</p>
            <div class="flex flex-col">
              <h3 class="text-2xl font-semibold mb-2">1,234 Items</h3>
              <router-link :to="{ name: 'SuppliesOverview' }" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm w-fit hover:bg-green-700">View</router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Predicted Usage -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
            <span class="material-icons-outlined text-green-600">trending_up</span>
          </div>
          <div>
            <p class="text-gray-600 mb-2">Predicted Usage Next Month</p>
            <div class="flex flex-col">
              <h3 class="text-2xl font-semibold mb-2">450 units</h3>
              <router-link :to="{ name: 'UsageOverview' }" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm w-fit hover:bg-green-700">View</router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Maintenance Forecast -->
      <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
            <span class="material-icons-outlined text-yellow-600">build</span>
          </div>
          <div>
            <p class="text-gray-600 mb-2">Maintenance Forecast</p>
            <h3 class="text-2xl font-semibold">12 items</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Section -->
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-lg">
      <div class="flex justify-between items-start">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="material-icons-outlined text-red-500">warning</span>
            <p class="font-medium text-gray-900">Paper (A4) - Only 12 reams left</p>
          </div>
          <p class="text-gray-600 ml-8">Ballpens - Low stock (50 pcs remaining)</p>
          <p class="text-gray-600 ml-8">Flash Drives - Critical (5 pcs remaining)</p>
        </div>
        <div class="flex gap-4">
          <router-link :to="{ name: 'Inventory' }" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">View Inventory</router-link>
          <button @click="showRestockModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-block">Restock</button>
        </div>
      </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Usage Trends -->
      <div class="bg-white rounded-lg p-6 shadow-sm h-[400px]">
        <h2 class="text-lg font-semibold mb-4">Usage Trends</h2>
        <div class="h-[300px]">
          <Line :data="usageTrendsData" :options="lineOptions" />
        </div>
      </div>

      <!-- Most Frequently Used Items -->
      <div class="bg-white rounded-lg p-6 shadow-sm h-[400px]">
        <h2 class="text-lg font-semibold mb-4">Most Frequently Used Items</h2>
        <div class="h-[300px]">
          <Bar :data="frequentItemsData" :options="barOptions" />
        </div>
      </div>
    </div>

    <!-- Forecast Projection -->
    <div class="bg-white rounded-lg p-6 shadow-sm mb-8 h-[400px]">
      <h2 class="text-lg font-semibold mb-4">Forecast Projection</h2>
      <div class="h-[300px]">
        <Line :data="forecastData" :options="forecastOptions" />
      </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg p-6 shadow-sm">
      <table class="w-full">
        <thead>
          <tr class="text-left">
            <th class="pb-4">ITEM NAME</th>
            <th class="pb-4">CATEGORY</th>
            <th class="pb-4">CURRENT STOCK</th>
            <th class="pb-4">MONTHLY AVG USAGE</th>
            <th class="pb-4">PREDICTED SHORTAGE DATE</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in inventoryItems" :key="item.name" class="border-t">
            <td class="py-4">{{ item.name }}</td>
            <td>{{ item.category }}</td>
            <td>{{ item.currentStock }}</td>
            <td>{{ item.monthlyUsage }}</td>
            <td>{{ item.shortageDate }}</td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-between mt-6">
        <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Export Excel</button>
        <div class="flex gap-4">
          <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Previous</button>
          <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Next</button>
        </div>
      </div>
    </div>

    <!-- Restock Modal -->
    <div v-if="showRestockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-xl w-full mx-4">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg text-green-600 font-medium">Restock item</h2>
          <button @click="showRestockModal = false" class="text-gray-500 hover:text-gray-700">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <form @submit.prevent="handleRestock" class="space-y-6">
          <!-- Paper -->
          <div class="bg-white p-4 rounded-lg shadow-sm">
            <label class="block text-green-600 font-medium mb-2">Paper</label>
            <div>
              <label class="block text-gray-600 mb-1">Quantity:</label>
              <input 
                v-model="restockData.paper"
                type="number"
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                min="0"
              />
            </div>
          </div>

          <!-- Ballpens -->
          <div class="bg-white p-4 rounded-lg shadow-sm">
            <label class="block text-green-600 font-medium mb-2">Ballpens</label>
            <div>
              <label class="block text-gray-600 mb-1">Quantity:</label>
              <input 
                v-model="restockData.ballpens"
                type="number"
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                min="0"
              />
            </div>
          </div>

          <!-- Flashdrive -->
          <div class="bg-white p-4 rounded-lg shadow-sm">
            <label class="block text-green-600 font-medium mb-2">Flashdrive</label>
            <div>
              <label class="block text-gray-600 mb-1">Quantity:</label>
              <input 
                v-model="restockData.flashdrive"
                type="number"
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                min="0"
              />
            </div>
          </div>

          <div class="flex justify-end">
            <button 
              type="submit"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            >
              Add
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Line, Bar } from 'vue-chartjs'

const router = useRouter()
const showRestockModal = ref(false)
const restockData = ref({
  paper: 0,
  ballpens: 0,
  flashdrive: 0
})

const handleRestock = async () => {
  try {
    // TODO: Implement API call to update stock quantities
    console.log('Submitting restock:', restockData.value)
    
    // Close modal after successful submission
    showRestockModal.value = false
    
    // Reset form data
    restockData.value = {
      paper: 0,
      ballpens: 0,
      flashdrive: 0
    }
  } catch (error) {
    console.error('Error restocking items:', error)
  }
}

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend
)

// Filter states
const timeRange = ref('3')
const selectedCategory = ref('all')
const selectedStatus = ref('all')

// Chart Data
const usageTrendsData = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [{
    label: 'Total Usage',
    data: [450, 500, 480, 550, 600, 580, 620, 650, 700, 680, 720, 750],
    borderColor: '#10B981',
    backgroundColor: 'rgba(16, 185, 129, 0.1)',
    fill: true,
    tension: 0.4,
    borderWidth: 2,
    pointRadius: 4,
    pointBackgroundColor: '#10B981'
  }]
})

const frequentItemsData = ref({
  labels: ['Printer Ink', 'Paper', 'Ballpens', 'Flash Drives', 'Ethernet Cables'],
  datasets: [{
    label: 'Units Consumed',
    data: [800, 1150, 950, 450, 380],
    backgroundColor: ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6'],
    borderRadius: 4
  }]
})

const forecastData = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [{
    label: 'Actual Usage',
    data: [450, 520, 480, 550, 600, 580, 620, 650, 700, 680, 720, 750],
    borderColor: '#10B981',
    backgroundColor: '#10B981',
    tension: 0.4,
    pointRadius: 4,
    borderWidth: 2
  }]
})

// Chart Options
const lineOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          size: 12
        }
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        drawBorder: false,
        color: '#E5E7EB'
      },
      ticks: {
        font: {
          size: 12
        }
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 12
        }
      }
    }
  }
})

const barOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        drawBorder: false,
        color: '#E5E7EB'
      },
      ticks: {
        font: {
          size: 12
        }
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 12
        }
      }
    }
  }
})

const forecastOptions = ref({
  ...lineOptions.value,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          size: 12
        }
      }
    }
  }
})

// Table Data
const inventoryItems = ref([
  {
    name: 'Printer Ink',
    category: 'Supply',
    currentStock: '15 units',
    monthlyUsage: '8 units',
    shortageDate: 'July 2025'
  },
  {
    name: 'Ethernet Cable',
    category: 'ICT',
    currentStock: '10 pcs',
    monthlyUsage: '5 pcs',
    shortageDate: 'August 2025'
  }
])

onMounted(() => {
  // Fetch initial data
})
</script>

<style scoped>
.analytics-container {
  padding: 1.5rem;
}

.material-icons-outlined {
  font-size: 24px;
}
</style>