<script setup>
import { ref, computed, onMounted  } from 'vue'
import { useRouter } from 'vue-router'
import useItems from '../composables/useItems'

const router = useRouter()
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(8)
const totalItems = ref(0)

const { useitems } = useItems()

console.log(useitems);

// Sample data
const inventoryItems = ref([
  {
    qrCode: '/images/qr-sample.png',
    image: '/images/desktop.png',
    article: 'Desktop',
    category: 'Inventory',
    description: 'Intel Core i5-650 2GB memory',
    propertyAccountCode: 'DDN-IMO-F501-223-C-02.05-10',
    unitValue: '32,200.00',
    dateAcquired: '12/28/10',
    poNumber: '2010-12-0579',
    location: 'Billing/I.Mandin',
    condition: 'A3 Servicable',
    actions: ['edit', 'delete']
  },
  // Generate 20 items with unique IDs for better demo
  ...Array(19).fill().map((_, index) => ({
    qrCode: '/images/qr-sample.png',
    image: '/images/desktop.png',
    article: 'Desktop',
    category: 'Inventory',
    description: `Intel Core i5-650 ${(index + 2)}GB memory`,
    propertyAccountCode: `DDN-IMO-F501-223-C-02.05-${index + 11}`,
    unitValue: '32,200.00',
    dateAcquired: '12/28/10',
    poNumber: '2010-12-0579',
    location: 'Billing/I.Mandin',
    condition: 'A3 Servicable',
    actions: ['edit', 'delete']
  }))
])






const filteredItems = computed(() => {
  if (!searchQuery.value) return inventoryItems.value
  
  return inventoryItems.value.filter(item => {
    return Object.values(item).some(value => 
      value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

// Update total items based on filtered results
const totalFilteredItems = computed(() => {
  return filteredItems.value.length
})

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredItems.value.slice(start, end)
})

const totalPages = computed(() => Math.ceil(totalFilteredItems.value / itemsPerPage.value))

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

// Add method to change items per page
const changeItemsPerPage = (newValue) => {
  itemsPerPage.value = Number(newValue)
  currentPage.value = 1 // Reset to first page when changing items per page
}

const goToAddItem = () => {
  router.push('/add-item')
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
        <button @click="goToAddItem" class="btn-primary">
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
    <div class="bg-white rounded-lg border border-gray-200">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 whitespace-nowrap">
          <thead>
            <tr class="bg-gray-50">
              <th class="sticky left-0 z-10 bg-gray-50 w-10 px-4 py-3">
                <input type="checkbox" class="rounded border-gray-300">
              </th>
              <th class="min-w-[80px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">QR CODE</th>
              <th class="min-w-[80px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IMAGE</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ARTICLE</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CATEGORY</th>
              <th class="min-w-[200px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DESCRIPTION</th>
              <th class="min-w-[200px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">PROPERTY ACCOUNT CODE</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">UNIT VALUE</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DATE ACQUIRED</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">P.O. NUMBER</th>
              <th class="min-w-[150px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">LOCATION</th>
              <th class="min-w-[120px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CONDITION</th>
              <th class="sticky right-0 z-10 bg-gray-50 min-w-[100px] px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ACTIONS</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="item in paginatedItems" :key="item.propertyAccountCode" class="hover:bg-gray-50">
              <td class="sticky left-0 z-10 bg-white hover:bg-gray-50 px-4 py-2">
                <input type="checkbox" class="rounded border-gray-300">
              </td>
              <td class="px-4 py-2">
                <img :src="item.qrCode" alt="QR Code" class="h-8 w-8">
              </td>
              <td class="px-4 py-2">
                <img :src="item.image" alt="Item" class="h-8 w-8 object-contain">
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.article">
                  {{ item.article }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.category">
                  {{ item.category }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[200px]" :title="item.description">
                  {{ item.description }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[200px]" :title="item.propertyAccountCode">
                  {{ item.propertyAccountCode }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.unitValue">
                  {{ item.unitValue }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.dateAcquired">
                  {{ item.dateAcquired }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.poNumber">
                  {{ item.poNumber }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[150px]" :title="item.location">
                  {{ item.location }}
                </div>
              </td>
              <td class="px-4 py-2">
                <div class="text-sm truncate max-w-[120px]" :title="item.condition">
                  {{ item.condition }}
                </div>
              </td>
              <td class="sticky right-0 z-10 bg-white hover:bg-gray-50 px-4 py-2">
                <div class="flex space-x-2">
                  <button class="p-1 rounded-md bg-green-100 text-green-600 hover:bg-green-200">
                    <span class="material-icons-outlined text-sm">edit</span>
                  </button>
                  <button class="p-1 rounded-md bg-green-100 text-green-600 hover:bg-green-200">
                    <span class="material-icons-outlined text-sm">delete</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200">
        <div class="flex items-center gap-4">
        <div class="text-sm text-gray-600">
            Result {{ (currentPage - 1) * itemsPerPage + 1 }}-{{ Math.min(currentPage * itemsPerPage, totalFilteredItems) }} of {{ totalFilteredItems }}
          </div>
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Items per page:</label>
            <select 
              v-model="itemsPerPage" 
              @change="changeItemsPerPage($event.target.value)"
              class="border rounded px-2 py-1 text-sm"
            >
              <option value="8">8</option>
              <option value="16">16</option>
              <option value="24">24</option>
              <option value="32">32</option>
            </select>
          </div>
        </div>
        <div class="flex items-center gap-1">
          <button 
            @click="goToPage(1)"
            :disabled="currentPage === 1"
            class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
          >
            First
          </button>
          <button 
            @click="goToPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
          >
            &lt; Previous
          </button>
          <div class="flex items-center gap-1">
            <template v-for="page in totalPages" :key="page">
              <!-- Show first page, last page, current page, and pages around current -->
          <button 
                v-if="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
            @click="goToPage(page)"
            :class="[
              'px-2 py-1 text-sm border rounded hover:bg-gray-50',
              currentPage === page ? 'bg-green-50 text-green-600 border-green-500' : ''
            ]"
          >
            {{ page }}
          </button>
              <!-- Show dots for skipped pages -->
              <span 
                v-else-if="page === currentPage - 2 || page === currentPage + 2"
                class="px-2"
              >...</span>
            </template>
          </div>
          <button 
            @click="goToPage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
          >
            Next &gt;
          </button>
          <button 
            @click="goToPage(totalPages)"
            :disabled="currentPage === totalPages"
            class="px-2 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50"
          >
            Last
          </button>
        </div>
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

/* Ensure consistent checkbox styling */
input[type="checkbox"] {
  @apply rounded border-gray-300 text-green-600 focus:ring-green-500;
}

/* Table specific styles */
.overflow-x-auto {
  @apply relative;
  scrollbar-width: thin;
  scrollbar-color: theme('colors.gray.300') theme('colors.gray.100');
}

.overflow-x-auto::-webkit-scrollbar {
  @apply h-2;
}

.overflow-x-auto::-webkit-scrollbar-track {
  @apply bg-gray-100 rounded-full;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  @apply bg-gray-300 rounded-full hover:bg-gray-400;
}

/* Ensure sticky columns work with hover states */
tr:hover td.sticky {
  @apply bg-gray-50;
}
</style>