<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $users,
    ) {}

    public function updateProfile(int $userId, array $data): User
    {
        $user = $this->users->find($userId);

        return $this->users->update($user, $data);
    }

    public function uploadAvatar(int $userId, $file): string
    {
        $user = $this->users->find($userId);
        $path = $file->store('avatars', 'public');

        $this->users->update($user, ['avatar' => $path]);

        return $path;
    }

    public function uploadCover(int $userId, $file): string
    {
        $user = $this->users->find($userId);
        $path = $file->store('covers', 'public');

        $this->users->update($user, ['cover' => $path]);

        return $path;
    }
}
