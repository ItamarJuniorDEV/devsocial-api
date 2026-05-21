<template>
  <q-page class="q-pa-md bg-dark-page">
    <div class="post-wrap q-mx-auto">
      <PostCard
        v-if="post.current"
        :post="post.current"
        class="q-mb-md"
        expanded
        @like="post.toggleLike(post.current.id)"
        @profile="goProfile"
      />

      <EmptyState
        v-else
        icon="info"
        title="Post nao disponivel diretamente"
        hint="A API nao expoe GET de post por id. Volte ao feed."
      />

      <q-card v-if="post.current" flat class="card-surface q-pa-md q-mb-md">
        <CommentComposer @submit="onComment" :loading="commenting" />
      </q-card>

      <div v-if="post.current && post.comments.length">
        <q-card
          v-for="c in post.comments"
          :key="c.id"
          flat
          class="card-surface q-pa-md q-mb-sm"
        >
          <div class="row items-center q-gutter-sm">
            <UserAvatar :user="c.user" size="32px" />
            <div>
              <div><b>{{ c.user?.name }}</b></div>
              <div class="text-muted text-caption">{{ formatRelative(c.created_at) }}</div>
            </div>
          </div>
          <div class="q-mt-sm" style="white-space: pre-wrap">{{ c.body }}</div>
        </q-card>
      </div>
      <EmptyState
        v-else-if="post.current"
        icon="comment"
        :title="$t('post.noComments')"
      />
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useFeedStore } from 'stores/feed'
import { usePostStore } from 'stores/post'
import { formatRelative } from 'src/utils/formatDate'
import { notifyError } from 'src/utils/notify'
import PostCard from 'components/PostCard.vue'
import CommentComposer from 'components/CommentComposer.vue'
import UserAvatar from 'components/UserAvatar.vue'
import EmptyState from 'components/EmptyState.vue'

const route = useRoute()
const router = useRouter()
const feed = useFeedStore()
const post = usePostStore()
const commenting = ref(false)

function loadFromCache() {
  const id = Number(route.params.id)
  const cached = feed.posts.find((p) => Number(p.id) === id)
  if (cached) post.setCurrent(cached)
  else post.reset()
}

onMounted(loadFromCache)
onBeforeUnmount(() => post.reset())

async function onComment(content) {
  commenting.value = true
  try {
    await post.addComment(route.params.id, content)
  } catch {
    notifyError('Falha ao comentar')
  } finally {
    commenting.value = false
  }
}

function goProfile(id) {
  if (id) router.push({ name: 'Profile', params: { id } })
}
</script>

<style lang="scss" scoped>
.post-wrap {
  max-width: 640px;
}
</style>
