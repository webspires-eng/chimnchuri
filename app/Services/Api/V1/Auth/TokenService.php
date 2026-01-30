<?php

namespace App\Services\Api\V1\Auth;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class TokenService
{
    public function createTokens(User $user): array
    {
        return [
            'access_token' => $user->createToken('access_token', ['access'], now()->addMinutes(15))->plainTextToken,
            'refresh_token' => $user->createToken('refresh_token', ['refresh'], now()->addDays(7))->plainTextToken,
        ];
    }

    public function refreshTokens(PersonalAccessToken $refreshToken): array
    {
        $user = $refreshToken->tokenable;
        $refreshToken->delete();

        return $this->createTokens($user);
    }
}
