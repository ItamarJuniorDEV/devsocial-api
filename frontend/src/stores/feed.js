import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'

export const useFeedStore = defineStore('feed', () => {
  const posts = ref([])
  const page = ref(0)
  const hasMore = ref(true)
  const loading = ref(false)
  const error = ref(null)

  async function fetchMore() {
    if (loading.value || !hasMore.value) return
    loading.value = true
    error.value = null
    try {
      const next = page.value + 1
      const { data } = await api.get('/feed', { params: { page: next } })
      const list = data?.data || []
      const meta = data?.meta || {}
      posts.value.push(...list)
      page.value = next
      if (meta.last_page) {
        hasMore.value = next < meta.last_page
      } else {
        hasMore.value = list.length > 0
      }
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  async function refresh() {
    posts.value = []
    page.value = 0
    hasMore.value = true
    await fetchMore()
  }

  async function createPost(body) {
    const { data } = await api.post('/posts', { type: 'text', body })
    const created = data?.data || data
    posts.value.unshift(created)
    return created
  }

  async function toggleLike(postId) {
    const target = posts.value.find((p) => p.id === postId)
    if (!target) return
    const prevLiked = !!target.liked
    const prevCount = target.likes_count || 0
    target.liked = !prevLiked
    target.likes_count = prevCount + (prevLiked ? -1 : 1)
    try {
      await api.post(`/posts/${postId}/like`)
    } catch (e) {
      target.liked = prevLiked
      target.likes_count = prevCount
      throw e
    }
  }

  return { posts, page, hasMore, loading, error, fetchMore, refresh, createPost, toggleLike }
})
