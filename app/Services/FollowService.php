<?php

namespace App\Services;

use App\Events\UserFollowed;
use App\Repositories\Contracts\FollowRepository;

class FollowService
{
    public function __construct(
        private readonly FollowRepository $follows,
    ) {}

    public function toggle(int $authUserId, int $userId): bool
    {
        $following = $this->follows->toggle($authUserId, $userId);

        UserFollowed::dispatch($authUserId, $userId, $following);

        return $following;
    }
}
