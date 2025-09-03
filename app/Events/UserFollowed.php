<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserFollowed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $fromUserId,
        public readonly int $toUserId,
        public readonly bool $following,
    ) {}
}
