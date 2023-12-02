<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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

    //Request Signature: 
    /*
    User 
    Role Name
    Permission Name 
    */
    public function authorizeTo(Request $request) 
    {
  
        $user = User::find($request->user);

        $role = $this->getOrCreateRole($request->role);  
        // $permission = $this->getOrCreatePermission($request->permission); 

        $user->assignRole($role['name']); 
        //or: 
        // $user->givePermissionTo($permission); 

        return response()->json([
            'user' => $user, 
            'role' => $role
        ]);
    }

    private function getOrCreateRole($roleName) {
        $role = Role::where('name', $roleName)->first(); 
        if($role == null) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            return $role; 
        }
        return $role; 
    }

    private function getOrCreatePermission($permissionName) {
        $permission = Permission::where('name', $permissionName)->first(); 
        if($permission == null) {
            $permission = Permission::create(['name'=> $permissionName]); 
            return $permission; 
        }
        return $permission; 
    }
}
