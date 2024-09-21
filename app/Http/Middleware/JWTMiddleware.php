<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'token_absent'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        return $next($request);
    }
}

/*
    try
    {
        $expired_token = JWTAuth::getToken();
        $refreshed_token = JWTAuth::refresh($expired_token);
        JWTAuth::invalidate($expired_token);
        $user = JWTAuth::setToken($refreshed_token)->toUser();
        return response()->json([
            'refreshed_token' => $refreshed_token
        ]);
    }catch (JWTException $e){
        return response()->json([
            'code'   => 103,
            'message' => 'Token cannot be refreshed, please Login again'
        ]);
    }
*/
