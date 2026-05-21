<template>
  <q-page class="q-pa-md bg-dark-page">
    <div class="profile-wrap q-mx-auto">
      <q-card flat class="card-surface q-pa-md q-mb-md">
        <div v-if="!profile" class="row items-center q-gutter-md">
          <q-skeleton type="QAvatar" size="96px" />
          <div class="col">
            <q-skeleton type="text" width="40%" />
            <q-skeleton type="text" width="30%" />
          </div>
        </div>
        <div v-else class="row items-center q-gutter-md">
          <UserAvatar :user="profile" size="96px" />
          <div class="col">
            <div class="text-h5">{{ profile.name }}</div>
            <div v-if="profile.bio" class="q-mt-sm">{{ profile.bio }}</div>
            <div v-if="profile.city || profile.work" class="text-muted text-caption q-mt-xs">
              <span v-if="profile.work">{{ profile.work }}</span>
              <span v-if="profile.work && profile.city"> &middot; </span>
              <span v-if="profile.city">{{ profile.city }}</span>
            </div>
          </div>
          <div>
            <q-btn
              v-if="isMe"
              outline
              color="primary"
              :label="$t('profile.editProfile')"
              :to="{ name: 'ProfileEdit' }"
            />
            <q-btn
              v-else
              :outline="isFollowing"
              color="primary"
              :label="isFollowing ? $t('profile.unfollow') : $t('profile.follow')"
              :loading="followLoading"
              unelevated
              @click="toggleFollow"
            />
          </div>
        </div>
      </q-card>

      <EmptyState
        v-if="!profile && !loading"
        icon="info"
        title="Detalhes deste perfil estao limitados"
        hint="A API ainda nao expoe perfil por id. Mostramos o que voce ja viu no feed ou na busca."
      />
    </div>
  </q-page>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { useProfileStore } from 'stores/profile'
import { notifyError } from 'src/utils/notify'
import UserAvatar from 'components/UserAvatar.vue'
import EmptyState from 'components/EmptyState.vue'

const route = useRoute()
const auth = useAuthStore()
const store = useProfileStore()

const followLoading = ref(false)
const loading = ref(false)
const localFollowing = ref(false)

const userId = computed(() => Number(route.params.id))
const profile = computed(() => {
  const cached = store.getProfile(userId.value)
  if (cached) return cached
  if (auth.user && Number(auth.user.id) === userId.value) return auth.user
  return null
})
const isMe = computed(() => auth.user && Number(auth.user.id) === userId.value)
const isFollowing = computed(() => localFollowing.value)

watch(profile, (p) => {
  if (p) localFollowing.value = !!p.is_following
}, { immediate: true })

onMounted(() => {
  if (auth.user && !store.getProfile(auth.user.id)) {
    store.setProfile(auth.user)
  }
})

async function toggleFollow() {
  if (isMe.value) return
  followLoading.value = true
  const prev = localFollowing.value
  localFollowing.value = !prev
  try {
    await store.toggleFollow(userId.value)
  } catch {
    localFollowing.value = prev
    notifyError('Falha ao seguir')
  } finally {
    followLoading.value = false
  }
}
</script>

<style lang="scss" scoped>
.profile-wrap {
  max-width: 720px;
}
</style>
