# DevSocial Backend

API REST em Laravel 10 com autenticacao Sanctum e MySQL 8.

## Stack

- PHP 8.2+
- Laravel 10
- Sanctum (bearer tokens)
- MySQL 8
- Docker (opcional)

## Pre-requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 8 rodando local OU Docker

## Como rodar (sem Docker)

```
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API em http://localhost:8000.

## Como rodar (com Docker)

```
docker compose up -d
```

Sobe app + nginx + mysql.

## Endpoints principais

- POST   /api/register
- POST   /api/login
- POST   /api/logout
- GET    /api/me
- GET    /api/feed
- POST   /api/posts
- GET    /api/posts/{id}
- POST   /api/posts/{id}/like
- GET    /api/posts/{id}/comments
- POST   /api/posts/{id}/comments
- GET    /api/users/{username}
- POST   /api/users/{username}/follow
- GET    /api/users/{username}/followers
- GET    /api/users/{username}/following
- GET    /api/users/{username}/posts
- PUT    /api/me
- GET    /api/search?q=&type=users|posts

Doc Swagger em /api/documentation.

## Testes

```
php artisan test
```