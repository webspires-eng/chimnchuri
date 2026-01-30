<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Pest\Support\Str;

class PasswordController extends Controller
{
    // FORGET PASSWORD 
    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "email" => "required|email|string|exists:users,email"
        ]);

        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Request validation failed.",
                "errors" => $validate->errors(),
            ]);
        }

        // Generate a random token
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        $user = User::where('email', $request->email)->first();
        Mail::to($request->email)->send(new ForgetPassword($token, $request->email, $user));

        return response()->json([
            'success' => true,
            'message' => 'A password reset email has been sent with instructions to reset your password.'
        ]);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "token" => "required",
            "email" => "required|email|exists:users,email",
            "password" => "required|min:5"
        ]);

        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Request Validation failed.",
                "errors" => $validate->errors(),
            ], 422);
        }

        try {
            $checkToken = DB::table("password_reset_tokens")->where("email", $request->email)->first();

            if (!$checkToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid token or email!'
                ], 404);
            }

            // CHECK TOKEN VALIDTION AND EXPIRY
            if (!Hash::check($request->token, $checkToken->token) || Carbon::parse($checkToken->created_at)->addMinutes(15)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is invalid or expired!',
                ], 400);
            }



            $user = User::where('email', $request->email)->first();

            $user->password = $request->password;
            $user->save();

            DB::table("password_reset_tokens")->where("email", $request->email)->delete();



            return response()->json([
                'success' => true,
                'message' => 'Your password has been reset successfully! You can now login with your new password.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
                "errors" => $e,
            ], 500);
        }


    }
}
