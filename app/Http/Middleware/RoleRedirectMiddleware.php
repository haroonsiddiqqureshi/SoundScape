<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        $otherGuard = ($guard === 'admin') ? 'web' : 'admin';

        // If the user is authenticated with the other guard, log them out of it.
        if (Auth::guard($otherGuard)->check()) {
            Auth::guard($otherGuard)->logout();
        }

        // Now, check if the user is authenticated with the required guard.
        if (!Auth::guard($guard)->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
