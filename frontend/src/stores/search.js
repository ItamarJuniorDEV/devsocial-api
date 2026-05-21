import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'

export const useSearchStore = defineStore('search', () => {
  const users = ref([])
  const posts = ref([])
  const lastQuery = ref('')
  const loading = ref(false)
  const error = ref(null)

  async function search(q) {
    if (!q || q.length < 2) return
    if (lastQuery.value === q && (users.value.length || posts.value.length)) return
    loading.value = true
    error.value = null
    try {
      const { data } = await api.get('/search', { params: { q } })
      const body = data?.data || {}
      users.value = body.users || []
      posts.value = body.posts || []
      lastQuery.value = q
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  function reset() {
    users.value = []
    posts.value = []
    lastQuery.value = ''
  }

  return { users, posts, lastQuery, loading, error, search, reset }
})
