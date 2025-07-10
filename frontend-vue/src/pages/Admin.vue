<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axiosClient from '../axios'
// import DefaultLayout from '../layouts/DefaultLayout.vue'

// Stats data
const stats = ref({
  admins: 0,
  users: 0
})

// console.log(adminAccounts.value.fullname)

// Account data
const adminAccounts = ref([])
const userAccounts = ref([])

const adminSearchQuery = ref('')
const userSearchQuery = ref('')
const currentAdminPage = ref(1)
const currentUserPage = ref(1)
const itemsPerPage = ref(5)
let number = ref(1)

const loading = ref(false)

// Fetch all users from the API
const fetchUsers = async () => {
  loading.value = true
  try {
    console.log('Fetching users...')
    const response = await axiosClient.get('/users')
    console.log('Complete API Response:', response)
    console.log('Response status:', response.status)
    console.log('Response headers:', response.headers)
    
    // Check if response has data
    if (response.data) {
      console.log('Raw response.data:', JSON.stringify(response.data, null, 2))
      
      // Handle both array and object with data property
      const users = Array.isArray(response.data) ? response.data : (response.data.data || [])
      console.log('Processed users array:', JSON.stringify(users, null, 2))

      // Log each user's role for debugging
      let no = 0
      users.forEach(user => {
        console.log(`User: ${user.image || 'image'} - Role: ${user.role || 'no role'}, Name: ${user.fullname || 'no name'}, No: ${number+=1}`)
      })


      // Filter users based on role, handle case-insensitive comparison
      adminAccounts.value = users.filter(user => {
        const hasRole = user.role?.toLowerCase() === 'admin'
        console.log(`Checking user ${user.id} for admin role:`, hasRole)
        return hasRole
      })
      userAccounts.value = users.filter(user => {
        const hasRole = user.role?.toLowerCase() === 'user'
        console.log(`Checking user ${user.id} for user role:`, hasRole)
        return hasRole
      })
      
      console.log('Filtered admin accounts:', adminAccounts.value)
      console.log('Filtered user accounts:', userAccounts.value)
      
      // Update stats
      stats.value.admins = adminAccounts.value.length
      stats.value.users = userAccounts.value.length
      
      console.log('Updated stats:', stats.value)
    } else {
      console.error('No data in API response')
      adminAccounts.value = []
      userAccounts.value = []
      stats.value.admins = 0
      stats.value.users = 0
    }
  } catch (error) {
    console.error('Error fetching users:', error)
    if (error.response) {
      console.error('Error response data:', error.response.data)
      console.error('Error response status:', error.response.status)
      console.error('Error response headers:', error.response.headers)
    }
    adminAccounts.value = []
    userAccounts.value = []
    stats.value.admins = 0
    stats.value.users = 0
  } finally {
    loading.value = false
  }
}

// State for reassign modal
const showReassignModal = ref(false)
const userToDelete = ref(null)
const newUserId = ref('')
const itemCount = ref(0)
const reassignLoading = ref(false)
const availableUsers = ref([])

// Fetch users for reassignment dropdown
const fetchAvailableUsers = async (excludeUserId) => {
  try {
    const response = await axiosClient.get('/users')
    if (response.data && Array.isArray(response.data.data)) {
      // Filter out the user being deleted
      availableUsers.value = response.data.data.filter(user => user.id != excludeUserId)
    } else if (response.data && Array.isArray(response.data)) {
      availableUsers.value = response.data.filter(user => user.id != excludeUserId)
    } else {
      availableUsers.value = []
    }
  } catch (error) {
    console.error('Error fetching available users:', error)
    availableUsers.value = []
  }
}

// Delete user function
const deleteUser = async (userId) => {
  if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
    try {
      // Show loading state
      loading.value = true
      
      // Send delete request to API
      const response = await axiosClient.delete(`/users/${userId}`)
      
      // Show success message
      alert(response.data?.message || 'User deleted successfully')
      
      // Refresh the users list
      await fetchUsers()
    } catch (error) {
      console.error('Error deleting user:', error)
      
      // Check if the error is due to associated items
      if (error.response?.data?.status === 'error' && 
          error.response?.data?.itemCount > 0) {
        
        // Set up the reassign modal
        userToDelete.value = error.response.data.userId
        itemCount.value = error.response.data.itemCount
        
        // Fetch available users for reassignment
        await fetchAvailableUsers(userToDelete.value)
        
        // Show the modal
        showReassignModal.value = true
      } else {
        // Show error message to user
        if (error.response?.data?.message) {
          alert(error.response.data.message)
        } else {
          alert('Failed to delete user. Please try again.')
        }
      }
    } finally {
      loading.value = false
    }
  }
}

