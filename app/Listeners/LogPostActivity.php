<?php

namespace App\Listeners;

use App\Events\PostCommented;
use App\Events\PostCreated;
use App\Events\PostLiked;
use Illuminate\Support\Facades\Log;

class LogPostActivity
{
    public function handle(PostCreated|PostCommented|PostLiked $event): void
    {
        if ($event instanceof PostCreated) {
            Log::info('post.created', ['user_id' => $event->post->user_id, 'post_id' => $event->post->id]);
            return;
        }

        if ($event instanceof PostCommented) {
            Log::info('post.commented', ['user_id' => $event->comment->user_id, 'post_id' => $event->comment->post_id]);
            return;
        }

        Log::info('post.liked', ['user_id' => $event->userId, 'post_id' => $event->postId, 'liked' => $event->liked]);
    }
}
