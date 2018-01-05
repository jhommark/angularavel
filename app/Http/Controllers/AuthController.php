<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Permission;
use App\Role;
use App\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:*')->except(['register', 'login']);
    }

    /**
     * Register a user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:8|max:20',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => $validation->errors()
            ]);
        }
        $user = User::create([
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

    /**
     * Get a JWT via given credentials.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (!$token = $this->guard()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        return response()->json([
            'success' => true,
            'data' => $this->guard()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    public function createRole(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function createPermission(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();

        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function assignRole(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        $role = Role::where('name', '=', $request->role)->first();
        $user->roles()->attach($role->id);

        return response()->json([
            'success' => true,
            'data' => $user->roles()->get()
        ]);
    }

    public function attachPermission(Request $request)
    {
        $role = Role::where('name', '=', $request->role)->first();
        $permission = Permission::where('name', '=', $request->name)->first();
        $role->perms()->attach($permission->id);

        return response()->json([
            'success' => true,
            'data' => $role->perms()->get()
        ]);
    }

    public function checkRoles(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        return response()->json([
            'success' => true,
            'data' => $user->roles()->get()
        ]);
    }
}
