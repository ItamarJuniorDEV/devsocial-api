<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory para criação de usuários em testes.
 */
class UserFactory extends Factory
{
    /**
     * O nome do modelo correspondente.
     *
     * @var string
     */
    protected $model = \App\Models\User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password', // será hash automaticamente pelo mutator
        ];
    }
}