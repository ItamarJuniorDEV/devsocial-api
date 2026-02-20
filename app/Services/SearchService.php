<?php

namespace App\Services;

use App\Repositories\Contracts\PostRepository;
use App\Repositories\Contracts\UserRepository;

class SearchService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly PostRepository $posts,
    ) {}

    public function search(string $query, int $perPage): array
    {
        return [
            'users' => $this->users->search($query, $perPage),
            'posts' => $this->posts->search($query, $perPage),
        ];
    }
}
