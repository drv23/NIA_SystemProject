<template>
  <div class="p-6">
    <!-- Header with Back Button -->
    <div class="mb-6">
      <router-link to="/analytics" class="inline-flex items-center text-green-600 hover:text-green-700">
        <span class="material-icons-outlined mr-1">arrow_back</span>
        Back
      </router-link>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-semibold mb-6">Supplies Overview</h1>

    <!-- Search and Filter Bar -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex-1 max-w-xl relative">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search item..."
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500"
        />
      </div>
      <div class="flex gap-4">
        <button class="px-4 py-2 flex items-center gap-2">
          <span class="material-icons-outlined">filter_list</span>
          Filter
        </button>
        <div class="relative">
          <button class="px-4 py-2 rounded-lg border flex items-center gap-2">
            Articles
            <span class="material-icons-outlined">expand_more</span>
          </button>
        </div>
      </div>
    </div>

    <!-- QR Code Modal -->
    <div v-if="showQRModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">QR Code Preview</h3>
          <button @click="closeQRModal" class="text-gray-500 hover:text-gray-700">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>
        
        <div class="flex flex-col items-center">
          <img :src="selectedQR" alt="QR Code" class="w-64 h-64 mb-4"/>
          <p class="text-gray-600 mb-4">{{ selectedItemDescription }}</p>
          <button @click="printQRCode" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center justify-center gap-2">
            <span class="material-icons-outlined">print</span>
            Print QR Code
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex flex-col items-center text-center">
          <span class="material-icons-outlined text-gray-400 text-5xl mb-4">warning</span>
          <h3 class="text-xl font-medium mb-4">Are you sure you want to delete this item?</h3>
          <div class="flex gap-3 w-full">
            <button 
              @click="confirmDelete" 
              class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors"
            >
              Yes, I'm sure
            </button>
            <button 
              @click="closeDeleteModal" 
              class="flex-1 bg-white text-gray-700 px-4 py-2 rounded border border-gray-300 hover:bg-gray-50 transition-colors"
            >
              No, cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="bg-gray-50 text-gray-600 text-sm">
            <th class="w-10 px-4 py-3 text-left">
              <input type="checkbox" class="rounded" />
            </th>
            <th class="px-4 py-3 text-left">QR CODE</th>
            <th class="px-4 py-3 text-left">IMAGE</th>
            <th class="px-4 py-3 text-left">ARTICLE</th>
            <th class="px-4 py-3 text-left">CATEGORY</th>
            <th class="px-4 py-3 text-left">DESCRIPTION</th>
            <th class="px-4 py-3 text-left">PROPERTY ACCOUNT CODE</th>
            <th class="px-4 py-3 text-left">STOCK</th>
            <th class="px-4 py-3 text-left">DATE ACQUIRED</th>
            <th class="px-4 py-3 text-left">ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredSupplies" :key="item.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-3">
              <input type="checkbox" class="rounded" />
            </td>
            <td class="px-4 py-3">
              <img 
                :src="item.qrCode" 
                alt="QR Code" 
                class="w-8 h-8 cursor-pointer transform transition-all duration-200 hover:scale-110 hover:border-2 hover:border-green-500 rounded"
                @click="openQRModal(item)"
              />
            </td>
            <td class="px-4 py-3">
              <img :src="item.image" alt="Item" class="w-8 h-8 object-contain" />
            </td>
            <td class="px-4 py-3">{{ item.article }}</td>
            <td class="px-4 py-3">{{ item.category }}</td>
            <td class="px-4 py-3">{{ item.description }}</td>
            <td class="px-4 py-3">{{ item.propertyCode }}</td>
            <td class="px-4 py-3">{{ item.stock }}</td>
            <td class="px-4 py-3">{{ item.dateAcquired }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <button 
                  @click="openDeleteModal(item)"
                  class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors"
                >
                  <span class="material-icons-outlined">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
      <div class="text-sm text-gray-600">
        Result 1-20 of 160
      </div>
      <div class="flex items-center gap-2">
        <button class="px-3 py-1 border rounded hover:bg-gray-50">&lt; Previous</button>
        <div class="flex">
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 1 }" @click="currentPage = 1">1</button>
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 2 }" @click="currentPage = 2">2</button>
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 3 }" @click="currentPage = 3">3</button>
          <span class="px-3 py-1">...</span>
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 8 }" @click="currentPage = 8">8</button>
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 9 }" @click="currentPage = 9">9</button>
          <button class="px-3 py-1" :class="{ 'bg-green-600 text-white rounded': currentPage === 10 }" @click="currentPage = 10">10</button>
        </div>
        <button class="px-3 py-1 border rounded hover:bg-gray-50">Next &gt;</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const currentPage = ref(1)
const showQRModal = ref(false)
const selectedQR = ref(null)
const selectedItemDescription = ref('')
const showDeleteModal = ref(false)
const itemToDelete = ref(null)

const supplies = ref([
  {
    id: 1,
    qrCode: '/qr-code-1.png',
    image: '/ballpen.png',
    article: 'Supplies',
    category: 'Consumable',
    description: 'Ballpen',
    propertyCode: 'DDN-IMO-F501-223-C-02.05-10',
    stock: '300',
    dateAcquired: '12/28/10'
  },
  {
    id: 2,
    qrCode: '/qr-code-2.png',
    image: '/bondpaper.png',
    article: 'Supplies',
    category: 'Consumable',
    description: 'Bondpaper',
    propertyCode: 'DDN-IMO-F501-223-C-02.05-10',
    stock: '1005',
    dateAcquired: '12/28/10'
  },
  {
    id: 3,
    qrCode: '/qr-code-3.png',
    image: '/flashdrive.png',
    article: 'Supplies',
    category: 'Consumable',
    description: 'flashdrive',
    propertyCode: 'DDN-IMO-F501-223-C-02.05-10',
    stock: '32',
    dateAcquired: '12/28/10'
  }
])

const openQRModal = (item) => {
  selectedQR.value = item.qrCode
  selectedItemDescription.value = item.description
  showQRModal.value = true
}

const closeQRModal = () => {
  showQRModal.value = false
  selectedQR.value = null
  selectedItemDescription.value = ''
}

const printQRCode = () => {
  // Create a new window for printing
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>Print QR Code</title>
      </head>
      <body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;">
        <img src="${selectedQR.value}" style="width: 300px; height: 300px;"/>
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.focus()
  printWindow.print()
  printWindow.close()
}

const openDeleteModal = (item) => {
  itemToDelete.value = item
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  itemToDelete.value = null
}

const confirmDelete = () => {
  if (itemToDelete.value) {
    // Filter out the deleted item
    supplies.value = supplies.value.filter(item => item.id !== itemToDelete.value.id)
    // Close the modal
    closeDeleteModal()
  }
}

const filteredSupplies = computed(() => {
  if (!searchQuery.value) return supplies.value
  
  const query = searchQuery.value.toLowerCase()
  return supplies.value.filter(item => 
    item.description.toLowerCase().includes(query) ||
    item.article.toLowerCase().includes(query) ||
    item.category.toLowerCase().includes(query) ||
    item.propertyCode.toLowerCase().includes(query)
  )
})
</script>

<style scoped>
.material-icons-outlined {
  font-size: 20px;
}

/* Add smooth transitions for modal */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Add transition for delete button hover */
.material-icons-outlined {
  transition: transform 0.2s ease;
}

button:hover .material-icons-outlined {
  transform: scale(1.1);
}
</style> 