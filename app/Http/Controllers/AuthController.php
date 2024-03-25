<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    function login(Request $request){
        $role = $request->role;
        $username = $request->username;
        $password = $request->password;
        // Cek apakah pengguna dengan username yang diberikan ada dalam tabel
        $user = User::where('username', $username)->where('role',$role)->first();
        if($user) {
            if(password_verify($password, $user->password)) {
                return response()->json(['status_auth' => true,'message'=>'Login Successfuly'], 200);
            } else {
                // Password tidak cocok
                return response()->json(['status_auth' => false,'message'=>'Wrong Password'], 200);
            }
        } else {
            // Pengguna tidak ditemukan
            return response()->json(['status_auth' => false,'message'=>'User Not Found!'],404);
        }
    }
    
}
