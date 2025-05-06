<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function indexUsers()
    {
        $users = User::all();
        return api_success($users, 'Users retrieved successfully');
    }

    public function deleteUser($user_id)
    {
        $user  = User::find($user_id);

        // Check if the user exists
        if (!$user) {
            return api_error('User not found', 404);
        }

        // Only allow if current user is admin or owns the account
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            return api_error('Unauthorized', 403);
        }

        // Delete the user
        $user->delete();

        return api_success(null, 'User deleted successfully');
    }
}
