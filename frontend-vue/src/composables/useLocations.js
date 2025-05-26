import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function useLocations(formData = null) {
  const locations = ref([])

  const fetchLocations = async () => {
    try {
      const response = await axiosClient.get('/locations')
      if (response.data && response.data.data) {
        locations.value = response.data.data
        console.log('Available locations:', locations.value)
      }
    } catch (error) {
      console.error('Error fetching locations:', error)
    }
  }

  onMounted(() => {
    fetchLocations()
  })

  if (formData) {
    watch(() => formData.value.location, (newValue) => {
      console.log('Location selected:', newValue)
    })
  }

  return {
    locations,
    fetchLocations
  }
}
