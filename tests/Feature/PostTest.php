<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Testes de integração para posts.
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/feed', [
                'type' => 'text',
                'body' => 'Hello world',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'sucesso',
            'post' => ['id', 'type', 'body'],
        ]);
    }
}