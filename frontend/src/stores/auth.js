import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from 'boot/axios'

const TOKEN_KEY = 'devsocial_token'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem(TOKEN_KEY) || null)
  const user = ref(null)
  const loading = ref(false)

  const isAuthenticated = computed(() => !!token.value)

  function setToken(value) {
    token.value = value
    if (value) {
      localStorage.setItem(TOKEN_KEY, value)
    } else {
      localStorage.removeItem(TOKEN_KEY)
    }
  }

  async function login(credentials) {
    loading.value = true
    try {
      const { data } = await api.post('/login', credentials)
      setToken(data.token || data.access_token)
      user.value = data.user
    } finally {
      loading.value = false
    }
  }

  async function register(payload) {
    loading.value = true
    try {
      const { data } = await api.post('/register', payload)
      setToken(data.token || data.access_token)
      user.value = data.user
    } finally {
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return
    try {
      const { data } = await api.get('/me')
      user.value = data.user || data
    } catch {
      logout()
    }
  }

  function logout() {
    setToken(null)
    user.value = null
  }

  return {
    token,
    user,
    loading,
    isAuthenticated,
    login,
    register,
    fetchUser,
    logout,
    setToken
  }
})
