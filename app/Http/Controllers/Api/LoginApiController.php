<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginApiController extends Controller
{
    public function login(LoginRequest $request)
    {
        dd('sadas');
        if ($request->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $request->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function me(Request $request)
    {
        // Return the authenticated user
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        // Invalidate the token
        JWTAuth::invalidate($request->token);
        return response()->json(['message' => 'Successfully logged out']);
    }
}
