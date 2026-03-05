<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LineIntegrationController extends Controller
{
    public function redirect() {
        return Socialite::driver('line')->redirect();
    }

    public function callback() {
        $lineUser = Socialite::driver('line')->user();
        Auth::user()->update(['line_id' => $lineUser->getId()]);
        return redirect()->route('profile.show');
    }
}