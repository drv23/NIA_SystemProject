import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function usecategories(formData = null) {
  const categories = ref([])

  const fetchcategories = async () => {
    try {
      const response = await axiosClient.get('/categories')
      if (response.data && response.data.data) {
        categories.value = response.data.data
        console.log('Available categories:', categories.value)
      }
    } catch (error) {
      console.error('Error fetching categories:', error)
    }
  }

  onMounted(() => {
    fetchcategories()
  })

  if (formData) {
    watch(() => formData.value.category, (newValue) => {
      console.log('Location selected:', newValue)
    })
  }

  return {
    categories,
    fetchcategories
  }
}
