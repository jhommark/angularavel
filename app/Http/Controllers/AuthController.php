<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use App\Permission;
use App\Role;
use App\User;

class AuthController extends Controller
{
    
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
   
    public function register(Request $request) {
        $user = $this->user->create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password)
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password',
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create token',
            ]);
        }
        return response()->json([
            'response' => 'success',
            'data' => [
                'token' => $token,
            ],
        ]);
    }

    public function getUser(Request $request) {
        $user = JWTAuth::toUser($request->token);        
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function createRole(Request $request) {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        return response()->json(['success' => true, 'data' => $role]);
    }

    public function createPermission(Request $request) {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();
        return response()->json(['success' => true, 'data' => $permission]);
    }

    public function assignRole(Request $request) {
        $user = User::where('email', '=', $request->email)->first();
        $role = Role::where('name', '=', $request->role)->first();
        $user->roles()->attach($role->id);
        return response()->json(['success' => true, 'data' => $user->roles()->get()]);
    }

    public function attachPermission(Request $request) {
        $role = Role::where('name', '=', $request->role)->first();
        $permission = Permission::where('name', '=', $request->name)->first();
        $role->perms()->attach($permission->id);
        return response()->json(['success' => true, 'data' => $role->perms()->get()]);
    }

    public function checkRoles(Request $request) {
        $user = User::where('email', '=', $request->email)->first();
        return response()->json([
            "user" => $user,
            "admin" => $user->hasRole('admin'),
            "viewUsers" => $user->can('view-users'),
            "addUsers" => $user->can('add-users'),
            "editUsers" => $user->can('edit-users'),
            "deleteUsers" => $user->can('delete-users'),
        ]);
    }
}
