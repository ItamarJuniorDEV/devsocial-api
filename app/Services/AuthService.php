<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Timebox;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create($data);
        $token = $user->createToken('api')->plainTextToken;

        UserRegistered::dispatch($user);

        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials): array
    {
        $user = (new Timebox)->call(
            function (Timebox $timebox) use ($credentials) {
                return User::where('email', $credentials['email'])->first();
            },
            200 * 1000
        );

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Credenciais invalidas.');
        }

        $token = $user->createToken('api')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
