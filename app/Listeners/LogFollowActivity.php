<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use Illuminate\Support\Facades\Log;

class LogFollowActivity
{
    public function handle(UserFollowed $event): void
    {
        Log::info('user.followed', [
            'from' => $event->fromUserId,
            'to' => $event->toUserId,
            'following' => $event->following,
        ]);
    }
}
