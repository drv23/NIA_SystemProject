import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function useUsers(formData = null) {
  const users = ref([])

  const fetchusers = async () => {
    try {
      const response = await axiosClient.get('/users')
      if (response.data && response.data.data) {
        users.value = response.data.data
        console.log('Available users:', users.value)
      }
    } catch (error) {
      console.error('Error fetching users:', error)
    }
  }

  onMounted(() => {
    fetchusers()
  })

  if (formData) {
    watch(() => formData.value.user, (newValue) => {
      console.log('user selected:', newValue)
    })
  }

  return {
    users,
    fetchusers
  }
}
