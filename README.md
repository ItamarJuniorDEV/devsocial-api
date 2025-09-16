# DevSocial API

API REST que construí pra servir uma rede social voltada pra desenvolvedores. O escopo é o de sempre numa rede social — usuário cria conta, publica posts, segue gente, curte, comenta, recebe um feed personalizado e consegue buscar por outros usuários ou conteúdo. Fiz em Laravel 10 com Sanctum e rodando em Docker pra ser fácil subir em qualquer máquina.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)
![CI](https://github.com/ItamarJuniorDEV/api-devsocial-php/actions/workflows/ci.yml/badge.svg?branch=master)

## Stack

- PHP 8.2
- Laravel 10
- Laravel Sanctum (autenticação por token)
- MySQL 8.0
- Docker (PHP-FPM, Nginx, MySQL)
- PHPUnit (testes de feature)
- l5-swagger (documentação OpenAPI)

## Funcionalidades

- Registro e login com emissão de token via Sanctum
- Perfil de usuário com upload de avatar e capa
- Posts de texto e imagem, com curtidas e comentários
- Feed paginado com posts próprios e dos usuários seguidos
- Seguir e deixar de seguir outros usuários (toggle)
- Busca por termo, retornando usuários e posts em uma chamada só
- Notificações de atividade via Events/Listeners (curtida, comentário, novo seguidor)

## Como rodar

A stack inteira sobe via Docker. Não precisa ter PHP nem MySQL instalados na máquina.

```bash
git clone https://github.com/ItamarJuniorDEV/api-devsocial-php.git
cd api-devsocial-php

cp .env.example .env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

Depois disso a API responde em `http://localhost:8000`.

## Decisões de arquitetura

- **Sanctum em vez de Passport**: a API é stateless e a autenticação só precisa de token simples por usuário. Passport traz OAuth completo, que aqui seria peso morto. Sanctum cobre o caso com bem menos cerimônia.
- **Events e Listeners pra notificar atividade**: curtir, comentar e seguir disparam eventos. Quem reage a isso (registrar atividade, futuramente mandar push/email) fica desacoplado do controller. Facilita adicionar canal novo sem mexer no fluxo principal.
- **Policies em vez de checagem inline**: a autorização de quem pode editar/apagar post fica isolada em `PostPolicy`. Controller só pergunta `$user->can(...)` e segue a vida. Centraliza a regra num lugar e fica trivial testar.
- **Docker desde o início**: como é projeto de portfólio, qualquer pessoa que abrir o repo precisa subir em um comando. Docker garante isso, e ainda evita o "na minha máquina funciona" entre PHP-FPM, Nginx e MySQL.
- **Form Requests pra validação**: cada endpoint tem seu Request dedicado. Controller chega já com payload validado e autorização resolvida, então sobra só a regra de negócio.

## Endpoints

| Recurso     | Método | Endpoint                       | Descrição                              |
|-------------|--------|--------------------------------|----------------------------------------|
| Auth        | POST   | `/api/auth/register`           | Registrar novo usuário                 |
| Auth        | POST   | `/api/auth/login`              | Autenticar e obter token               |
| Auth        | GET    | `/api/auth/me`                 | Dados do usuário autenticado           |
| Auth        | POST   | `/api/auth/logout`             | Encerrar sessão                        |
| Usuário     | GET    | `/api/user/me`                 | Perfil do usuário autenticado          |
| Usuário     | PUT    | `/api/user/profile`            | Atualizar dados do perfil              |
| Usuário     | POST   | `/api/user/avatar`             | Upload de avatar                       |
| Usuário     | POST   | `/api/user/cover`              | Upload de capa                         |
| Feed        | GET    | `/api/feed`                    | Listar feed paginado                   |
| Posts       | POST   | `/api/posts`                   | Criar post de texto ou imagem          |
| Posts       | POST   | `/api/posts/{id}/like`         | Curtir ou descurtir post               |
| Posts       | POST   | `/api/posts/{id}/comments`     | Comentar em um post                    |
| Seguidores  | POST   | `/api/users/{id}/follow`       | Seguir ou deixar de seguir usuário     |
| Busca       | GET    | `/api/search?q=`               | Buscar usuários e posts por termo      |

## Documentação da API

A documentação OpenAPI completa fica disponível em `http://localhost:8000/api/documentation` depois de subir os containers.

## Testes

```bash
docker compose exec app php artisan test
```

> Observação: as dependências `mockery/mockery` e `nunomaduro/collision` não estão no `composer.json` atual, então a suíte de feature ainda não roda end-to-end. Isso fica como dívida conhecida e está listado em "Melhorias futuras" abaixo.

## Melhorias futuras

- Incluir `mockery/mockery` e `nunomaduro/collision` em `require-dev` pra destravar a suíte de feature já existente.
- Mover notificações pra fila assíncrona (Redis + Horizon) quando o volume justificar.
- Adicionar paginação por cursor no feed em vez de offset, melhor pra grandes volumes.
- Cobrir os endpoints com testes de contrato a partir do spec OpenAPI.
- Rate limit por usuário autenticado em vez de só por IP nas rotas de escrita.

## Licença

MIT. Detalhes em [LICENSE](LICENSE).

## Autor

Itamar Alves Ferreira Junior
