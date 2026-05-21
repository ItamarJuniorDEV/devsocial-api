<template>
  <div>
    <q-input
      v-model="text"
      type="textarea"
      autogrow
      dark
      borderless
      :placeholder="$t('post.commentPlaceholder')"
      maxlength="500"
    />
    <div class="row justify-end q-mt-sm">
      <q-btn
        color="primary"
        :disable="!text.trim() || loading"
        :loading="loading"
        :label="$t('post.comment')"
        unelevated
        @click="submit"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  loading: { type: Boolean, default: false }
})
const emit = defineEmits(['submit'])

const text = ref('')

function submit() {
  const value = text.value.trim()
  if (!value) return
  emit('submit', value)
  text.value = ''
}
</script>
