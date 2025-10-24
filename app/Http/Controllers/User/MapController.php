<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Concert;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function index()
    {
        $concerts = Concert::all();

        $concerts->each(function ($concert) {
            $concert->is_followed = Follow::where('user_id', Auth::id())
                ->where('concert_id', $concert->id)
                ->exists();
        });

        return Inertia::render('User/Map/Index', [
            'concerts' => $concerts
        ]);
    }
}
