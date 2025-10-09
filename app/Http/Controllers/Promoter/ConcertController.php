<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Concert;

class ConcertController extends Controller
{
    public function index()
    {
        $concerts = Concert::where('promoter_id', auth('web')->user()->promoter->id)->get();
        return inertia::render('User/Promoter/Concert/Index', [
            'concerts' => $concerts,
        ]);
    }

    public function detail(Concert $concert)
    {
        return Inertia::render('User/Promoter/Concert/Detail', [
            'concert' => $concert,
        ]);
    }

    public function create()
    {
        return inertia::render('User/Promoter/Concert/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:upcoming,completed,cancelled',
            'venue_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'concert_datetime' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            'picture_url' => 'required|image|max:1024',
            'ticket_link' => 'nullable|url',
        ]);

        Concert::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'venue_name' => $validated['venue_name'],
            'city' => $validated['city'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'concert_datetime' => $validated['concert_datetime'],
            'price' => $validated['price'] ?? null,
            'picture_url' => $validated['picture_url']->store('concerts', 'public'),
            'ticket_link' => $validated['ticket_link'] ?? null,
            'promoter_id' => auth('web')->user()->promoter->id,
        ]);

        return redirect()->route('promoter.concert.index')->with('success', 'Concert created successfully.');
    }
}
