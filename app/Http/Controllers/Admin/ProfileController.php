<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show()
    {
        return Inertia::render('User/Profile/Show', [
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    }
}
