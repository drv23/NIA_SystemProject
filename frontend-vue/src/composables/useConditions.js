import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function useConditions(formData = null) {
  const conditions = ref([])

  const fetchconditions = async () => {
    try {
      const response = await axiosClient.get('/conditions')
      if (response.data && response.data.data) {
        conditions.value = response.data.data
        console.log('Available conditions:', conditions.value)
      }
    } catch (error) {
      console.error('Error fetching conditions:', error)
    }
  }

  onMounted(() => {
    fetchconditions()
  })

  if (formData) {
    watch(() => formData.value.condition, (newValue) => {
      console.log('Location selected:', newValue)
    })
  }

  return {
    conditions,
    fetchconditions
  }
}
