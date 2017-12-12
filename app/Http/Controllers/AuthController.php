<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use App\User;

class AuthController extends Controller
{
    
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
   
    public function register(Request $request) {
        $user = $this->user->create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json([
            'response' => 'success',
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
                    'response' => 'error',
                    'message' => 'Invalid email or password',
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
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
        return response()->json(['response' => 'success', 'data' => $user]);
    }
}
