<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_text_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'type' => 'text',
            'body' => 'Hello DevSocial!',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'type', 'body', 'user', 'likes_count', 'comments_count'],
            ]);
    }

    public function test_post_requires_body_when_type_is_text(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', ['type' => 'text']);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_create_post(): void
    {
        $response = $this->postJson('/api/posts', [
            'type' => 'text',
            'body' => 'Hello DevSocial!',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_like_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
            ->assertJsonPath('data.liked', true);
    }

    public function test_user_can_unlike_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson("/api/posts/{$post->id}/like");
        $response = $this->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
            ->assertJsonPath('data.liked', false);
    }

    public function test_user_can_comment_on_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'body' => 'Great post!',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'body', 'created_at'],
            ]);
    }
}
