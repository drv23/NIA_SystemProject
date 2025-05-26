<template>
  
    <div class="min-h-screen bg-gray-50 p-6">
      <!-- Header -->
      <div class="flex items-center gap-3 mb-8">
        <button @click="goBack" class="inline-flex items-center text-gray-600 hover:text-gray-800">
          <span class="material-icons-outlined">arrow_back</span>
          <span class="ml-1">Back</span>
        </button>
      </div>

      <!-- Main Form -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
        <h2 class="text-lg font-medium text-gray-800 mb-6">Add new account</h2>
        
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Account Type -->
          <div class="form-group">
            <label class="form-label">Account Type</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">badge</span>
              </span>
              <select 
                v-model="formData.accountType"
                class="form-select"
                required
              >
                <option value="" disabled>Select account type</option>
                <option 
                  v-for="type in accountTypes" 
                  :key="type.value" 
                  :value="type.value"
                >
                  {{ type.label }}
                </option>
              </select>
              <p v-if="errors.account_type" class="mt-1 text-sm text-red-600">{{ errors.account_type[0] }}</p>
            </div>
          </div>

          <!-- Avatar Upload -->
          <div class="form-group">
            <label class="form-label">Avatar</label>
            <div class="mt-1">
              <div
                @click="$refs.fileInput.click()"
                @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false"
                @drop.prevent="handleFileDrop"
                class="flex flex-col items-center justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 rounded-md appearance-none cursor-pointer"
                :class="{ 
                  'border-dashed hover:border-gray-400': !selectedFile,
                  'border-solid border-green-500': selectedFile,
                  'border-blue-400 border-solid': dragOver
                }"
              >
                <div v-if="selectedFile" class="flex items-center space-x-2">
                  <img
                    v-if="previewUrl"
                    :src="previewUrl"
                    class="w-16 h-16 object-cover rounded"
                    alt="Preview"
                  />
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900">{{ selectedFile.name }}</span>
                    <span class="text-xs text-gray-500">{{ formatFileSize(selectedFile.size) }}</span>
                  </div>
                  <button
                    @click.stop="clearFile"
                    class="p-1 text-gray-500 hover:text-gray-700"
                    title="Remove file"
                  >
                    <span class="material-icons-outlined">close</span>
                  </button>
                </div>
                <div v-else class="flex items-center space-x-2">
                  <span class="material-icons-outlined text-gray-400">cloud_upload</span>
                  <span class="font-medium text-gray-600">
                    <span class="text-green-600 hover:underline">Choose file</span> or drag and drop
                  </span>
                </div>
                <input
                  ref="fileInput"
                  type="file"
                  @change="handleImageUpload"
                  accept="image/*"
                  class="hidden"
                >
              </div>
              <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
              <p v-if="errors.avatar" class="mt-1 text-sm text-red-600">{{ errors.avatar[0] }}</p>
            </div>
          </div>

          <!-- Full Name -->
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">person</span>
              </span>
              <input 
                type="text" 
                v-model="formData.fullname"
                class="form-input"
                placeholder="Enter full name"
                required
              >
              <p v-if="errors.fullname" class="mt-1 text-sm text-red-600">{{ errors.fullname[0] }}</p>
            </div>
          </div>

          <!-- Username -->
          <div class="form-group">
            <label class="form-label">Username</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">alternate_email</span>
              </span>
              <input 
                type="text" 
                v-model="formData.username"
                class="form-input"
                placeholder="Enter username"
                required
              >
              <p v-if="errors.username" class="mt-1 text-sm text-red-600">{{ errors.username[0] }}</p>
            </div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label class="form-label">Email</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">email</span>
              </span>
              <input 
                type="email" 
                v-model="formData.email"
                class="form-input"
                placeholder="Enter email address"
                required
              >
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>
          </div>

          <!-- Location -->
          <div class="form-group">
            <label class="form-label">Location</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">location_on</span>
              </span>
              <select 
                v-model="formData.location"
                class="form-select"
                required
                @change="(e) => console.log('Location changed:', e.target.value)"
              >
                <option value="" disabled>Select location</option>
                <option 
                  v-for="location in locations" 
                  :key="location.id" 
                  :value="location.id || location.location_id"
                >
                  {{ location.location }}
                </option>
              </select>
              
              
            </div>
            <p v-if="errors.location" class="mt-1 text-sm text-red-600">{{ errors.location[0] }}</p>
            <p v-if="formData.location" class="mt-1 text-xs text-gray-500">Selected location ID: {{ formData.location }}</p>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">lock</span>
              </span>
              <input 
                type="password" 
                v-model="formData.password"
                class="form-input"
                placeholder="Enter password"
                required
              >
              
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
          </div>

          <!-- Confirm Password -->
          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="relative">
              <span class="absolute left-0 top-0 text-gray-400">
                <span class="material-icons-outlined">lock</span>
              </span>
              <input 
                type="password" 
                v-model="formData.confirmPassword"
                class="form-input"
                placeholder="Confirm password"
                required
                @input="errors.password = []"
              >
              
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
          </div>

          <!-- General Error Message -->
          <div v-if="errors.general" class="mt-4">
            <p class="text-sm text-red-600">{{ errors.general[0] }}</p>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end mt-6">
            <button 
              type="submit"
              :disabled="isSubmitting"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <span v-if="isSubmitting" class="material-icons-outlined animate-spin text-sm">refresh</span>
              {{ isSubmitting ? 'Creating...' : 'Create Account' }}
            </button>
          </div>
        </form>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import axiosClient from '../axios'
