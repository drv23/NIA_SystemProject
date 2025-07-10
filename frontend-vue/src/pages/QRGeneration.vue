<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showQRModal = ref(false)
const selectedQR = ref(null)

const items = ref([
  {
    qrCode: 'QR1',
    image: '/desktop-icon.png',
    article: 'Desktop',
    category: 'Inventory',
    description: 'Intel Core i5-650 2GB memory',
    propertyAccountCode: 'DDN-IMO-F501-223-C-02.05-10',
    unitValue: '32,200.00',
    dateAcquired: '12/28/10',
    poNumber: '2010-12-0579',
    location: 'Billing/I.Mandin',
    condition: 'A3 Serviceable'
  }
])

const showValidation = ref(false)
const selectedItem = ref(null)
const condition = ref('')
const conditionNumber = ref('')

const specs = ref({
  maxMemorySize: '16.38 GB',
  memoryTypes: 'DDR3 1066/1333',
  maxMemoryChannels: '',
  maxMemoryBandwidth: '21 GB/s',
  physicalAddressExtensions: '36-bit'
})

const openQRModal = (item) => {
  selectedQR.value = item
  showQRModal.value = true
}

const closeQRModal = () => {
  showQRModal.value = false
  selectedQR.value = null
}

const openValidation = (item) => {
  selectedItem.value = item
  showValidation.value = true
}

const goBack = () => {
  showValidation.value = false
  selectedItem.value = null
  condition.value = ''
  conditionNumber.value = ''
}

const navigateBack = () => {
  router.back()
}
</script>

<template>
  <div class="p-6">
    <!-- QR Generation List View -->
    <div v-if="!showValidation">
      <!-- Add back button -->
      <button @click="navigateBack" class="mb-6 flex items-center text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
        <span class="material-icons-outlined mr-1">arrow_back</span>
        Back
      </button>

      <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">QR Generation</h2>
        <p class="text-gray-600">Manage and generate QR codes for inventory items</p>
      </div>

      <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  <input type="checkbox" class="rounded border-gray-300">
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  QR Code
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Item
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Article
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Category
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Description
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Property Account Code
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Unit Value
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in items" :key="item.propertyAccountCode">
                <td class="px-6 py-4 whitespace-nowrap">
                  <input type="checkbox" class="rounded border-gray-300">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div 
                    class="qr-code-container cursor-pointer transition-transform hover:scale-110"
                    @click="openQRModal(item)"
                  >
                    <img 
                      :src="item.qrCode" 
                      alt="QR Code" 
                      class="w-12 h-12 object-contain border-2 border-transparent hover:border-green-500 rounded"
                    >
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <img 
                    :src="item.image" 
                    alt="Item Image" 
                    class="w-12 h-12 object-contain rounded"
                  >
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.article }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.propertyAccountCode }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.unitValue }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button 
                    @click="openValidation(item)"
                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
                  >
                    Validate
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- QR Code Modal -->
      <div v-if="showQRModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" @click="closeQRModal">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>

          <div class="inline-block bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm w-full">
            <div class="p-6">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-gray-900">QR Code Preview</h3>
                <button 
                  @click="closeQRModal" 
                  class="text-gray-400 hover:text-gray-500"
                >
                  <span class="material-icons-outlined">close</span>
                </button>
              </div>
              
              <div class="flex flex-col items-center">
                <img 
                  :src="selectedQR?.qrCode" 
                  alt="QR Code Large" 
                  class="w-48 h-48 object-contain mb-4"
                >
                <p class="text-center text-gray-600 mb-6">{{ selectedQR?.description }}</p>
                
                <button 
                  class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-md flex items-center justify-center space-x-2 text-sm"
                >
                  <span class="material-icons-outlined text-xl">print</span>
                  <span>Print QR Code</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Validation View -->
    <div v-else>
      <!-- Back Button -->
      <button @click="goBack" class="mb-6 flex items-center text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
        <span class="material-icons-outlined mr-1">arrow_back</span>
        Back
      </button>

      <!-- Title -->
      <h1 class="text-2xl font-semibold text-green-600 mb-8">{{ selectedItem?.description }}</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Left Column -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-green-600 text-xl font-medium mb-4">QR Code Calendar Year 2024</h2>
          
          <div class="flex justify-center mb-4">
            <img :src="selectedItem?.qrCode" alt="QR Code" class="w-48 h-48">
          </div>
          
          <p class="text-center text-gray-700 mb-6">{{ selectedItem?.propertyAccountCode }}</p>
          
          <div class="mb-6">
            <h3 class="font-medium mb-2">Specs:</h3>
            <div class="bg-gray-50 rounded p-4">
              <p>Max Memory Size (dependent on memory type): {{ specs.maxMemorySize }}</p>
              <p>Memory Types: {{ specs.memoryTypes }}</p>
              <p>Max # of Memory Channels.</p>
              <p>Max Memory Bandwidth: {{ specs.maxMemoryBandwidth }}</p>
              <p>Physical Address Extensions: {{ specs.physicalAddressExtensions }}</p>
            </div>
          </div>

          <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            EDIT
          </button>
          <div>
              <label class="block text-gray-700 mt-2">Condition:</label>
              <select v-model="condition" class="w-full border rounded p-2">
                <option value="">Select condition</option>
                <option value="serviceable">Serviceable</option>
                <option value="unserviceable">Unserviceable</option>
              </select>
            </div>

            <div>
              <label class="block text-gray-700 mt-2">Condition number:</label>
              <select v-model="conditionNumber" class="w-full border rounded p-2">
                <option value="">Select condition number</option>
                <option value="A1">A1</option>
                <option value="A2">A2</option>
                <option value="A3">A3</option>
              </select>
            </div>

            <button class="w-full mt-3 bg-green-600 text-white py-2 rounded hover:bg-green-700">
              VALIDATE
            </button>
        </div>

        <!-- Right Column -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-gray-700 mb-4">New QR Code:</h2>
          <h3 class="text-green-600 text-xl font-medium mb-4">QR Code Calendar Year 2025</h3>
          
          <div class="flex justify-center mb-4">
            <img :src="selectedItem?.qrCode" alt="New QR Code" class="w-48 h-48">
          </div>
          
          <p class="text-center text-gray-700 mb-6">{{ selectedItem?.propertyAccountCode }}</p>

          <div class="space-y-4">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.material-icons-outlined {
  font-size: 20px;
}

.qr-code-container {
  transition: transform 0.2s ease-in-out;
}

.qr-code-container:hover {
  transform: scale(1.1);
}
</style> 