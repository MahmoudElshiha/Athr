<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{

    public function show($user_id)
    {

        $user = User::find($user_id);

        if (!$user) {
            return api_error('User not found', 404);
        }

        // Verify if the user is the same or an admin
        if (!auth()->user()->is_admin && auth()->id() !== $user->id) {
            return api_error('Unauthorized', 403);
        }

        return api_success(new UserResource($user));
    }


    public function update(UserUpdateRequest $request, $user_id)
    {
        $validated = $request->validated();

        $user  = User::find($user_id);

        // Check if the user exists
        if (!$user) {
            return api_error('User not found', 404);
        }

        // Verify if the user is the same or an admin
        if (!auth()->user()->is_admin && auth()->id() !== $user->id) {
            return api_error('Unauthorized', 403);
        }

        $user->update($validated);

        return api_success(new UserResource($user));
    }

    public function destroy()
    {
        // user can only delete their accounts 
        $user  = User::find(auth()->id());

        // Check if the user exists
        if (!$user) {
            return api_error('User not found', 404);
        }

        $user->delete();

        return api_success();
    }
}
