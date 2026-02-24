<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $settings = \App\Models\Setting::first();
        return view('admin.auth.login', compact('settings'));
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Add role check to credentials
        // $credentials['role'] = 'admin';


        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            if (in_array($user->role, ["admin", "manager", "staff"])) {


                if ($user->role !== "admin" && empty($user->branch_ids)) {
                    Auth::guard('admin')->logout();
                    session()->forget('branch_ids');
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.login')->with('error', 'You are not authorized to login as admin');
                }
            } else {
                Auth::guard('admin')->logout();
                session()->forget('branch_ids');
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('error', 'You are not authorized to login as admin');
            }

            session(['branch_ids' => $user->branch_ids]);


            return redirect()->intended(route('admin.dashboard'));
        }

        // Failed authentication
        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records or you are not an admin.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->forget('branch_ids');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
