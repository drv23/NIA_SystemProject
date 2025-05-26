<script setup>
import { ref, computed } from 'vue'
import QRCode from 'qrcode'

const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(8)
const totalItems = 160

const generateQRCode = async (text) => {
  try {
    return await QRCode.toDataURL(text, {
      width: 32,
      margin: 0,
      color: {
        dark: '#000000',
        light: '#ffffff'
      }
    })
  } catch (err) {
    console.error(err)
    return ''
  }
}

// Sample data matching the image
const inventoryItems = ref([
  {
    qrCode: 'DDN-IMO-F501-223-C-02.05-10',
    image: '/images/desktop.png',
    article: 'Desktop',
    category: 'Inventory',
    description: 'Intel Core i5-650 2GB memory',
    propertyAccountCode: 'DDN-IMO-F501-223-C-02.05-10',
    unitValue: '32,200.00',
    dateAcquired: '12/28/10',
    pc: '20'
  },
  // Duplicate the same item 7 more times to match the image
  ...Array(7).fill().map(() => ({
    qrCode: 'DDN-IMO-F501-223-C-02.05-10',
    image: '/images/desktop.png',
    article: 'Desktop',
    category: 'Inventory',
    description: 'Intel Core i5-650 2GB memory',
    propertyAccountCode: 'DDN-IMO-F501-223-C-02.05-10',
    unitValue: '32,200.00',
    dateAcquired: '12/28/10',
    pc: '20'
  }))
])

const filteredItems = computed(() => {
  return inventoryItems.value.filter(item => {
    return Object.values(item).some(value => 
      value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredItems.value.slice(start, end)
})

const totalPages = computed(() => Math.ceil(totalItems / itemsPerPage.value))

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
</script>

<template>
  <div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold text-green-700">Inventory</h1>
      <div class="flex items-center gap-2">
        <button class="btn-primary">
          <span class="material-icons-outlined text-lg mr-1">print</span>
          Print
        </button>
        <button class="btn-primary">
          <span class="material-icons-outlined text-lg mr-1">add</span>
          Add Supply
        </button>
        <button class="btn-primary">
          <span class="material-icons-outlined text-lg mr-1">add</span>
          Add New
        </button>
      </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="flex justify-between items-center">
      <div class="relative w-96">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search item..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500"
        >
      </div>
      <div class="flex items-center gap-2">
        <button class="btn-secondary">
          <span class="material-icons-outlined text-lg mr-1">filter_alt</span>
          Filter
        </button>
        <button class="btn-secondary">
          Articles
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr class="bg-gray-50">
            <th class="w-10 px-4 py-3">
              <input type="checkbox" class="rounded border-gray-300">
            </th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">QR CODE</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IMAGE</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ARTICLE</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CATEGORY</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DESCRIPTION</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">PROPERTY ACCOUNT CODE</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">UNIT VALUE</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DATE ACQUIRED</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">P.C</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="item in paginatedItems" :key="item.qrCode" class="hover:bg-gray-50">
            <td class="px-4 py-2">
              <input type="checkbox" class="rounded border-gray-300">
            </td>
            <td class="px-4 py-2">
              <img :src="generateQRCode(item.qrCode)" alt="QR Code" class="h-8 w-8">
            </td>
            <td class="px-4 py-2">
              <img :src="item.image" alt="Item" class="h-8 w-8 object-contain">
            </td>
            <td class="px-4 py-2 text-sm">{{ item.article }}</td>
            <td class="px-4 py-2 text-sm">{{ item.category }}</td>
            <td class="px-4 py-2 text-sm">{{ item.description }}</td>
            <td class="px-4 py-2 text-sm">{{ item.propertyAccountCode }}</td>
            <td class="px-4 py-2 text-sm">{{ item.unitValue }}</td>
            <td class="px-4 py-2 text-sm">{{ item.dateAcquired }}</td>
            <td class="px-4 py-2 text-sm">{{ item.pc }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
      <div class="text-sm text-gray-600">
        Result 1-{{ itemsPerPage }} of {{ totalItems }}
      </div>
      <div class="flex items-center gap-1">
        <button 
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
        >
          &lt; Previous
        </button>
        <button 
          v-for="page in totalPages"
          :key="page"
          @click="goToPage(page)"
          :class="[
            'px-2 py-1 text-sm border rounded hover:bg-gray-50',
            currentPage === page ? 'bg-green-50 text-green-600 border-green-500' : ''
          ]"
        >
          {{ page }}
        </button>
        <button 
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
        >
          Next &gt;
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.btn-primary {
  @apply bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 flex items-center text-sm font-medium;
}

.btn-secondary {
  @apply bg-white text-gray-700 px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 flex items-center text-sm font-medium;
}

/* Additional styles for table header alignment and spacing */
th {
  @apply tracking-wider font-medium;
}

/* Ensure consistent checkbox styling */
input[type="checkbox"] {
  @apply rounded border-gray-300 text-green-600 focus:ring-green-500;
}
</style> 