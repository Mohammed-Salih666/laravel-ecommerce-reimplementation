<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required', 
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
            'is_admin' => false,
            'is_active' => true
        ]);

        $token = $user->createToken('register-token')->plainTextToken; 

        return response()->json([
            'token' => $token
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required', 
            'password' => 'required',
        ]); 

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
         return response()->json([
            'message' => 'Unauthorized'
         ]);   
        }

        $user = User::where('email', $request->email)->first(); 
        $token = $user->createToken('access_token')->plainTextToken; 

        return response()->json([
            'user' => $user, 
            'message' => 'authenticated',
            'token' => $token
        ]);

    }

    public function logout(Request $request){
        // auth()->user()->tokens()->delete();
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'logged out'
        ]);
    }
}
