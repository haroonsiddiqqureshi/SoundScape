<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return inertia('User/Profile/Show', [
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    }
}
