<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const user = ref({
  fullName: 'Jessie John Gludo',
  username: 'jgludo',
  email: 'xjsupreme@gmail.com',
  location: '',
  avatar: '../assets/avatar.jpg'
})

const selectedFile = ref(null)
const previewImage = ref(null)

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      previewImage.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const goToDashboard = () => {
  router.push('/dashboard')
}
</script>

<template>
  <div class="p-4 sm:p-6 lg:p-8">
    <!-- Back to Dashboard Button -->
    <button 
      @click="goToDashboard"
      class="mb-6 inline-flex items-center text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
    >
      <span class="material-icons-outlined mr-1">arrow_back</span>
      Back to Dashboard
    </button>

    <div class="flex flex-col lg:flex-row gap-8">
      <!-- Left Column - Form -->
      <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Admin</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Profile</p>

        <form class="space-y-6">
          <!-- Avatar Upload -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Avatar</label>
            <div class="flex items-center">
              <label class="relative cursor-pointer">
                <input
                  type="file"
                  class="hidden"
                  accept="image/*"
                  @change="handleFileChange"
                />
                <div class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                  Choose File
                </div>
              </label>
              <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                {{ selectedFile ? selectedFile.name : 'No file chosen' }}
              </span>
            </div>
          </div>

          <!-- Full Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
            <input
              type="text"
              v-model="user.fullName"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            />
          </div>

          <!-- Username -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
            <input
              type="text"
              v-model="user.username"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input
              type="email"
              v-model="user.email"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            />
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
            <select
              v-model="user.location"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            >
              <option value="" disabled>Select Location</option>
              <option value="location1">Location 1</option>
              <option value="location2">Location 2</option>
              <option value="location3">Location 3</option>
            </select>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
            <input
              type="password"
              placeholder="Enter password"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            />
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
            <input
              type="password"
              placeholder="Confirm password"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300"
            />
          </div>
        </form>
      </div>

      <!-- Right Column - Account Details -->
      <div class="lg:w-1/3">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Account Details</h2>
          
          <div class="flex justify-center mb-6">
            <div class="relative">
              <img
                :src="previewImage || user.avatar"
                alt="Profile"
                class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-600"
              />
              <span class="absolute bottom-0 right-0 bg-green-500 rounded-full w-6 h-6 border-2 border-white dark:border-gray-600"></span>
            </div>
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400">Full Name</label>
              <p class="text-gray-800 dark:text-gray-200">{{ user.fullName }}</p>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400">User Name</label>
              <p class="text-gray-800 dark:text-gray-200">{{ user.username }}</p>
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-400">Email</label>
              <p class="text-gray-800 dark:text-gray-200">{{ user.email }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any component-specific styles here */
</style> 