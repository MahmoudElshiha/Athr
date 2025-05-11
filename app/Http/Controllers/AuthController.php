<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return api_success(new UserResource($user));
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return api_error("The provided credentials are incorrect.", 401);
        }

        return api_success(
            [
                'token' => $user->createToken('API Token')->plainTextToken,
            ]
        );
    }

    public function logout(Request $request)
    {

        if (!auth()->check()) {
            return api_error('No authenticated user.', 401);
        }

        auth()->user()->currentAccessToken()->delete();
        return api_success();
    }
}