// Reassign items and then delete user
const reassignAndDelete = async () => {
  if (!newUserId.value) {
    alert('Please select a user to reassign items to')
    return
  }
  
  try {
    reassignLoading.value = true
    
    // First reassign the items
    const reassignResponse = await axiosClient.post(`/users/${userToDelete.value}/reassign-items`, {
      new_user_id: newUserId.value
    })
    
    // Then delete the user
    const deleteResponse = await axiosClient.delete(`/users/${userToDelete.value}`)
    
    // Show success message
    alert('Items reassigned and user deleted successfully')
    
    // Close the modal and reset
    closeReassignModal()
    
    // Refresh the users list
    await fetchUsers()
  } catch (error) {
    console.error('Error during reassign and delete:', error)
    alert(error.response?.data?.message || 'Failed to reassign items and delete user')
  } finally {
    reassignLoading.value = false
  }
}

// Close the reassign modal
const closeReassignModal = () => {
  showReassignModal.value = false
  userToDelete.value = null
  newUserId.value = ''
  itemCount.value = 0
}

// Edit user function is now handled by router-link

const filteredAdminAccounts = computed(() => {
  console.log('Filtering admin accounts:', adminAccounts.value)
  return adminAccounts.value.filter(account => {
    const searchString = adminSearchQuery.value.toLowerCase()
    return (
      account.fullname?.toLowerCase().includes(searchString) ||
      account.email?.toLowerCase().includes(searchString) ||
      account.location?.toLowerCase().includes(searchString) ||
      account.image?.toLowerCase().includes(searchString)
    )
  })
})

const filteredUserAccounts = computed(() => {
  console.log('Filtering user accounts:', userAccounts.value)
  return userAccounts.value.filter(account => {
    const searchString = userSearchQuery.value.toLowerCase()
    return (
      account.fullname?.toLowerCase().includes(searchString) ||
      account.email?.toLowerCase().includes(searchString) ||
      account.location?.toLowerCase().includes(searchString) ||
      account.image?.toLowerCase().includes(searchString)
    )
  })
})

// Admin pagination
const totalAdminPages = computed(() => {
  return Math.ceil(filteredAdminAccounts.value.length / itemsPerPage.value)
})

