<template>
  <q-layout view="hHh LpR fFf">
    <q-header bordered class="bg-dark text-white">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          class="lt-md"
          @click="drawerOpen = !drawerOpen"
        />
        <q-toolbar-title class="font-mono text-primary">DevSocial</q-toolbar-title>
        <q-input
          v-model="searchQuery"
          dense
          dark
          standout="bg-grey-9 text-white"
          placeholder="Buscar"
          class="q-mx-md gt-sm"
          style="min-width: 280px"
          @keyup.enter="goSearch"
        >
          <template #append>
            <q-icon name="search" class="cursor-pointer" @click="goSearch" />
          </template>
        </q-input>
        <q-space />
        <q-btn flat round dense>
          <q-avatar size="32px" color="primary" text-color="dark">
            <span class="font-mono">{{ initials }}</span>
          </q-avatar>
          <q-menu anchor="bottom right" self="top right">
            <q-list style="min-width: 180px">
              <q-item v-if="auth.user" clickable v-close-popup @click="goProfile">
                <q-item-section>Meu perfil</q-item-section>
              </q-item>
              <q-item clickable v-close-popup :to="{ name: 'ProfileEdit' }">
                <q-item-section>Configuracoes</q-item-section>
              </q-item>
              <q-separator />
              <q-item clickable v-close-popup @click="logout">
                <q-item-section class="text-negative">{{ $t('auth.logout') }}</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawerOpen"
      show-if-above
      bordered
      :width="240"
      class="bg-dark"
    >
      <q-list>
        <q-item clickable v-ripple :to="{ name: 'Feed' }" exact>
          <q-item-section avatar><q-icon name="home" /></q-item-section>
          <q-item-section>Feed</q-item-section>
        </q-item>
        <q-item clickable v-ripple :to="{ name: 'Search' }">
          <q-item-section avatar><q-icon name="search" /></q-item-section>
          <q-item-section>Buscar</q-item-section>
        </q-item>
        <q-item v-if="auth.user" clickable v-ripple :to="profileRoute">
          <q-item-section avatar><q-icon name="person" /></q-item-section>
          <q-item-section>Meu perfil</q-item-section>
        </q-item>
        <q-item clickable v-ripple :to="{ name: 'ProfileEdit' }">
          <q-item-section avatar><q-icon name="settings" /></q-item-section>
          <q-item-section>Configuracoes</q-item-section>
        </q-item>
        <q-separator class="q-my-sm" />
        <q-item clickable v-ripple @click="logout">
          <q-item-section avatar><q-icon name="logout" /></q-item-section>
          <q-item-section class="text-negative">{{ $t('auth.logout') }}</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'

const router = useRouter()
const auth = useAuthStore()
const drawerOpen = ref(false)
const searchQuery = ref('')

const initials = computed(() => {
  const name = auth.user?.name || '?'
  return name.trim().substring(0, 1).toUpperCase()
})

const profileRoute = computed(() => {
  if (!auth.user?.id) return { name: 'Feed' }
  return { name: 'Profile', params: { id: auth.user.id } }
})

function goSearch() {
  router.push({ name: 'Search', query: { q: searchQuery.value } })
}

function goProfile() {
  if (auth.user?.id) {
    router.push({ name: 'Profile', params: { id: auth.user.id } })
  }
}

async function logout() {
  await auth.logout()
  router.push({ name: 'Login' })
}
</script>
