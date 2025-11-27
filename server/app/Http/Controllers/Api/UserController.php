<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user()
    {
        $user = Auth::user();
        return response()->json([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]
        ]);
    }
}
