import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'

export const usePostStore = defineStore('post', () => {
  const current = ref(null)
  const comments = ref([])
  const loading = ref(false)
  const error = ref(null)

  function setCurrent(post) {
    current.value = post
    comments.value = post?.comments || []
  }

  async function addComment(id, body) {
    const { data } = await api.post(`/posts/${id}/comments`, { body })
    const created = data?.data || data
    comments.value.push(created)
    if (current.value) {
      current.value.comments_count = (current.value.comments_count || 0) + 1
    }
    return created
  }

  async function toggleLike(id) {
    if (!current.value || current.value.id !== id) return
    const prev = !!current.value.liked
    const prevCount = current.value.likes_count || 0
    current.value.liked = !prev
    current.value.likes_count = prevCount + (prev ? -1 : 1)
    try {
      await api.post(`/posts/${id}/like`)
    } catch (e) {
      current.value.liked = prev
      current.value.likes_count = prevCount
      throw e
    }
  }

  function reset() {
    current.value = null
    comments.value = []
    error.value = null
  }

  return { current, comments, loading, error, setCurrent, addComment, toggleLike, reset }
})
