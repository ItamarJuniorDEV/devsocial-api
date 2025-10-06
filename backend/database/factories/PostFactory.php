<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = \App\Models\Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'text',
            'body' => $this->faker->paragraph(),
        ];
    }
}
