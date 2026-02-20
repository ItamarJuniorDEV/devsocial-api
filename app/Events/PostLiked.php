<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLiked
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $postId,
        public readonly int $userId,
        public readonly bool $liked,
    ) {}
}
