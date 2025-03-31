<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->only('student_number', 'password');

        // Add debug logging
        Log::debug('Attempting login with: ' . $request->student_number);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::debug('Login successful for: ' . $user->name . ' (Role: ' . $user->user_role . ')');

            if ($user->user_role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->user_role === 'faculty') {
                // Changed from admin.faculty.index to faculty.dashboard
                return redirect()->route('faculty.dashboard');
            } elseif ($user->user_role === 'client') {
                return redirect()->route('client.dashboard');
            }
        } else {
            Log::debug('Login failed for: ' . $request->student_number);
        }

        return back()->withErrors([
            'student_number' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('welcome');
    }
}