import useLocations from '../composables/useLocations'

const router = useRouter()
const errors = ref({})
const dragOver = ref(false)
const selectedFile = ref(null)
const previewUrl = ref(null)
const fileInput = ref(null)
const isSubmitting = ref(false)

const formData = ref({
  accountType: '',
  avatar: null,
  fullname: '',
  username: '',
  email: '',
  location: '',
  password: '',
  confirmPassword: ''
})

// Sample data for dropdowns
const accountTypes = ref([
  { value: 'admin', label: 'Admin' },
  { value: 'user', label: 'User' }
])

const { locations } = useLocations(formData)

// const locations = ref([])

// // Fetch locations from the backend
// const fetchLocations = async () => {
//   try {
//     const response = await axiosClient.get('/locations')
//     if (response.data && response.data.data) {
//       locations.value = response.data.data
//       console.log('Available locations:', locations.value)
//     }
//   } catch (error) {
//     console.error('Error fetching locations:', error)
//   }
// }

// // Watch for location changes
// watch(() => formData.value.location, (newValue) => {
//   console.log('Location selected:', newValue)
// })

// // Call fetchLocations when component is mounted
// onMounted(() => {
//   fetchLocations()
// })

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const handleFileDrop = (event) => {
  dragOver.value = false
  const file = event.dataTransfer.files[0]
  if (file && file.type.startsWith('image/')) {
    handleFileSelection(file)
  }
}

const handleFileSelection = (file) => {
  if (file.size > 10 * 1024 * 1024) { // 10MB limit
    errors.value.avatar = ['File size should not exceed 10MB']
    return
  }
  
  selectedFile.value = file
  formData.value.avatar = file
  
  // Create preview URL
  const reader = new FileReader()
  reader.onload = (e) => {
    previewUrl.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    handleFileSelection(file)
  }
}

const clearFile = () => {
  selectedFile.value = null
  previewUrl.value = null
  formData.value.avatar = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const goBack = () => {
  router.push('/admin')
}

const validatePasswords = () => {
  if (formData.value.password !== formData.value.confirmPassword) {
    errors.value.password = ['Passwords do not match']
    return false
  }
  if (formData.value.password.length < 8) {
    errors.value.password = ['Password must be at least 8 characters long']
    return false
  }
  return true
}

const handleSubmit = async () => {
  if (isSubmitting.value) return
  
  try {
    isSubmitting.value = true
    errors.value = {}

    // Validate passwords
    if (!validatePasswords()) {
      isSubmitting.value = false
      return
    }

    // Check if location is selected
    if (!formData.value.location) {
      console.error('No location selected')
      errors.value.location = ['Please select a location']
      isSubmitting.value = false
      return
    }

    // Create FormData object for file upload
    const formDataToSend = new FormData()
    
    // Append all form fields
    formDataToSend.append('role', formData.value.accountType)
    formDataToSend.append('fullname', formData.value.fullname)
    formDataToSend.append('username', formData.value.username)
    formDataToSend.append('email', formData.value.email)
    formDataToSend.append('location_id', formData.value.location)
    formDataToSend.append('password', formData.value.password)
    
    // Append the image file if it exists
    if (formData.value.avatar) {
      formDataToSend.append('image', formData.value.avatar)
    }

    console.log('About to send form data with image')

    // Send request to Laravel API with proper headers for multipart form data
    const response = await axiosClient.post('/register', formDataToSend, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.data) {
      console.log('Account created successfully:', response.data)
      router.push('/admin')
    }
  } catch (error) {
    console.error('Full error object:', error)
    console.error('Error response data:', error.response?.data)
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      errors.value = {
        general: ['An unexpected error occurred. Please try again.']
      }
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.form-group {
  @apply space-y-1;
}

.form-label {
  @apply block text-sm font-medium text-gray-700;
}

.form-input, .form-select {
  @apply block w-full pl-12 pr-3 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500;
}

/* Add new styles for the icon container */
.relative {
  position: relative;
}

.relative span.absolute {
  @apply flex items-center justify-center;
  height: 100%;
  width: 40px;
  pointer-events: none;
}

.material-icons-outlined {
  @apply text-xl leading-none;
}
</style>
