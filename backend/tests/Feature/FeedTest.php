<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_feed(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/feed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    public function test_feed_includes_own_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['user_id' => $user->id, 'type' => 'text', 'body' => 'Meu post']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/feed');

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 1);
    }

    public function test_unauthenticated_user_cannot_see_feed(): void
    {
        $this->getJson('/api/feed')->assertStatus(401);
    }
}
