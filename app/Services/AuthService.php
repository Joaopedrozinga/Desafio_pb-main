<?php

namespace App\Services;

use App\DTOs\LoginData;
use App\DTOs\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    /**
     * Register a new user and issue an API token.
     *
     * @return array{user: User, token: string}
     */
    public function register(RegisterData $data): array
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    /**
     * Authenticate a user by credentials and issue an API token.
     *
     * @return array{user: User, token: string}|null
     */
    public function login(LoginData $data): ?array
    {
        $user = User::where('email', $data->email)->first();

        if ($user === null || ! Hash::check($data->password, $user->password)) {
            return null;
        }

        return [
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    /**
     * Revoke the current API token of the user.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
