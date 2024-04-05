<?php

namespace App\Http\Middleware;

use App\Exceptions\PasswordRequiredException;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class IsFirstAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => __('messages.user.not_found')], 500);
        }

        if($user->isFirstAccess()){
           return throw new PasswordRequiredException(__('messages.user.first_access'));
        }

        return $next($request);
    }
}
