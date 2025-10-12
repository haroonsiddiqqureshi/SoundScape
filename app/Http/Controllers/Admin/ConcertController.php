<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Concert_Log;
use Carbon\Carbon;

class ConcertController extends Controller
{

    public function index()
    {
        $concerts = Concert::all();
        return Inertia::render('Admin/Concert/Index', [
            'concerts' => $concerts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Concert/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        Concert::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'artists' => $validated['artists'] ?? null,
            'genre' => $validated['genre'] ?? null,
            'status' => $validated['status'],
            'venue_name' => $validated['venue_name'],
            'city' => $validated['city'],
            'price' => $validated['price'] ?? null,
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'picture_url' => $validated['picture_url']->store('concerts', 'public'),
            'ticket_link' => $validated['ticket_link'] ?? null,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.concert.index')->with('success', 'Concert created successfully.');
    }

    public function detail(Concert $concert)
    {
        return Inertia::render('Admin/Concert/Detail', [
            'concert' => $concert,
        ]);
    }

    public function edit(Concert $concert)
    {
        return Inertia::render('Admin/Concert/Edit', [
            'concert' => $concert,
        ]);
    }

    public function update(Request $request, Concert $concert)
    {
        $validated = $request->validate($this->validationRules(true));

        $originalData = $concert->getOriginal();

        // Log changes
        foreach ($validated as $key => $value) {
            if ($key === 'start_datetime') {
                // Parse both dates to Carbon objects for accurate comparison
                $oldDate = Carbon::parse($concert->start_datetime);
                $newDate = Carbon::parse($value);

                if ($oldDate->ne($newDate)) { // `ne` is "not equal"
                    Concert_Log::create([
                        'concert_id' => $concert->id,
                        'admin_id' => Auth::guard('admin')->id(),
                        'field_name' => $key,
                        'old_value' => $concert->start_datetime,
                        'new_value' => $newDate->toDateTimeString(),
                    ]);
                }
            } elseif ($key !== 'picture_url' && $concert->{$key} != $value) {
                Concert_Log::create([
                    'concert_id' => $concert->id,
                    'admin_id' => Auth::guard('admin')->id(),
                    'field_name' => $key,
                    'old_value' => $originalData[$key] ?? 'null',
                    'new_value' => $value,
                ]);
            }
        }

        if ($request->hasFile('picture_url')) {
            // Handle file upload and log change
            $newPath = $request->file('picture_url')->store('concerts', 'public');
            Concert_Log::create([
                'concert_id' => $concert->id,
                'admin_id' => Auth::guard('admin')->id(),
                'field_name' => 'picture_url',
                'old_value' => $concert->picture_url,
                'new_value' => $newPath,
            ]);
            $validated['picture_url'] = $newPath;
        } else {
            // Exclude picture_url from the update if no new file is uploaded
            unset($validated['picture_url']);
        }

        $concert->update($validated);

        return redirect()->route('admin.concert.detail', $concert)->with('success', 'Concert updated successfully.');
    }

    private function validationRules($isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'artists' => 'nullable|array',
            'genre' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,completed,cancelled',
            'venue_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'ticket_link' => 'nullable|url',
        ];

        if ($isUpdate) {
            $rules['picture_url'] = 'nullable|image|max:1024';
        } else {
            $rules['picture_url'] = 'required|image|max:1024';
        }

        return $rules;
    }
}
