<script setup>
import { ref, computed, onMounted } from 'vue'
import axiosClient from '../axios'
import { getDeletedItems as getStoredDeletedItems, removeDeletedItem } from '../utils/deletedItemsStorage'

const items = ref([])
const loading = ref(false)
const error = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const showRestoreModal = ref(false)
const selectedItem = ref(null)
const restoreLoading = ref(false)

// Fetch deleted items from localStorage
const fetchDeletedItems = async () => {
  loading.value = true
  error.value = null
  
  try {
    // Get deleted items from localStorage
    const storedItems = getStoredDeletedItems()
    items.value = storedItems
    console.log('Deleted items from localStorage:', items.value)
  } catch (err) {
    console.error('Error fetching deleted items:', err)
    error.value = 'Failed to load deleted items. Please try again.'
  } finally {
    loading.value = false
  }
}

// Fetch deleted items when component mounts
onMounted(() => {
  fetchDeletedItems()
})

const filteredItems = computed(() => {
  if (!searchQuery.value) return formattedItems.value
  
  const searchLower = searchQuery.value.toLowerCase()
  return formattedItems.value.filter(item => {
    return Object.values(item).some(value => 
      String(value).toLowerCase().includes(searchLower)
    )
  })
})

const totalItems = computed(() => filteredItems.value.length)
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1)
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage.value - 1, totalItems.value))

// Map API data to the format expected by the table
const formattedItems = computed(() => {
  return items.value.map(item => ({
    uuid: item.uuid,
    id: item.id,
    article: item.article || '',
    description: item.description || '',
    propertyAccountCode: item.propertyAccountCode || '',
    unitValue: item.unitValue || '',
    dateAcquired: item.dateAcquired || '',
    poNumber: item.poNumber || '',
    location: item.location || '',
    deletedAt: new Date(item.deleted_at).toLocaleString(),
    deletionReason: item.deletion_reason || 'No reason provided'
  }))
})

const openRestoreModal = (item) => {
  selectedItem.value = item
  showRestoreModal.value = true
}

const closeRestoreModal = () => {
  showRestoreModal.value = false
  selectedItem.value = null
}

const handleRestore = async () => {
  if (!selectedItem.value) return
  
  restoreLoading.value = true
  
  try {
    // Remove the item from localStorage
    removeDeletedItem(selectedItem.value.uuid)
    
    // Show success message
    alert('Item has been removed from the deleted items list.')
    
    // Refresh the deleted items list
    await fetchDeletedItems()
  } catch (error) {
    console.error('Error restoring item:', error)
    
    // Show error message
    alert('Failed to restore item. Please try again.')
  } finally {
    restoreLoading.value = false
    closeRestoreModal()
  }
}
</script>

<template>
  <div class="p-6 bg-white rounded-lg">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Deleted Items History</h1>

    <div class="bg-white rounded-lg shadow-sm">
      <div class="p-4">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
          <div class="flex items-center">
            <span class="text-gray-600 mr-2">Show</span>
            <select 
              v-model="itemsPerPage"
              class="border rounded px-3 py-1 text-gray-600 bg-white focus:outline-none focus:border-green-500"
            >
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span class="text-gray-600 ml-2">entries</span>
          </div>

          <div class="w-full sm:w-auto">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search..."
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
            />
          </div>
        </div>

        <div class="overflow-x-auto">
          <!-- Loading indicator -->
          <div v-if="loading" class="flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-600"></div>
          </div>
          
          <!-- Error state -->
          <div v-else-if="error" class="flex flex-col justify-center items-center py-10">
            <span class="material-icons-outlined text-4xl text-red-400">error_outline</span>
            <p class="mt-2 text-red-500">{{ error }}</p>
            <button 
              @click="fetchDeletedItems" 
              class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              Try Again
            </button>
          </div>
          
          <!-- Empty state -->
          <div v-else-if="filteredItems.length === 0" class="flex flex-col justify-center items-center py-10">
            <span class="material-icons-outlined text-4xl text-gray-400">delete_outline</span>
            <p class="mt-2 text-gray-500">No deleted items found</p>
            <p class="text-sm text-gray-400 mt-1">Items deleted from the inventory will appear here.</p>
            <p v-if="searchQuery" class="text-sm text-gray-400 mt-1">Try adjusting your search query</p>
          </div>
          
          <!-- Table with data -->
          <table v-else class="min-w-full bg-white">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ARTICLE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DESCRIPTION</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PROPERTY ACCOUNT CODE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UNIT VALUE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE ACQUIRED</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P.O. NUMBER</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LOCATION</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DELETED AT</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">REASON</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTION</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="item in filteredItems" :key="item.uuid" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.article }}</td>
                <td class="px-6 py-4 whitespace-pre-line text-sm text-gray-600">{{ item.description }}</td>
                <td class="px-6 py-4 whitespace-pre-line text-sm text-gray-600">{{ item.propertyAccountCode }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.unitValue }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.dateAcquired }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.poNumber }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.location }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.deletedAt }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.deletionReason }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button 
                    @click="openRestoreModal(item)"
                    class="p-2 bg-green-600 rounded-full hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    :disabled="loading"
                    title="Remove from deleted items list"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
          <div>
            Showing {{ startIndex }} to {{ endIndex }} of {{ totalItems }} entries
          </div>
          <div class="flex gap-1">
            <button 
              @click="currentPage--" 
              :disabled="currentPage === 1"
              class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors"
              :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-600'"
            >
              &lt;
            </button>
            <button 
              v-for="page in totalPages" 
              :key="page"
              @click="currentPage = page"
              class="px-3 py-1 border rounded transition-colors"
              :class="currentPage === page ? 'bg-green-600 text-white border-green-600' : 'text-gray-600 hover:bg-gray-50'"
            >
              {{ page }}
            </button>
            <button 
              @click="currentPage++" 
              :disabled="currentPage === totalPages"
              class="px-3 py-1 border rounded hover:bg-gray-50 transition-colors"
              :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-gray-600'"
            >
              &gt;
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div v-if="showRestoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="flex justify-end">
          <button @click="closeRestoreModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="text-center mb-6">
          <div class="mb-4">
            <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
          </div>
          <h3 class="text-lg text-gray-700 mb-4">Are you sure you want to remove this item from the deleted items list?</h3>
          <p class="text-sm text-gray-500 mb-4">Note: This will only remove the item from this list. The actual item cannot be restored to the inventory in this version.</p>
          <div class="flex justify-center gap-4">
            <button
              @click="handleRestore"
              class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center gap-2"
              :disabled="restoreLoading"
            >
              <span v-if="restoreLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
              {{ restoreLoading ? 'Removing...' : 'Yes, remove it' }}
            </button>
            <button
              @click="closeRestoreModal"
              class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
              :disabled="restoreLoading"
            >
              No, cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
</style> 