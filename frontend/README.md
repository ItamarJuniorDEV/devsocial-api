# DevSocial Frontend

SPA em Vue 3 + Quasar 2 que consome a API Laravel em ../backend.

## Stack

- vue 3.5 (Composition API, `<script setup>`)
- quasar 2.18
- @quasar/app-vite 2.4
- pinia 3
- vue-router 4
- axios 1.7
- vue-i18n 11
- vite (via quasar/app-vite)
- eslint 9 + prettier 3

## Pre-requisitos

- Node 22.12+
- npm 10+
- Backend rodando em http://localhost:8000 (ver ../backend/README.md)

## Como rodar

```
npm install
npm run dev
```

App em http://localhost:9000. O proxy de `/api` aponta para http://localhost:8000.

## Como buildar

```
npm run build
```

Saida em `dist/spa`.

## Estrutura de pastas

```
src/
  boot/         # axios, i18n
  components/   # PostCard, PostComposer, UserAvatar, etc
  css/          # paleta + estilos globais
  i18n/pt-BR/   # mensagens
  layouts/      # MainLayout, AuthLayout
  pages/        # Login, Register, Feed, Profile, ProfileEdit, Post, Search, NotFound
  router/       # rotas e guard de auth
  stores/       # auth, feed, profile, post, search
  utils/        # notify, formatDate
```

## Autenticacao

Login devolve um Bearer token do Sanctum que fica gravado em `localStorage` sob a chave `devsocial_token`. O interceptor do axios em `boot/axios.js` injeta esse token em todo request. Resposta 401 limpa o token e o store de auth manda o usuario pra tela de login.

## Variaveis de ambiente

Nenhuma. O endereco da API e fixo no proxy do `quasar.config.js` (`/api` -> `http://localhost:8000`).