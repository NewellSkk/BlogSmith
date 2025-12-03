<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user()
    {
        $user = UserResource::make(Auth::user());
        return response()->json([
            'user' => $user
        ]);
    }
}
