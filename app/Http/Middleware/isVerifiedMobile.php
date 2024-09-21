<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isVerifiedMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (request()->user()?->phone_verified_at) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'The phone is not verified, check your phone messages the code was sent',
        ]);
    }
}