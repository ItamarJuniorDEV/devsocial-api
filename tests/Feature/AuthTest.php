<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Testes de integração para autenticação.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login(): void
    {
        // Testa registro
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Usuário',
            'email' => 'usuario@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'sucesso',
            'token',
            'user' => ['id', 'name', 'email'],
        ]);

        // Testa login
        $login = $this->postJson('/api/auth/login', [
            'email' => 'usuario@example.com',
            'password' => 'password',
        ]);

        $login->assertStatus(200);
        $login->assertJsonStructure([
            'sucesso',
            'token',
            'user' => ['id', 'name', 'email'],
        ]);
    }
}