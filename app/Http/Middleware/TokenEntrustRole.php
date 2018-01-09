<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenEntrustRole extends BaseMiddleware
{
    const DELIMITER = '|';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if (!$token = $this->auth->setRequest($request)->getToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 400);
        }

        if (!$token = $this->auth->setRequest($request)->getToken()) {
            return response()->error('Token not provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->error('Token has expired', 400);
        } catch (JWTException $e) {
            return response()->error('Could not decode token', 400);
        }

        if (!$user) {
            return response()->error('User does not exist', 404);
        }

        if (!$user->hasRole($roles)) {
            return response()->error('Unauthorized', 401);
        }

        return $next($request);
    }
}