<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ]);
        }

        $accessToken = auth()->user()->createToken('accessToken')->accessToken;

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $accessToken
        ]);
    }
}
