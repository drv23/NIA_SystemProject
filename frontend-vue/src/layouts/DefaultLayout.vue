<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const user = ref({
  name: 'JGludo',
  avatar: '../assets/avatar.jpg'
})

const isSidebarOpen = ref(false)

const navigation = [
  { name: 'Dashboard', path: '/dashboard', icon: 'dashboard' },
  { name: 'Inventory', path: '/inventory', icon: 'inventory' },
  { name: 'Admins', path: '/admin', icon: 'people' },
  { name: 'Analytics', path: '/analytics', icon: 'analytics' }
]

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-20 transform transition-transform duration-300 ease-in-out lg:translate-x-0',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <!-- Logo -->
      <div class="flex items-center px-4 sm:px-6 py-3 sm:py-4 h-14 sm:h-16">
        <img src="../assets/logo.png" alt="IrrigTrack" class="h-6 w-6 sm:h-8 sm:w-8" />
        <span class="ml-2 text-lg sm:text-xl font-semibold text-gray-800">IrrigTrack</span>
      </div>

      <!-- Navigation -->
      <nav class="mt-2 sm:mt-4 px-2 sm:px-4">
        <router-link
          v-for="item in navigation"
          :key="item.name"
          :to="item.path"
          :class="[
            'flex items-center px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-700 rounded-lg mb-1',
            $route.path === item.path
              ? 'bg-green-50 text-green-600'
              : 'hover:bg-gray-50'
          ]"
          @click="isSidebarOpen = false"
        >
          <span class="material-icons-outlined mr-2 sm:mr-3 text-current text-xl sm:text-2xl">{{ item.icon }}</span>
          {{ item.name }}
        </router-link>

        <!-- Create Account Button -->
        <div class="px-2 sm:px-4 mt-6 sm:mt-8">
          <button class="w-full bg-green-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center justify-center hover:bg-green-700 text-sm sm:text-base">
            <span class="material-icons-outlined mr-2 text-xl sm:text-2xl">add</span>
            Create account
          </button>
        </div>
      </nav>
    </aside>

    <!-- Overlay -->
    <div 
      v-if="isSidebarOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-10 lg:hidden"
      @click="toggleSidebar"
    ></div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
      <!-- Top Bar -->
      <header class="h-14 sm:h-16 bg-white shadow-sm flex items-center justify-between px-3 sm:px-4 lg:px-6 sticky top-0 z-30">
        <div class="flex items-center gap-2 sm:gap-4">
          <!-- Mobile Menu Button -->
          <button 
            class="lg:hidden p-1.5 sm:p-2 rounded-lg hover:bg-gray-100"
            @click="toggleSidebar"
          >
            <span class="material-icons-outlined text-xl sm:text-2xl">menu</span>
          </button>
          <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800">{{ route.name }}</h1>
        </div>

        <!-- Right Side Icons -->
        <div class="flex items-center space-x-2 sm:space-x-4">
          <!-- Dark Mode Toggle -->
          <button class="p-1.5 sm:p-2 rounded-full hover:bg-gray-100">
            <span class="material-icons-outlined text-xl sm:text-2xl">dark_mode</span>
          </button>

          <!-- Notifications -->
          <button class="p-1.5 sm:p-2 rounded-full hover:bg-gray-100 relative">
            <span class="material-icons-outlined text-xl sm:text-2xl">notifications</span>
            <span class="absolute top-0 right-0 h-3.5 w-3.5 sm:h-4 sm:w-4 bg-red-500 rounded-full text-[10px] sm:text-xs text-white flex items-center justify-center">
              2
            </span>
          </button>

          <!-- Profile -->
          <button class="flex items-center space-x-2">
            <img
              :src="user.avatar"
              alt="Profile"
              class="h-7 w-7 sm:h-8 sm:w-8 rounded-full object-cover"
            />
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-3 sm:p-4 lg:p-6">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons+Outlined');

.material-icons-outlined {
  font-family: 'Material Icons Outlined';
  font-weight: normal;
  font-style: normal;
  font-size: inherit;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-smoothing: antialiased;
}
</style> 