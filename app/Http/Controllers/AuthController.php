<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('booking-api', ['*'], now()->addHours(4))->plainTextToken; // change to 2

        return response()->json([
            'message' => 'Logged In',
            'api-token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged Out',
        ]);
    }
}
