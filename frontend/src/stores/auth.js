import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from 'boot/axios'

const TOKEN_KEY = 'devsocial_token'

function unwrap(payload) {
  return payload?.data ?? payload
}

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
      const { data } = await api.post('/auth/login', credentials)
      const body = unwrap(data)
      setToken(body.token)
      user.value = body.user || null
    } finally {
      loading.value = false
    }
  }

  async function register(payload) {
    loading.value = true
    try {
      const { data } = await api.post('/auth/register', payload)
      const body = unwrap(data)
      setToken(body.token)
      user.value = body.user || null
    } finally {
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return
    try {
      const { data } = await api.get('/auth/me')
      user.value = unwrap(data)
    } catch {
      setToken(null)
      user.value = null
    }
  }

  async function logout() {
    if (token.value) {
      try {
        await api.post('/auth/logout')
      } catch {
        //
      }
    }
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
