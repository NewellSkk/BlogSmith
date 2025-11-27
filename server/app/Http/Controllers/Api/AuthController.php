<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'

        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        Auth::login($user);
        $request->session()->regenerate();
        return response()->json([
            'message' => 'User registered successfully',
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]
        ],201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'invalid credentials'
            ], 401);
        }
        $user = Auth::user();
        return response()->json([
            'message' => 'Login Successful',
            'user' => $user
        ],201);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
