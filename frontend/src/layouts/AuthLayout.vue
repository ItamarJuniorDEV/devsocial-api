<template>
  <q-layout view="lHh Lpr lff" class="bg-dark-page">
    <q-page-container>
      <q-page class="row items-center justify-center bg-dark-page">
        <q-card flat class="auth-card q-pa-lg">
          <div class="text-center q-mb-md">
            <span class="text-h5 font-mono text-primary">DevSocial</span>
          </div>
          <router-view />
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
//
</script>

<style lang="scss" scoped>
.auth-card {
  background: #15171c;
  border-radius: 8px;
  width: 100%;
  max-width: 400px;
}
</style>
