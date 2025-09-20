<?php

namespace App\Services;

use App\Models\UserRelation;

class FollowService
{
    public function toggle(int $authUserId, int $userId): bool
    {
        $relation = UserRelation::where('user_from', $authUserId)
            ->where('user_to', $userId)
            ->first();

        if ($relation) {
            $relation->delete();

            return false;
        }

        UserRelation::create([
            'user_from' => $authUserId,
            'user_to' => $userId,
        ]);

        return true;
    }
}
