<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class AdminProfileController extends Controller implements UpdatesUserPasswords
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = auth('admin')->user();

        Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->after(function ($validator) use ($user, $request) {
            if (! isset($request->current_password) || ! Hash::check($request->current_password, $user->password)) {
                $validator->errors()->add('current_password', 'The provided password does not match your current password.');
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return back()->with('status', 'password-updated');
    }
}
