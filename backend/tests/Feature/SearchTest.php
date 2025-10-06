<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_users_and_posts(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/search?q=test');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['users', 'posts'],
                'meta' => ['users', 'posts'],
            ]);
    }

    public function test_search_requires_query_param(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/search');

        $response->assertStatus(422);
    }

    public function test_search_finds_matching_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['type' => 'text', 'body' => 'Laravel é incrível']);
        Post::factory()->create(['type' => 'text', 'body' => 'Conteúdo sem relação']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/search?q=Laravel');

        $response->assertStatus(200)
            ->assertJsonPath('meta.posts.total', 1);
    }

    public function test_unauthenticated_user_cannot_search(): void
    {
        $this->getJson('/api/search?q=test')->assertStatus(401);
    }
}
