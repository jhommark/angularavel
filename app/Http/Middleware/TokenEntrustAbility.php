<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenEntrustAbility extends BaseMiddleware
{
    const DELIMITER = '|';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  \App\Role $roles
     * @param  \App\Permission $permissions
     * @param  bool $validateAll
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {
        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if (!is_bool($validateAll)) {
            $validateAll = filter_var($validateAll, FILTER_VALIDATE_BOOLEAN);
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

        if (!$user->ability($roles, $permissions, ['validate_all' => $validateAll])) {
            return response()->error('Unauthorized', 401);
        }

        return $next($request);
    }
}
