<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
  router.back()
}

const navigateToReport = (categoryType) => {
  // All categories navigate to the desktop monitoring page with the category type as a parameter
  router.push(`/reports/desktop/${categoryType.toLowerCase()}`)
}

const categories = ref([
  {
    name: 'Desktop',
    icon: 'computer',
    type: 'desktop'
  },
  {
    name: 'Laptop',
    icon: 'laptop',
    type: 'laptop'
  },
  {
    name: 'Printers',
    icon: 'print',
    type: 'printers'
  },
  {
    name: 'Equipments',
    icon: 'build',
    type: 'equipments'
  }
])
</script>

<template>
  <div>
    <!-- Back Button -->
    <button 
      @click="goBack"
      class="mb-6 px-4 py-2 bg-green-600 text-white rounded-lg flex items-center hover:bg-green-700 transition-colors"
    >
      <span class="material-icons-outlined mr-2">arrow_back</span>
      Back
    </button>

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reporting</h2>

    <!-- Category Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div 
        v-for="category in categories" 
        :key="category.name"
        class="bg-white rounded-lg p-6 flex flex-col items-center"
      >
        <div class="p-4 bg-green-50 rounded-full mb-4">
          <span class="material-icons-outlined text-green-600 text-3xl">{{ category.icon }}</span>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ category.name }}</h3>
        <button 
          @click="navigateToReport(category.type)"
          class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-700 transition-colors"
        >
          <span class="material-icons-outlined mr-2">visibility</span>
          View
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.material-icons-outlined {
  font-size: 24px;
}
</style> 