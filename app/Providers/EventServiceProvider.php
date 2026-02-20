<?php

namespace App\Providers;

use App\Events\PostCommented;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Events\UserFollowed;
use App\Events\UserRegistered;
use App\Listeners\LogFollowActivity;
use App\Listeners\LogPostActivity;
use App\Listeners\LogUserActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            LogUserActivity::class,
        ],
        PostCreated::class => [
            LogPostActivity::class,
        ],
        PostCommented::class => [
            LogPostActivity::class,
        ],
        PostLiked::class => [
            LogPostActivity::class,
        ],
        UserFollowed::class => [
            LogFollowActivity::class,
        ],
    ];

    public function boot(): void
    {
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
