/**
 * Simple utility for storing deleted items in localStorage
 * This is a temporary solution until the backend soft delete functionality is implemented
 */

// Get all deleted items from localStorage
export const getDeletedItems = () => {
  try {
    const items = localStorage.getItem('deletedItems')
    return items ? JSON.parse(items) : []
  } catch (error) {
    console.error('Error retrieving deleted items from localStorage:', error)
    return []
  }
}

// Add a deleted item to localStorage
export const addDeletedItem = (item) => {
  try {
    const items = getDeletedItems()
    
    // Add deletion metadata
    const deletedItem = {
      ...item,
      deletedAt: new Date().toISOString(),
      deletionReason: item.deletionReason || 'User initiated deletion'
    }
    
    // Add to the beginning of the array (most recent first)
    items.unshift(deletedItem)
    
    // Save back to localStorage
    localStorage.setItem('deletedItems', JSON.stringify(items))
    
    return true
  } catch (error) {
    console.error('Error adding deleted item to localStorage:', error)
    return false
  }
}

// Remove a deleted item from localStorage (for restore functionality)
export const removeDeletedItem = (uuid) => {
  try {
    const items = getDeletedItems()
    const filteredItems = items.filter(item => item.uuid !== uuid)
    localStorage.setItem('deletedItems', JSON.stringify(filteredItems))
    return true
  } catch (error) {
    console.error('Error removing deleted item from localStorage:', error)
    return false
  }
}

// Clear all deleted items from localStorage
export const clearDeletedItems = () => {
  try {
    localStorage.removeItem('deletedItems')
    return true
  } catch (error) {
    console.error('Error clearing deleted items from localStorage:', error)
    return false
  }
}