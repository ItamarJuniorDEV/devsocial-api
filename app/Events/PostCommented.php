<?php

namespace App\Events;

use App\Models\PostComment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCommented
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly PostComment $comment,
    ) {}
}
