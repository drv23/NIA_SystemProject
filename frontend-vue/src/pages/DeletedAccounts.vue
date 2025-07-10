<script setup>
import { ref, computed } from 'vue'
import { ArrowPathIcon } from '@heroicons/vue/24/outline'
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline'

const deletedAccounts = ref([
  {
    name: 'Juan Dela Cruz',
    username: 'juancruz',
    email: 'juan@gmail.com',
    deletedBy: 'caballeros123@gmail.com',
    deletionDate: 'May 1, 2025, 2:32 PM'
  }
])

const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const showRestoreModal = ref(false)
const accountToRestore = ref(null)

const filteredAccounts = computed(() => {
  return deletedAccounts.value.filter(account => {
    const searchLower = searchQuery.value.toLowerCase()
    return (
      account.name.toLowerCase().includes(searchLower) ||
      account.username.toLowerCase().includes(searchLower) ||
      account.email.toLowerCase().includes(searchLower)
    )
  })
})

const openRestoreModal = (account) => {
  accountToRestore.value = account
  showRestoreModal.value = true
}

const handleRestore = () => {
  // TODO: Implement actual restore logic here
  console.log('Restoring account:', accountToRestore.value)
  showRestoreModal.value = false
  accountToRestore.value = null
}

const closeModal = () => {
  showRestoreModal.value = false
  accountToRestore.value = null
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">Deleted Account History</h1>
    
    <div class="bg-white rounded-lg shadow">
      <div class="p-4 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-2">
          <span class="text-sm">Show</span>
          <select 
            v-model="itemsPerPage"
            class="border rounded px-2 py-1 text-sm"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="text-sm">entries</span>
        </div>

        <div class="w-full sm:w-auto">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-full px-3 py-2 border rounded-lg text-sm"
          />
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="bg-gray-50 border-y">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted By</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deletion Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="account in filteredAccounts" :key="account.email">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ account.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ account.username }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ account.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ account.deletedBy }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ account.deletionDate }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button 
                  @click="openRestoreModal(account)"
                  class="p-2 bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200 flex items-center justify-center"
                >
                  <ArrowPathIcon class="h-5 w-5 text-white" />
                  <span class="sr-only">Restore</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4 flex items-center justify-between border-t">
        <div class="text-sm text-gray-500">
          Showing 1 to 1 of 1 entries
        </div>
        <div class="flex items-center space-x-2">
          <button class="px-2 py-1 rounded border" disabled>&lt;</button>
          <button class="px-3 py-1 rounded bg-green-600 text-white">1</button>
          <button class="px-3 py-1 rounded border">2</button>
          <button class="px-2 py-1 rounded border">&gt;</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <Teleport to="body">
    <div v-if="showRestoreModal" class="fixed inset-0 z-50 overflow-y-auto">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10">
              <ExclamationCircleIcon class="h-6 w-6 text-gray-600" />
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-lg font-semibold leading-6 text-gray-900">
                Are you sure you want to restore this account?
              </h3>
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
            <button
              type="button"
              class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 sm:w-auto"
              @click="handleRestore"
            >
              Yes, I'm sure
            </button>
            <button
              type="button"
              class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
              @click="closeModal"
            >
              No, cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template> 