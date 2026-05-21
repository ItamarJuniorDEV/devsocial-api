<template>
  <q-card flat class="card-surface q-pa-md">
    <div class="row items-center q-gutter-sm">
      <UserAvatar :user="post.user" size="40px" class="cursor-pointer" @click="$emit('profile', post.user?.id)" />
      <div class="col">
        <div class="cursor-pointer" @click="$emit('profile', post.user?.id)">
          <b>{{ post.user?.name }}</b>
        </div>
        <div class="text-muted text-caption">{{ formatRelative(post.created_at) }}</div>
      </div>
    </div>

    <div v-if="post.type === 'text'" class="q-mt-sm" style="white-space: pre-wrap">{{ post.body }}</div>
    <div v-else-if="post.type === 'photo' && post.photo_url" class="q-mt-sm">
      <img :src="post.photo_url" :alt="'post ' + post.id" style="max-width: 100%; border-radius: 8px" />
    </div>

    <div class="row q-mt-md q-gutter-md">
      <q-btn
        flat
        dense
        no-caps
        :icon="post.liked ? 'favorite' : 'favorite_border'"
        :color="post.liked ? 'accent' : ''"
        :label="String(post.likes_count || 0)"
        @click="$emit('like')"
      />
      <q-btn
        flat
        dense
        no-caps
        icon="chat_bubble_outline"
        :label="String(post.comments_count || 0)"
        @click="$emit('open')"
      />
      <q-btn
        v-if="!expanded"
        flat
        dense
        no-caps
        icon="open_in_new"
        @click="$emit('open')"
      />
    </div>
  </q-card>
</template>

<script setup>
import UserAvatar from 'components/UserAvatar.vue'
import { formatRelative } from 'src/utils/formatDate'

defineProps({
  post: { type: Object, required: true },
  expanded: { type: Boolean, default: false }
})

defineEmits(['like', 'open', 'profile'])
</script>
