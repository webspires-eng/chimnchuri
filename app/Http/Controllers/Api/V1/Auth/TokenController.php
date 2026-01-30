<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Auth\TokenService;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{

    // REFRESH TOKEN 
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'success' => false,
                'message' => 'Refresh token missing'
            ], 401);
        }


        $refreshModel = PersonalAccessToken::findToken($refreshToken);

        if (!$refreshModel || !$refreshModel->can('refresh')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid refresh token'
            ], 401);
        }

        $user = $refreshModel->tokenable;

        $refreshModel->delete();

        // Create new tokens
        // $newAccessToken = $user->createToken(
        //     'access_token',
        //     ['access'],
        //     now()->addMinutes(2)
        // )->plainTextToken;

        // $newRefreshToken = $user->createToken(
        //     'refresh_token',
        //     ['refresh'],
        //     now()->addMinutes(5)
        // )->plainTextToken;

        $tokens = app(TokenService::class)->refreshTokens($refreshModel);

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully'
        ])->cookie("access_token", $tokens["access_token"], 15, "/", null, true, true, false, "none")
            ->cookie("refresh_token", $tokens["refresh_token"], 60 * 24 * 7, "/", null, true, true, false, "none");

    }
}
