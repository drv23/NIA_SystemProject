<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const specs = ref({
  maxMemorySize: '16.38 GB',
  memoryTypes: 'DDR3 1066/1333',
  maxMemoryChannels: '',
  maxMemoryBandwidth: '21 GB/s',
  physicalAddressExtensions: '36-bit'
})

const condition = ref('')
const conditionNumber = ref('')
const itemId = ref('')

onMounted(() => {
  itemId.value = route.params.id
  if (!itemId.value) {
    router.push({ name: 'QRGeneration' })
    return
  }
  console.log('Mounted ItemValidation with ID:', itemId.value)
})

const goBack = () => {
  router.push({ name: 'QRGeneration' })
}
</script>

<template>
  <div class="p-4">
    <!-- Back Button -->
    <button @click="goBack" class="mb-4 flex items-center text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-sm">
      <span class="material-icons-outlined mr-1">arrow_back</span>
      Back
    </button>

    <!-- Title -->
    <h1 class="text-xl font-medium text-green-600 mb-6">Intel Core i5-650 2GB memory</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left Column -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-green-600 text-lg font-medium mb-4">QR Code Calendar Year 2024</h2>
        
        <div class="flex justify-center mb-4">
          <img src="/qr-code.png" alt="QR Code" class="w-40 h-40">
        </div>
        
        <p class="text-center text-gray-700 mb-4">2010-12-0579</p>
        
        <div class="mb-4">
          <p class="mb-2">Specs:</p>
          <div class="bg-gray-100 rounded p-4 text-sm space-y-1">
            <p>Max Memory Size (dependent on memory type): 16.38 GB.</p>
            <p>Memory Types: DDR3 1066/1333.</p>
            <p>Max # of Memory Channels.</p>
            <p>Max Memory Bandwidth: 21 GB/s.</p>
            <p>Physical Address Extensions: 36-bit.</p>
          </div>
        </div>

        <button class="w-full bg-green-600 text-white py-3 rounded font-medium text-base">
          EDIT
        </button>
      </div>

      <!-- Right Column -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-700 mb-2">New QR Code:</p>
        <h2 class="text-green-600 text-lg font-medium mb-4">QR Code Calendar Year 2025</h2>
        
        <div class="flex justify-center mb-4">
          <img src="/qr-code.png" alt="New QR Code" class="w-40 h-40">
        </div>
        
        <p class="text-center text-gray-700 mb-6">2010-12-0898</p>

        <div class="space-y-4">
          <div>
            <label class="block text-gray-700 mb-2">Condition:</label>
            <div class="relative">
              <select v-model="condition" class="w-full border rounded p-2 pr-8 appearance-none bg-white">
                <option value="">Select condition</option>
                <option value="serviceable">Serviceable</option>
                <option value="unserviceable">Unserviceable</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-gray-700 mb-2">Condition number:</label>
            <div class="relative">
              <select v-model="conditionNumber" class="w-full border rounded p-2 pr-8 appearance-none bg-white">
                <option value="">Select condition number</option>
                <option value="A1">A1</option>
                <option value="A2">A2</option>
                <option value="A3">A3</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <button class="w-full bg-green-600 text-white py-2 rounded font-medium">
            VALIDATE
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.material-icons-outlined {
  font-size: 18px;
}
</style> 