
<template>
  <div>
    <!-- Preloader -->
    <div v-if="showPreloader" class="pre-loader">
      <div class="pre-loader-box">
        <div class="loader-logo">
          <img src="../assets/logo.png" alt="" class="w-24 h-24" />
        </div>
        <div class="loader-progress" id="progress_div">
          <div class="bar" :style="{ width: `${progress}%` }"></div>
        </div>
        <div class="percent" id="percent1">{{ progress }}%</div>
        <div class="loading-text">{{ isLoading ? 'Logging in...' : 'Loading...' }}</div>
      </div>
    </div>

    <!-- Main Login Form -->
    <div class="login-container min-h-screen relative flex items-center p-6">
      <!-- Background Image -->`
      <div class="absolute inset-0 bg-image"></div>
      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
      
      <div class="mx-auto flex-1 max-w-4xl overflow-hidden rounded-lg bg-white/90 backdrop-blur-md shadow-2xl relative z-10">
        <div class="flex flex-col overflow-y-auto md:flex-row">
          <!-- Left Image Section -->
          <div class="h-32 md:h-auto md:w-1/2">
            <img 
              class="h-full w-full object-cover"
              src="../assets/image-section.jpg" 
              alt="Irrigation System" 
            />
          </div>

          <!-- Right Login Section -->  
          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <!-- Logo and Title -->
              <div class="mb-8 flex items-center">
                <img src="../assets/logo.png" alt="IrrigTrack Logo" class="mr-3 h-10 w-10" />
                <h1 class="text-xl font-semibold text-gray-700">
                  IrrigTrack - Tracking Management System
                </h1>
              </div>

              <!-- Login Form -->
              <form @submit.prevent="login">
                <!-- Email Input -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700">
                    Email
                  </label>
                  <input
                    v-model="data.email"
                    type="email"
                    required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                    placeholder="jane.doe@example.com"
                  />
                  <p v-if="errors.email" class="error-msg">{{ errors.email[0] }}</p>
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700">
                    Password
                  </label>
                  <input
                    v-model="data.password"
                    type="password"
                    required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                    placeholder="••••••••"
                  />
                  <p v-if="errors.password" class="error-msg">{{ errors.password }}</p>
                </div>

                <p v-if="errors.general" class="error-msg text-red-600 text-center mb-4">{{ errors.general[0] }}</p>

                <!-- Login Button -->
                <button
                  type="submit"
                  :disabled="isLoading"
                  class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                  Log in
                </button>
              </form>

              <!-- Divider -->
              <div class="my-8 flex items-center">
                <div class="flex-1 border-t border-gray-300"></div>
                <div class="mx-4 text-sm text-gray-500">or</div>
                <div class="flex-1 border-t border-gray-300"></div>
              </div>

              <!-- Google Sign In -->
              <button
                type="button"
                class="flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24">
                  <path
                    fill="currentColor"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                  />
                  <path
                    fill="currentColor"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                  />
                  <path
                    fill="currentColor"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                  />
                  <path
                    fill="currentColor"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                  />
                </svg>
                Continue with Google
              </button>

              <!-- Forgot Password Link -->
              <div class="mt-6 text-center">
                <router-link
                  to="/forgot-password"
                  class="text-sm text-green-600 hover:text-green-500"
                >
                  Forgot your password?
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axiosClient from '../axios'

const router = useRouter()
// const email = ref('')
// const password = ref('')
const isLoading = ref(false)
const progress = ref(0)
const showPreloader = ref(true)

// Simulate initial page load
const initializeLoader = () => {
  let loadProgress = 0
  const interval = setInterval(() => {
    loadProgress += 2
    progress.value = loadProgress
    if (loadProgress >= 100) {
      clearInterval(interval)
      showPreloader.value = false
    }
  }, 20)
}

// Call initialize loader when component mounts
initializeLoader()

const data = ref({
  email: '',
  password: ''
})

const errors = ref({})

const login = async () => {
  isLoading.value = true
  showPreloader.value = true
  progress.value = 0
  errors.value = {}

  // Optional fake delay
  await new Promise(resolve => setTimeout(resolve, 500))

  let loadProgress = 0
  const interval = setInterval(async () => {
    loadProgress += 5
    progress.value = loadProgress

    if (loadProgress >= 100) {
      clearInterval(interval)

      try {
        const response = await axiosClient.post('/login', data.value)
        // console.log('Login response:', response)
        
        if (response.status === 200) {
          // Save token if it exists in response
          if (response.data.token) {
            localStorage.setItem('token', response.data.token)
          }
          // Navigate to dashboard
          await router.push('/dashboard')
        }
      } catch (error) {
        if (error.response && (error.response.status === 401 || error.response.status === 422)) {
          errors.value = error.response.data.errors || { general: [error.response.data.message] }
        } else {
          console.error('Unexpected error:', error.message)
          errors.value = { general: ['An unexpected error occurred. Please try again.'] }
        }
      } finally {
        isLoading.value = false
        showPreloader.value = false
      }
    }
  }, 50)
}
</script>

<style scoped>
.login-container {
  background-color: #f3f4f6;
}

.bg-image {
  background-image: url('../assets/bg.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  filter: blur(4px);
}

.pre-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: white;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.pre-loader-box {
  text-align: center;
}

.loader-progress {
  width: 200px;
  height: 4px;
  background: #f3f3f3;
  border-radius: 4px;
  margin: 20px auto;
  overflow: hidden;
}

.bar {
  width: 0%;
  height: 100%;
  background-color: #22c55e;
  animation: progress 2s ease-in-out infinite;
}

.percent {
  color: #666;
  font-size: 14px;
  margin: 8px 0;
}

.loading-text {
  color: #666;
  font-size: 14px;
}

@keyframes progress {
  0% { width: 0%; }
  50% { width: 100%; }
  100% { width: 0%; }
}
</style>


