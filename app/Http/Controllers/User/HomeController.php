<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Highlight;
use App\Models\Concert;
use App\Models\Follow;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $highlights = Highlight::where('is_active', true)->get();
        $concerts = Concert::all();
        $provinces = Province::all()->keyBy('id');

        $concerts->each(function ($concert) {
            $concert->is_followed = Follow::where('user_id', Auth::id())
                ->where('concert_id', $concert->id)
                ->exists();
        });

        return Inertia::render('Index', [
            'highlights' => $highlights,
            'concerts' => $concerts,
            'provinces' => $provinces,
        ]);
    }

    public function concertDetail(Concert $concert)
    {
        $follow = Follow::where('user_id', Auth::id())
            ->where('concert_id', $concert->id)
            ->first();
        return Inertia::render('User/Concert/ConcertDetail', [
            'concert' => $concert,
            'follow' => $follow,
        ]);
    }

    public function followConcert(Request $request, $id)
    {
        Auth::check() or abort(403, 'You must be logged in to follow concerts.');

        $request->validate([
            'is_following' => 'required|boolean',
        ]);

        $concert = Concert::findOrFail($id);
        if ($request->input('is_following')) {
            // Follow the concert
            Follow::firstOrCreate([
                'user_id' => Auth::id(),
                'concert_id' => $concert->id,
            ]);
        } else {
            // Unfollow the concert
            Follow::where('user_id', Auth::id())
                ->where('concert_id', $concert->id)
                ->delete();
        }
    }
}
