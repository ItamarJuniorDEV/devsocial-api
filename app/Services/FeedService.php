<?php

namespace App\Services;

use App\Repositories\Contracts\FollowRepository;
use App\Repositories\Contracts\PostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FeedService
{
    public function __construct(
        private readonly PostRepository $posts,
        private readonly FollowRepository $follows,
    ) {}

    public function feed(int $authUserId, int $perPage): LengthAwarePaginator
    {
        $followed = $this->follows->getFollowedUserIds($authUserId);
        $paginator = $this->posts->feedForUser($authUserId, $followed, $perPage);

        $postIds = [];
        foreach ($paginator->items() as $post) {
            $postIds[] = (int) $post->id;
        }

        $likedMap = $this->posts->isLikedMap($postIds, $authUserId);

        foreach ($paginator->items() as $post) {
            $post->setAttribute('mine', (int) $post->user_id === $authUserId);
            $post->setAttribute('liked', isset($likedMap[(int) $post->id]));
        }

        return $paginator;
    }
}
