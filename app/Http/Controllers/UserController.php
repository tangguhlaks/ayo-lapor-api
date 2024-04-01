<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUserByUsername(Request $request)
    {
        $username = $request->username;
        $user = User::where('username', $username)->first();
        if ($user) {
            return response()->json(['status' => true, 'user' => $user], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
    }
}
