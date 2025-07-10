<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const formData = ref({
  article: '',
  category: '',
  description: '',
  propertyAccountCode: '',
  stock: '',
  dateAcquired: '',
  poNumber: '',
  location: '',
  issuedTo: '',
  condition: '',
  conditionNumber: '',
  image: null
})

const handleBack = () => {
  router.push({ name: 'Inventory' })
}

const handleSubmit = () => {
  // Add logic to handle form submission
  console.log('Form submitted:', formData.value)
  // After successful submission, redirect back to inventory
  router.push({ name: 'Inventory' })
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.value.image = file
  }
}
</script>

<template>
  <div class="p-6 bg-white rounded-lg">
    <div class="flex items-center mb-6">
      <button @click="handleBack" class="flex items-center text-gray-600 hover:text-gray-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back
      </button>
    </div>

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Supplies</h1>
    <h2 class="text-lg text-gray-600 mb-6">Add new item</h2>

    <form @submit.prevent="handleSubmit" class="space-y-6 max-w-3xl">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Article:</label>
          <select v-model="formData.article" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select article</option>
            <option value="desktop">Desktop</option>
            <option value="laptop">Laptop</option>
            <option value="printer">Printer</option>
            <option value="scanner">Scanner</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category:</label>
          <select v-model="formData.category" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select category</option>
            <option value="hardware">Hardware</option>
            <option value="software">Software</option>
            <option value="peripherals">Peripherals</option>
            <option value="network">Network Equipment</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
          <textarea
            v-model="formData.description"
            required
            rows="3"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Property Account Code:</label>
          <input
            type="text"
            v-model="formData.propertyAccountCode"
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Stock:</label>
          <input
            type="number"
            v-model="formData.stock"
            required
            min="0"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date Acquired:</label>
          <input
            type="date"
            v-model="formData.dateAcquired"
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">P.O Number:</label>
          <input
            type="text"
            v-model="formData.poNumber"
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Location:</label>
          <select v-model="formData.location" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select location</option>
            <option value="office1">Office 1</option>
            <option value="office2">Office 2</option>
            <option value="storage">Storage Room</option>
            <option value="lab">Laboratory</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Issued To:</label>
          <select v-model="formData.issuedTo" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select person</option>
            <option value="john">John Doe</option>
            <option value="jane">Jane Smith</option>
            <option value="mike">Mike Johnson</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Condition:</label>
          <select v-model="formData.condition" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select condition</option>
            <option value="new">New</option>
            <option value="good">Good</option>
            <option value="fair">Fair</option>
            <option value="poor">Poor</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Condition number:</label>
          <select v-model="formData.conditionNumber" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
            <option value="" disabled selected>Select condition number</option>
            <option value="1">1 - Excellent</option>
            <option value="2">2 - Very Good</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Fair</option>
            <option value="5">5 - Poor</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Image:</label>
          <div class="flex items-center space-x-2">
            <button
              type="button"
              @click="$refs.fileInput.click()"
              class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
              Choose File
            </button>
            <span class="text-sm text-gray-500">{{ formData.image ? formData.image.name : 'No file chosen' }}</span>
            <input
              ref="fileInput"
              type="file"
              @change="handleFileChange"
              accept="image/*"
              class="hidden"
            />
          </div>
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
          Add
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
</style> 