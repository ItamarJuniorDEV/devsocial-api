<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    public function handle(UserRegistered $event): void
    {
        Log::info('user.registered', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
        ]);
    }
}
