import { ref, onMounted, watch } from 'vue'
import axiosClient from '../axios' // or wherever your axiosClient is

export default function useItems() {
  const items = ref([])

  const fetchitems = async () => {
    try {
      const response = await axiosClient.get('/items')
      if (response.data && response.data.data) {
        items.value = response.data.data
        console.log('Available items:', items.value)
      }
    } catch (error) {
      console.error('Error fetching items:', error)
    }
  }

  onMounted(() => {
    fetchitems()
  })

//   if (formData) {
//     watch(() => formData.value.item, (newValue) => {
//       console.log('item selected:', newValue)
//     })
//   }

  return {
    items,
    fetchitems
  }
}
