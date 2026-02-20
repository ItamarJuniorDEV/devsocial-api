<?php

namespace App\Listeners;

use App\Events\PostCommented;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Events\UserFollowed;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    public function handle(object $event): void
    {
        $context = match (true) {
            $event instanceof UserRegistered => [
                'user_id' => $event->user->id,
                'email'   => $event->user->email,
            ],
            $event instanceof PostCreated => [
                'user_id' => $event->post->user_id,
                'post_id' => $event->post->id,
                'type'    => $event->post->type,
            ],
            $event instanceof PostCommented => [
                'user_id' => $event->comment->user_id,
                'post_id' => $event->comment->post_id,
            ],
            $event instanceof PostLiked => [
                'user_id' => $event->userId,
                'post_id' => $event->postId,
                'liked'   => $event->liked,
            ],
            $event instanceof UserFollowed => [
                'from'      => $event->fromUserId,
                'to'        => $event->toUserId,
                'following' => $event->following,
            ],
            default => [],
        };

        Log::info(class_basename($event), $context);
    }
}
