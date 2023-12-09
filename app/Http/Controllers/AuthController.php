<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required', 
            'email' => 'required|email', 
            'password' => 'required|min:8|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password-confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60)); 

                $user->save();

                event(new PasswordReset($user));
            }
        ); 

        return $status === Password::PASSWORD_RESET 
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
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
        $permission = $this->getOrCreatePermission($request->permission); 
        $role->givePermissionTo($permission['name']); 

        $user->assignRole($role['name']); 
        //or: 
        // $user->givePermissionTo($permission); 

        return response()->json([
            'user' => $user, 
            'role' => $role
        ]);
    }


    public function unauthorize(Request $request) {
        $user = User::find($request->user); 
        $role = $request->role; 
        $permission = $request->permission; 

        $user->revokePermissionTo($permission); 
        $user->removeRole($role); 

        return response()->json([
            'user' => $user, 
            'role' => $role, 
            'permission' => $permission, 
            'message' => 'Removed'
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
            $permission = Permission::create(['name'=> $permissionName, 'guard_name' => 'web']); 
            return $permission; 
        }
        return $permission; 
    }
}
