<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Check which guard is authenticated
        if (Auth::guard('admin')->check()) {
            // Redirect admins to their dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // Redirect regular users to the default dashboard
        return redirect()->intended(config('fortify.home'));
    }
}