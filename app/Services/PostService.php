<?php

namespace App\Services;

use App\DTO\Post\CreateCommentDTO;
use App\DTO\Post\CreatePostDTO;
use App\Events\PostCommented;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Models\Post;
use App\Models\PostComment;
use App\Repositories\Contracts\PostRepository;

class PostService
{
    public function __construct(
        private readonly PostRepository $posts,
    ) {}

    public function create(int $authUserId, CreatePostDTO $dto): Post
    {
        $body = $dto->body;

        if ($dto->type === 'photo' && $dto->photo) {
            $body = $dto->photo->store('posts', 'public');
        }

        $post = $this->posts->create([
            'user_id' => $authUserId,
            'type' => $dto->type,
            'body' => (string) $body,
        ]);

        PostCreated::dispatch($post);

        return $post;
    }

    public function toggleLike(int $postId, int $authUserId): bool
    {
        $liked = $this->posts->toggleLike($postId, $authUserId);

        PostLiked::dispatch($postId, $authUserId, $liked);

        return $liked;
    }

    public function comment(int $postId, int $authUserId, CreateCommentDTO $dto): PostComment
    {
        $comment = $this->posts->createComment($postId, $authUserId, $dto->body);

        PostCommented::dispatch($comment);

        return $comment;
    }
}
