<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;

class PostService
{
    public function create(int $authUserId, array $data): Post
    {
        $body = $data['body'] ?? null;

        if (($data['type'] ?? null) === 'photo' && isset($data['photo'])) {
            $body = $data['photo']->store('posts', 'public');
        }

        return Post::create([
            'user_id' => $authUserId,
            'type' => $data['type'],
            'body' => (string) $body,
        ]);
    }

    public function toggleLike(int $postId, int $authUserId): bool
    {
        $exists = PostLike::where('post_id', $postId)
            ->where('user_id', $authUserId)
            ->exists();

        if ($exists) {
            PostLike::where('post_id', $postId)
                ->where('user_id', $authUserId)
                ->delete();

            return false;
        }

        PostLike::create(['post_id' => $postId, 'user_id' => $authUserId]);

        return true;
    }

    public function comment(int $postId, int $authUserId, array $data): PostComment
    {
        return PostComment::create([
            'post_id' => $postId,
            'user_id' => $authUserId,
            'body' => $data['body'],
        ]);
    }
}
