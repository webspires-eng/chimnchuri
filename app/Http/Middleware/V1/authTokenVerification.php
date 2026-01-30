<?php

namespace App\Http\Middleware\V1;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class authTokenVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {



        $access_token = $request->cookie('access_token');
        $refresh_token = $request->cookie("refresh_token");

        // CHECK REQUEST HAS ACCESS TOKEN 
        if ($access_token) {
            $request->headers->set('Authorization', 'Bearer ' . $access_token);
            return $next($request);
        }

        // CHECK REQUEST HAS REFRESH TOKEN 
        // if ($refresh_token) {
        //     $refreshModel = PersonalAccessToken::findToken($refresh_token);

        //     // CHECK REFRESH TOKEN AND TOKEN EXPIRY
        //     if ($refreshModel && Carbon::now()->lt($refreshModel->expires_at)) {

        //         $user = $refreshModel->tokenable;

        //         // DELETE USER TOKENS
        //         $refreshModel->delete();

        //         // GENERATE NEW TOKENS
        //         $newAccessToken = $user->createToken("access_token", ["*"], now()->addMinutes(2))->plainTextToken;
        //         $newRefreshToken = $user->createToken("refresh_token", ["*"], now()->addMinutes(5))->plainTextToken;

        //         // SET IN HEADER
        //         $request->headers->set('Authorization', 'Bearer ' . $newAccessToken);

        //         $resposnse = $next($request);

        //         // SET IN RESPONSE
        //         return $resposnse
        //             ->cookie("access_token", $newAccessToken, 2, "/", null, true, true, false, "none")
        //             ->cookie("refresh_token", $newRefreshToken, 5, "/", null, true, true, false, "none");

        //     }
        // }

        return $next($request);
    }
}
