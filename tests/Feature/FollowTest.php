<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_another_user(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/{$target->id}/follow");

        $response->assertStatus(200)
            ->assertJsonPath('data.following', true);
    }

    public function test_user_can_unfollow_a_followed_user(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson("/api/users/{$target->id}/follow");
        $response = $this->postJson("/api/users/{$target->id}/follow");

        $response->assertStatus(200)
            ->assertJsonPath('data.following', false);
    }

    public function test_unauthenticated_user_cannot_follow(): void
    {
        $target = User::factory()->create();

        $this->postJson("/api/users/{$target->id}/follow")->assertStatus(401);
    }
}
