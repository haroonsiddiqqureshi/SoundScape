<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Concert;
use App\Models\Artist;
use App\Models\Province;
use App\Models\Concert_Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- Added for Geocoding
use Illuminate\Validation\ValidationException; // <-- Added for Geocoding error

class ConcertController extends Controller
{
    public function index()
    {
        // Retain promoter-specific query
        $concerts = Concert::where('promoter_id', auth('web')->user()->promoter->id)->get();
        
        // Add provinces, just like Admin
        $provinces = Province::all()->keyBy('id'); 
        
        return inertia::render('Promoter/Concert/Index', [
            'concerts' => $concerts,
            'provinces' => $provinces, // Pass provinces
        ]);
    }

    public function create()
    {
        // Add artists, just like Admin
        $artists = Artist::all(); 
        
        return inertia::render('Promoter/Concert/Create', [
            'artists' => $artists, // Pass artists
        ]);
    }

    public function store(Request $request)
    {
        // Use identical validation
        $validated = $request->validate($this->validationRules());
        
        // --- Start of Geocoding Block (Identical to Admin) ---
        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];

        if (is_null($latitude) && !empty($validated['venue_name']) && !empty($validated['province_id'])) {
            
            $province = Province::find($validated['province_id']);
            
            if ($province) {
                $queryString = urlencode($validated['venue_name'] . ', ' . $province->name_th . ', Thailand');
                $url = "https://nominatim.openstreetmap.org/search?q={$queryString}&format=json&limit=1&accept-language=th,en";

                $response = Http::withHeaders([
                    'User-Agent' => 'SoundScape/1.0 (haroonsiddiq.q@kkumail.com)'
                ])->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                
                    if (!empty($data)) {
                        $latitude = $data[0]['lat'];
                        $longitude = $data[0]['lon'];
                    } else {
                        throw ValidationException::withMessages([
                            'venue_name' => 'ไม่พบตำแหน่งนี้ ตรวจสอบชื่อสถานที่และจังหวัดอีกครั้ง หรือใช้แผนที่เพื่อปักหมุดเอง',
                        ]);
                    }
                }
            }
        }
        
        $validated['latitude'] = $latitude;
        $validated['longitude'] = $longitude;
        // --- End of Geocoding Block ---


        // Use identical create logic from Admin
        $concert = Concert::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'genre' => $validated['genre'],
            'event_type' => $validated['event_type'],
            'venue_name' => $validated['venue_name'],
            'province_id' => $validated['province_id'],
            'price_min' => $validated['price_min'],
            'price_max' => $validated['price_max'],
            'start_show_date' => $validated['start_show_date'],
            'start_show_time' => $validated['start_show_time'],
            'end_show_date' => $validated['end_show_date'],
            'end_show_time' => $validated['end_show_time'],
            'start_sale_date' => $validated['start_sale_date'],
            'end_sale_date' => $validated['end_sale_date'],
            'latitude' => $validated['latitude'], // Will now use geocoded value if available
            'longitude' => $validated['longitude'], // Will now use geocoded value if available
            'picture_url' => $validated['picture_url']->store('concerts', 'public'),
            'ticket_link' => $validated['ticket_link'],
            
            // Use promoter_id instead of admin_id
            'promoter_id' => auth('web')->user()->promoter->id, 
        ]);

        // Add artist relationship logic
        if (isset($validated['artist_ids'])) {
            $concert->artists()->attach($validated['artist_ids']);
        }

        return redirect()->route('promoter.concert.index')->with('success', 'Concert created successfully.');
    }

    public function detail(Concert $concert)
    {
        // Ensure the authenticated promoter owns this concert
        if ($concert->promoter_id !== Auth::user()->promoter->id) {
            abort(403);
        }

        // Add data loading, just like Admin
        $concert->load('artists');
        $provinces = Province::all()->keyBy('id');

        return Inertia::render('Promoter/Concert/Detail', [
            'concert' => $concert,
            'provinces' => $provinces, // Pass provinces
        ]);
    }

    public function edit(Concert $concert)
    {
        // Ensure the authenticated promoter owns this concert
        if ($concert->promoter_id !== Auth::user()->promoter->id) {
            abort(403);
        }

        // Add data loading, just like Admin
        $concert->load('artists');
        $artists = Artist::all();

        return Inertia::render('Promoter/Concert/Edit', [
            'concert' => $concert,
            'artists' => $artists, // Pass artists
        ]);
    }

    public function update(Request $request, Concert $concert)
    {
        // Ensure the authenticated promoter owns this concert
        if ($concert->promoter_id !== Auth::user()->promoter->id) {
            abort(403);
        }

        // Use identical validation
        $validated = $request->validate($this->validationRules(true));

        $originalData = $concert->getOriginal();
        
        // --- Start of Geocoding Block (Identical to Admin) ---
        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];

        if (is_null($latitude) && !empty($validated['venue_name']) && !empty($validated['province_id'])) {
            
            $province = Province::find($validated['province_id']);
            
            if ($province) {
                $queryString = urlencode($validated['venue_name'] . ', ' . $province->name_th . ', Thailand');
                $url = "https://nominatim.openstreetmap.org/search?q={$queryString}&format=json&limit=1&accept-language=th,en";

                $response = Http::withHeaders([
                    'User-Agent' => 'SoundScape/1.0 (haroonsiddiq.q@kkumail.com)'
                ])->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                
                    if (!empty($data)) {
                        $latitude = $data[0]['lat'];
                        $longitude = $data[0]['lon'];
                    } else {
                        throw ValidationException::withMessages([
                            'venue_name' => 'ไม่พบตำแหน่งนี้ ตรวจสอบชื่อสถานที่และจังหวัดอีกครั้ง หรือใช้แผนที่เพื่อปักหมุดเอง',
                        ]);
                    }
                }
            }
        }
        
        $validated['latitude'] = $latitude;
        $validated['longitude'] = $longitude;
        // --- End of Geocoding Block ---

        // Get promoter ID for logging
        $promoterId = Auth::user()->promoter->id; 

        // Use identical logging logic from Admin
        foreach ($validated as $key => $value) {

            if ($key === 'artist_ids') {

                $oldArtistIds = $concert->artists()->pluck('artists.id')->toArray();
                sort($oldArtistIds);

                $newArtistIds = $value ?? [];
                sort($newArtistIds);

                if ($oldArtistIds !== $newArtistIds) {
                    Concert_Log::create([
                        'concert_id' => $concert->id,
                        'promoter_id' => $promoterId, // Use promoter_id
                        'field_name' => 'artist_ids',
                        'old_value' => json_encode($oldArtistIds),
                        'new_value' => json_encode($newArtistIds),
                    ]);
                }
                continue;
            }

            if ($key !== 'picture_url' && $concert->{$key} != $value) {
                Concert_Log::create([
                    'concert_id' => $concert->id,
                    'promoter_id' => $promoterId, // Use promoter_id
                    'field_name' => $key,
                    'old_value' => $originalData[$key],
                    'new_value' => $value,
                ]);
            }
        }

        if ($request->hasFile('picture_url')) {
            $newPath = $request->file('picture_url')->store('concerts', 'public');
            Concert_Log::create([
                'concert_id' => $concert->id,
                'promoter_id' => $promoterId, // Use promoter_id
                'field_name' => 'picture_url',
                'old_value' => $concert->picture_url,
                'new_value' => $newPath,
            ]);
            $validated['picture_url'] = $newPath;
        } else {
            unset($validated['picture_url']);
        }

        unset($validated['artist_ids']);

        $concert->update($validated);

        // Add artist sync logic
        if (isset($request->artist_ids)) {
            $concert->artists()->sync($request->artist_ids ?? []);
        }

        return redirect()->route('promoter.concert.detail', $concert)->with('success', 'Concert updated successfully.');
    }

    // Add destroy method from Admin
    public function destroy(Concert $concert)
    {
        // Ensure the authenticated promoter owns this concert
        if ($concert->promoter_id !== Auth::user()->promoter->id) {
            abort(403);
        }

        if ($concert->picture_url) {
            Storage::disk('public')->delete($concert->picture_url);
        }
        $concert->delete();
        return redirect()->route('promoter.concert.index')->with('success', 'Concert deleted successfully.');
    }

    // Use identical validation rules from Admin
    private function validationRules($isUpdate = false)
    {
        $rules = [
            // Core Information
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_type' => 'nullable|in:music_festival,concert,club,fan_meeting,folk,other',
            'genre' => 'nullable|in:pop,rock,hiphop,jazz,classical,country,edm,other',

            // Location
            'venue_name' => 'nullable|string|max:255',
            'province_id' => 'nullable|exists:provinces,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Prices
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',

            // Dates and Times
            'start_sale_date' => 'nullable|date_format:Y-m-d',
            'end_sale_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_sale_date',
            'start_show_date' => 'nullable|date_format:Y-m-d',
            'start_show_time' => 'nullable|date_format:H:i:s',
            'end_show_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_show_date',
            'end_show_time' => 'nullable|date_format:H:i:s',

            // Additional Information
            'ticket_link' => 'nullable|url',

            'artist_ids' => 'nullable|array',
            'artist_ids.*' => 'exists:artists,id',
        ];

        if ($isUpdate) {
            $rules['picture_url'] = 'nullable|image|max:2048';
        } else {
            $rules['picture_url'] = 'required|image|max:2048';
        }

        return $rules;
    }
}