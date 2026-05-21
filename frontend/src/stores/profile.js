import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'

export const useProfileStore = defineStore('profile', () => {
  const profiles = ref(new Map())
  const loading = ref(false)
  const error = ref(null)

  function getProfile(id) {
    return profiles.value.get(Number(id)) || null
  }

  function setProfile(user) {
    if (user?.id != null) {
      profiles.value.set(Number(user.id), user)
    }
  }

  async function updateMe(payload) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.put('/user/profile', payload)
      const updated = data?.data || data
      setProfile(updated)
      return updated
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  async function toggleFollow(userId) {
    const current = getProfile(userId)
    if (current) {
      const prev = !!current.is_following
      const prevCount = current.followers_count || 0
      current.is_following = !prev
      current.followers_count = prevCount + (prev ? -1 : 1)
      try {
        await api.post(`/users/${userId}/follow`)
      } catch (e) {
        current.is_following = prev
        current.followers_count = prevCount
        throw e
      }
      return
    }
    await api.post(`/users/${userId}/follow`)
  }

  async function toggleLikeOnList(list, postId) {
    const target = list.find((p) => p.id === postId)
    if (!target) return
    const prev = !!target.liked
    const prevCount = target.likes_count || 0
    target.liked = !prev
    target.likes_count = prevCount + (prev ? -1 : 1)
    try {
      await api.post(`/posts/${postId}/like`)
    } catch (e) {
      target.liked = prev
      target.likes_count = prevCount
      throw e
    }
  }

  return {
    profiles,
    loading,
    error,
    getProfile,
    setProfile,
    toggleFollow,
    toggleLikeOnList,
    updateMe
  }
})
