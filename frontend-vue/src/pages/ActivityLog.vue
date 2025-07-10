<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const entries = ref([
  { 
    name: 'Jane Doe',
    date: '01-05-2025',
    time: '10:32 AM',
    action: 'Logged In',
    role: 'Admin'
  },
  {
    name: 'John Smith',
    date: '2025-05-01',
    time: '10:35 AM',
    action: 'Logged In',
    role: 'User'
  }
])

const entriesPerPage = ref(10)
const searchQuery = ref('')
const currentPage = ref(1)
const totalEntries = ref(1)
const isProfileDropdownOpen = ref(false)

const user = ref({
  name: 'JGludo',
  avatar: '../assets/avatar.jpg'
})

const toggleProfileDropdown = () => {
  isProfileDropdownOpen.value = !isProfileDropdownOpen.value
}

const closeProfileDropdown = (e) => {
  if (isProfileDropdownOpen.value) {
    const dropdown = document.querySelector('.profile-dropdown')
    const button = document.querySelector('.profile-button')
    if (dropdown && !dropdown.contains(e.target) && button && !button.contains(e.target)) {
      isProfileDropdownOpen.value = false
    }
  }
}

onMounted(() => {
  document.addEventListener('click', closeProfileDropdown)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeProfileDropdown)
})

const filteredEntries = computed(() => {
  return entries.value.filter(entry => {
    const searchLower = searchQuery.value.toLowerCase()
    return (
      entry.name.toLowerCase().includes(searchLower) ||
      entry.action.toLowerCase().includes(searchLower) ||
      entry.role.toLowerCase().includes(searchLower)
    )
  })
})
</script>

<template>
  <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
    <!-- Header with Profile -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Activity Log</h1>
      
      <!-- Profile Dropdown -->
      <div class="relative">
        <button 
          class="flex items-center space-x-2 profile-button"
          @click="toggleProfileDropdown"
        >
          <img
            :src="user.avatar"
            alt="Profile"
            class="h-7 w-7 sm:h-8 sm:w-8 rounded-full object-cover"
          />
        </button>

        <!-- Profile Dropdown Menu -->
        <div 
          v-if="isProfileDropdownOpen"
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50 profile-dropdown"
        >
          <router-link 
            to="/profile"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50"
            @click="isProfileDropdownOpen = false"
          >
            Profile
          </router-link>
          <router-link
            to="/history"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50"
            @click="isProfileDropdownOpen = false"
          >
            History
          </router-link>
          <button
            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50"
            @click="() => { isProfileDropdownOpen = false; /* Add logout logic here */ }"
          >
            Log out
          </button>
        </div>
      </div>
    </div>
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
      <div class="flex items-center">
        <span class="text-gray-600 dark:text-gray-400 mr-2">Show</span>
        <select 
          v-model="entriesPerPage"
          class="border rounded px-2 py-1 text-gray-600 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600"
        >
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="text-gray-600 dark:text-gray-400 ml-2">entries</span>
      </div>

      <div class="w-full sm:w-auto">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search..."
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
        />
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="entry in filteredEntries" :key="entry.name + entry.date + entry.time">
            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ entry.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ entry.date }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ entry.time }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ entry.action }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ entry.role }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex justify-between items-center mt-4">
      <div class="text-sm text-gray-600 dark:text-gray-400">
        Showing {{ Math.min((currentPage - 1) * entriesPerPage + 1, totalEntries) }} to {{ Math.min(currentPage * entriesPerPage, totalEntries) }} of {{ totalEntries }} entries
      </div>
      
      <div class="flex space-x-1">
        <button
          class="px-3 py-1 rounded border text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          <span class="material-icons-outlined text-sm">chevron_left</span>
        </button>
        
        <button
          class="px-3 py-1 rounded border"
          :class="currentPage === 1 ? 'bg-green-500 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
        >
          1
        </button>
        
        <button
          class="px-3 py-1 rounded border"
          :class="currentPage === 2 ? 'bg-green-500 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
        >
          2
        </button>
        
        <button
          class="px-3 py-1 rounded border text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
          :disabled="currentPage * entriesPerPage >= totalEntries"
          @click="currentPage++"
        >
          <span class="material-icons-outlined text-sm">chevron_right</span>
        </button>
      </div>
    </div>
  </div>
</template> 