<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Repositories\Contracts\FollowRepository;
use App\Repositories\Contracts\PostRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentFollowRepository;
use App\Repositories\Eloquent\EloquentPostRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(PostRepository::class, EloquentPostRepository::class);
        $this->app->bind(FollowRepository::class, EloquentFollowRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
    }
}
