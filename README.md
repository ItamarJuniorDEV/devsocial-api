# DevSocial

Monorepo da rede social DevSocial.

## Estrutura

- `backend/` -- API REST em Laravel 10 + Sanctum + MySQL. Detalhes em [backend/README.md](backend/README.md).
- `frontend/` -- SPA em Vue 3 + Quasar 2. Detalhes em [frontend/README.md](frontend/README.md).

## Pre-requisitos

- PHP 8.2+ e Composer (para o backend)
- Node 22.12+ e npm 10+ (para o frontend)
- MySQL 8 (ou Docker)
- Git

## Como rodar backend

```
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API sobe em http://localhost:8000.

## Como rodar frontend

```
cd frontend
npm install
npm run dev
```

App sobe em http://localhost:9000. O proxy de `/api` aponta para http://localhost:8000.