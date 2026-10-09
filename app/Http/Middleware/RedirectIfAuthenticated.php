<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{

    protected $except = [
        'courses/*/features',  // bypass CSRF for this route
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            // Skip redirect for API guard (so JWT/sanctum APIs don't get redirected)
            if ($guard === 'api' && Auth::guard($guard)->check()) {
                return $next($request);
            }

            // For web guard, redirect to home
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return $next($request);
    }
}
