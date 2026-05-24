<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'is_active' => true])) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            if ($user->hasRole('admin')) return response()->json(['success' => true, 'redirect' => '/admin/dashboard']);
            if ($user->hasRole('cashier')) return response()->json(['success' => true, 'redirect' => '/cashier/dashboard']);
            if ($user->hasRole('kitchen')) return response()->json(['success' => true, 'redirect' => '/kitchen/dashboard']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau Password salah, atau akun nonaktif.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}