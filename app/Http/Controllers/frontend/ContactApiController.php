<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactApiController extends Controller
{
    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get admin email from settings or fallback to config
            $settings = Setting::first();
            $adminEmail = $settings->email ?? config('mail.from.address');

            if (!$adminEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin email not configured.'
                ], 500);
            }

            // Send email
            try {
                Mail::to($adminEmail)->send(new ContactMail($request->all()));
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email. Please try again later.',
                    'error' => $th->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