const paginatedAdminAccounts = computed(() => {
  const start = (currentAdminPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredAdminAccounts.value.slice(start, end)
})

const adminStartIndex = computed(() => {
  return (currentAdminPage.value - 1) * itemsPerPage.value + 1
})

const adminEndIndex = computed(() => {
  return Math.min(currentAdminPage.value * itemsPerPage.value, filteredAdminAccounts.value.length)
})

// User pagination
const totalUserPages = computed(() => {
  return Math.ceil(filteredUserAccounts.value.length / itemsPerPage.value)
})

const paginatedUserAccounts = computed(() => {
  const start = (currentUserPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredUserAccounts.value.slice(start, end)
})

const userStartIndex = computed(() => {
  return (currentUserPage.value - 1) * itemsPerPage.value + 1
})

const userEndIndex = computed(() => {
  return Math.min(currentUserPage.value * itemsPerPage.value, filteredUserAccounts.value.length)
})

const router = useRouter()

const addNewAdmin = () => {
  router.push({ 
    path: '/add-account',
    query: { type: 'admin' }
  })
}

const addAdmin = () => {
  router.push({ 
    path: '/add-account',
    query: { type: 'admin' }
  })
}

const addUser = () => {
  router.push({ 
    path: '/add-account',
    query: { type: 'user' }
  })
}

// Pagination methods
const goToAdminPage = (page) => {
  if (page >= 1 && page <= totalAdminPages.value) {
    currentAdminPage.value = page
  }
}

const goToUserPage = (page) => {
  if (page >= 1 && page <= totalUserPages.value) {
    currentUserPage.value = page
  }
}

// Watch for search query changes to reset pagination
watch(adminSearchQuery, () => {
  currentAdminPage.value = 1
})

watch(userSearchQuery, () => {
  currentUserPage.value = 1
})

// Fetch users when component is mounted
onMounted(() => {
  fetchUsers()
  console.log(paginatedAdminAccounts.value)
})
</script>

<template>
  
  <DefaultLayout>
    <div class="p-4 sm:p-6">
      <h1 class="text-xl sm:text-2xl font-semibold mb-4 sm:mb-6">Admins</h1>

      <!-- Stats Cards -->
      <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
          <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 w-full sm:w-auto">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-white">admin_panel_settings</span>
              </div>
              <div>
                <p class="text-gray-600">Admins</p>
                <p class="text-xl font-semibold">{{ stats.admins }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined text-white">group</span>
              </div>
              <div>
                <p class="text-gray-600">Users</p>
                <p class="text-xl font-semibold">{{ stats.users }}</p>
              </div>
            </div>
          </div>
          <button @click="addNewAdmin" class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center sm:justify-start">
            <span class="material-icons-outlined">add</span>
            Add new
          </button>
        </div>
      </div>

      <!-- Admin Accounts Section -->
      <div class="mb-8">
        <h2 class="text-lg font-medium mb-4">Admin Accounts</h2>
        
        <!-- Search Bar -->
        <div class="flex justify-end mb-4">
          <div class="relative w-full sm:w-64">
            <input
              v-model="adminSearchQuery"
              type="text"
              placeholder="Search..."
              class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg"
            >
            <span class="material-icons-outlined absolute right-3 top-2.5 text-gray-400">search</span>
          </div>
        </div>

        <!-- Admin Table -->
        <div class="bg-white rounded-lg border border-gray-200">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Full Name</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">No.</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Location</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="loading">
                  <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                      <div class="flex items-center justify-center">
                        <span class="material-icons-outlined animate-spin mr-2">refresh</span>
                        Loading...
                      </div>
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="(account, index) in paginatedAdminAccounts" :key="index" class="border-b">
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <img 
                          :src="account.image"
                          class="h-8 w-8 rounded-full bg-gray-200 object-cover"
                          :alt="account.fullname"
                        >
                        <div class="ml-3">
                          <div class="font-medium">{{ account.fullname }}</div>
                          <div class="text-gray-500 text-sm truncate max-w-[200px]">{{ account.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4">{{ index+1 }}</td>
                    <td class="px-6 py-4">{{ new Date(account.created_at).toLocaleDateString() }}</td>
                    <td class="px-6 py-4">{{ account.location || 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <div class="flex gap-2">
                        <router-link :to="`/edit-account/${account.id}`" class="text-green-600 hover:text-green-700 inline-flex items-center justify-center p-1">
                          <span class="material-icons-outlined">edit</span>
                        </router-link>
                        <button 
                          @click="deleteUser(account.id)" 
                          class="text-red-600 hover:text-red-700 p-1"
                          :disabled="loading"
                        >
                          <span class="material-icons-outlined" v-if="!loading">delete</span>
                          <span class="material-icons-outlined animate-spin" v-else>refresh</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="paginatedAdminAccounts.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                      No admin accounts found
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="px-4 sm:px-6 py-3 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
              <div class="text-sm text-gray-500 text-center sm:text-left">
                Showing {{ adminStartIndex }} to {{ adminEndIndex }} of {{ filteredAdminAccounts.length }} entries
              </div>
              <div class="flex items-center gap-2">
                <button 
                  class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:hover:bg-white" 
                  :disabled="currentAdminPage === 1"
                  @click="goToAdminPage(currentAdminPage - 1)"
                >
                  Previous
                </button>
                <div class="flex items-center gap-1">
                  <button 
                    v-for="page in totalAdminPages" 
                    :key="page"
                    @click="goToAdminPage(page)"
                    class="px-3 py-1 text-sm border rounded"
                    :class="currentAdminPage === page ? 'bg-green-600 text-white' : 'hover:bg-gray-50'"
                  >
                    {{ page }}
                  </button>
                </div>
                <button 
                  class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:hover:bg-white"
                  :disabled="currentAdminPage === totalAdminPages"
                  @click="goToAdminPage(currentAdminPage + 1)"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- User Accounts Section -->
      <div>
        <h2 class="text-lg font-medium mb-4">User Accounts</h2>
        
        <!-- Search Bar -->
        <div class="flex justify-end mb-4">
          <div class="relative w-full sm:w-64">
            <input
              v-model="userSearchQuery"
              type="text"
              placeholder="Search..."
              class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg"
            >
            <span class="material-icons-outlined absolute right-3 top-2.5 text-gray-400">search</span>
          </div>
        </div>

        <!-- User Table -->
        <div class="bg-white rounded-lg border border-gray-200">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Full Name</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">No.</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Location</th>
                  <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="loading">
                  <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                      <div class="flex items-center justify-center">
                        <span class="material-icons-outlined animate-spin mr-2">refresh</span>
                        Loading...
                      </div>
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="(account, index) in paginatedUserAccounts" :key="index" class="border-b">
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <img 
                          :src="account.image || '/path/to/default-avatar.png'" 
                          class="h-8 w-8 rounded-full bg-gray-200 object-cover"
                          :alt="account.fullname"
                        >
                        <div class="ml-3">
                          <div class="font-medium">{{ account.fullname }}</div>
                          <div class="text-gray-500 text-sm truncate max-w-[200px]">{{ account.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4">{{ index+1 }}</td>
                    <td class="px-6 py-4">{{ new Date(account.created_at).toLocaleDateString() }}</td>
                    <td class="px-6 py-4">{{ account.location || 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <div class="flex gap-2">
                        <router-link :to="`/edit-account/${account.id}`" class="text-green-600 hover:text-green-700 inline-flex items-center justify-center p-1">
                          <span class="material-icons-outlined">edit</span>
                        </router-link>
                        <button 
                          @click="deleteUser(account.id)" 
                          class="text-red-600 hover:text-red-700 p-1"
                          :disabled="loading"
                        >
                          <span class="material-icons-outlined" v-if="!loading">delete</span>
                          <span class="material-icons-outlined animate-spin" v-else>refresh</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="paginatedUserAccounts.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                      No user accounts found
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="px-4 sm:px-6 py-3 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
              <div class="text-sm text-gray-500 text-center sm:text-left">
                Showing {{ userStartIndex }} to {{ userEndIndex }} of {{ filteredUserAccounts.length }} entries
              </div>
              <div class="flex items-center gap-2">
                <button 
                  class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:hover:bg-white" 
                  :disabled="currentUserPage === 1"
                  @click="goToUserPage(currentUserPage - 1)"
                >
                  Previous
                </button>
                <div class="flex items-center gap-1">
                  <button 
                    v-for="page in totalUserPages" 
                    :key="page"
                    @click="goToUserPage(page)"
                    class="px-3 py-1 text-sm border rounded"
                    :class="currentUserPage === page ? 'bg-green-600 text-white' : 'hover:bg-gray-50'"
                  >
                    {{ page }}
                  </button>
                </div>
                <button 
                  class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:hover:bg-white"
                  :disabled="currentUserPage === totalUserPages"
                  @click="goToUserPage(currentUserPage + 1)"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Reassign Items Modal -->
    <div v-if="showReassignModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-medium mb-4">Reassign Items</h3>
        
        <p class="mb-4">
          This user has {{ itemCount }} associated items. Please select another user to reassign these items to before deleting.
        </p>
        
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
          <select 
            v-model="newUserId"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            required
          >
            <option value="" disabled>Select a user</option>
            <option 
              v-for="user in availableUsers" 
              :key="user.id" 
              :value="user.id"
            >
              {{ user.fullname }} ({{ user.role }})
            </option>
          </select>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
          <button 
            @click="closeReassignModal"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
            :disabled="reassignLoading"
          >
            Cancel
          </button>
          <button 
            @click="reassignAndDelete"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-75 disabled:cursor-not-allowed flex items-center gap-2"
            :disabled="!newUserId || reassignLoading"
          >
            <span v-if="reassignLoading" class="material-icons-outlined animate-spin text-sm">refresh</span>
            {{ reassignLoading ? 'Processing...' : 'Reassign & Delete' }}
          </button>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
.material-icons-outlined {
  font-size: 20px;
}
</style>
