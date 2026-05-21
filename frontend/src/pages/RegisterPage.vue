<template>
  <q-form @submit.prevent="onSubmit" class="q-gutter-md" autocomplete="on">
    <q-input
      v-model="form.name"
      :label="$t('auth.name')"
      dark
      filled
      autocomplete="name"
      :error="!!fieldErrors.name"
      :error-message="fieldErrors.name"
      :rules="[(v) => (v && v.length >= 3) || 'minimo 3 caracteres']"
    />
    <q-input
      v-model="form.email"
      type="email"
      :label="$t('auth.email')"
      dark
      filled
      autocomplete="email"
      :error="!!fieldErrors.email"
      :error-message="fieldErrors.email"
      :rules="[(v) => /.+@.+\..+/.test(v) || 'Email invalido']"
    />
    <q-input
      v-model="form.password"
      type="password"
      :label="$t('auth.password')"
      dark
      filled
      autocomplete="new-password"
      :error="!!fieldErrors.password"
      :error-message="fieldErrors.password"
      :rules="[(v) => v.length >= 6 || 'minimo 6 caracteres']"
    />
    <q-input
      v-model="form.password_confirmation"
      type="password"
      :label="$t('auth.passwordConfirmation')"
      dark
      filled
      autocomplete="new-password"
      :rules="[(v) => v === form.password || 'senhas nao conferem']"
    />

    <q-btn
      type="submit"
      color="primary"
      class="full-width"
      :loading="auth.loading"
      :label="$t('auth.register')"
      unelevated
    />

    <div class="text-center text-muted q-mt-md">
      {{ $t('auth.haveAccount') }}
      <router-link :to="{ name: 'Login' }" class="text-primary">{{ $t('auth.login') }}</router-link>
    </div>
  </q-form>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { notifyError } from 'src/utils/notify'

const router = useRouter()
const auth = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const fieldErrors = ref({})

async function onSubmit() {
  fieldErrors.value = {}
  try {
    await auth.register(form.value)
    router.push({ name: 'Feed' })
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      const mapped = {}
      Object.keys(errors).forEach((k) => {
        mapped[k] = errors[k][0]
      })
      fieldErrors.value = mapped
    } else {
      notifyError(e.response?.data?.message || 'Falha ao criar conta')
    }
  }
}
</script>
