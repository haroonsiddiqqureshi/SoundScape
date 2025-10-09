<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConcertController extends Controller
{

    public function index()
    {
        $concerts = Concert::all();
        return Inertia::render('Admin/Concert/Index', [
            'concerts' => $concerts,
        ]);
    }

    public function detail(Concert $concert)
    {
        return Inertia::render('Admin/Concert/Detail', [
            'concert' => $concert,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Concert/Create');
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
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.concert.index')->with('success', 'Concert created successfully.');
    }
}
