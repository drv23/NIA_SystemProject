<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const user = ref({
  fullName: 'Jessie John Gludo',
  username: 'Jgludo',
  email: 'xjsupreme@gmail.com',
  avatar: '../assets/avatar.jpg'
})

const goBack = () => {
  router.push('/dashboard')
}
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Back Button -->
    <button 
      @click="goBack"
      class="mb-6 inline-flex items-center text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300"
    >
      <span class="material-icons-outlined mr-1 text-xl">arrow_back</span>
      Back to Dashboard
    </button>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-green-400 mb-2">Admin</h1>
    <p class="text-sm text-green-600 dark:text-green-400 mb-8">Profile</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Edit Form -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form @submit.prevent>
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Avatar</label>
            <div class="flex items-center">
              <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                Choose File
              </button>
              <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">No file chosen</span>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
            <input 
              type="text" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
              placeholder="Enter full name"
            >
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
            <input 
              type="text" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
              placeholder="Enter username"
            >
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input 
              type="email" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
              placeholder="Enter email"
            >
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
            <select 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
            >
              <option value="">Select Location</option>
              <option value="location1">Location 1</option>
              <option value="location2">Location 2</option>
            </select>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
            <input 
              type="password" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
              placeholder="Enter password"
            >
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
            <input 
              type="password" 
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-300"
              placeholder="Confirm password"
            >
          </div>

          <button 
            type="submit"
            class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-700 dark:hover:bg-green-600"
          >
            Edit
          </button>
        </form>
      </div>

      <!-- Account Details -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-green-400 mb-6">Account Details</h2>
        
        <div class="flex justify-center mb-8">
          <div class="relative">
            <img 
              :src="user.avatar" 
              alt="Profile" 
              class="w-32 h-32 rounded-full object-cover"
            >
            <span class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></span>
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
</template> 