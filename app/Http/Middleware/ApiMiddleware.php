<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

// class ApiMiddleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         // ✅ Skip auth check for login & maintenance routes
//         // dd($request?->all());
//         if ($request->is('api/login') || $request->is('api/maintenance-check')) {
//             dd('sfsdf234');
//             return $next($request);
//         }
//         // 🔒 For all other routes, enforce authentication
//         if (!Auth::guard('api')->check()) {
//             dd('sfsdf');
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         return $next($request);
//     }
// }


class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Skip auth check for public routes
        if (
            $request->is('api/login') ||
            $request->is('api/maintenance-check') ||
            $request->is('api/fetch-features') ||
            $request->is('api/ccavenue/callback') ||
            $request->is('api/dynamic-page') ||
            $request->is('api/courses/*') ||
            $request->is('api/course/*') ||
            $request->is('course/*') ||
            $request->is('api/list/*') ||
            $request->is('api/resource/*') ||
            $request->is('resource/*') ||
            $request->is('api/all-courses') ||
            $request->is('api/categorised-course-list') ||
            $request->is('api/testimonials/*') ||
            $request->is('api/faq/*')
        ) {
            return $next($request);
        }

        try {
            // ✅ Parse and authenticate the token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // ✅ Allow the request to proceed
        return $next($request);
    }
}
