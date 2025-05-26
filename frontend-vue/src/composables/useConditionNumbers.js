import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function useConditionNumbers(formData = null) {
  const condition_numbers = ref([])

  const fetchcondition_numbers = async () => {
    try {
      const response = await axiosClient.get('/condition_numbers')
      if (response.data && response.data.data) {
        condition_numbers.value = response.data.data
        console.log('Available condition_numbers:', condition_numbers.value)
      }
    } catch (error) {
      console.error('Error fetching condition_numbers:', error)
    }
  }

  onMounted(() => {
    fetchcondition_numbers()
  })

  if (formData) {
    watch(() => formData.value.condition_number, (newValue) => {
      console.log('Location selected:', newValue)
    })
  }

  return {
    condition_numbers,
    fetchcondition_numbers
  }
}
