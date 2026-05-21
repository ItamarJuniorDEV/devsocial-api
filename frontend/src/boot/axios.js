import { defineBoot } from '#q-app/wrappers'
import axios from 'axios'
import { useAuthStore } from 'stores/auth'

const api = axios.create({
  baseURL: '/api',
  withCredentials: false
})

let routerRef = null

api.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  config.headers.Accept = 'application/json'
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      const auth = useAuthStore()
      auth.logout()
      if (routerRef) {
        const current = routerRef.currentRoute.value
        if (current?.meta?.auth) {
          routerRef.push({ name: 'Login' })
        }
      }
    }
    return Promise.reject(error)
  }
)

export default defineBoot(({ app, router }) => {
  routerRef = router
  app.config.globalProperties.$api = api
})

export { api }
