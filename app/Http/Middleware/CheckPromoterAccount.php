<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPromoterAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Avoid redirecting if the user is already on promoter routes (prevents loops)
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            if ($user->promoter) {
                if ($currentRoute === 'promoter.index' || str_starts_with($currentRoute ?? '', 'promoter.')) {
                    return $next($request);
                }

                // User has a promoter account and is not on a promoter route -> redirect to promoter index
                return redirect()->route('promoter.index');
            } else {
                if ($currentRoute === 'promoter.create' || $currentRoute === 'promoter.store' || str_ends_with($currentRoute ?? '', '.create')) {
                    return $next($request);
                }

                // User does not have a promoter account, redirect to create page
                return redirect()->route('promoter.create');
            }
        }

        return $next($request);
    }
}
