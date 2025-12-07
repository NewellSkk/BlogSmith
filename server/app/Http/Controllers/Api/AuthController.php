<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['invalid credentials'],
            ]);
        }
        //Delete existing tokens-single device login
        $user->tokens()->delete();
        
        //New token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successful',
            'user' => $user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
      
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
