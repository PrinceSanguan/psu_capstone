<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->only('student_number', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->user_role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->user_role === 'client') {
                return redirect()->route('client.dashboard');
            }
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
