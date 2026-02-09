<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Artist;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Concert_Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ConcertController extends Controller
{

    public function index(Request $request)
    {
        $concerts = Concert::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        $provinces = Province::all()->keyBy('id');
        return Inertia::render('Admin/Concert/Index', [
            'concerts' => $concerts,
            'provinces' => $provinces,
        ]);
    }

    public function create()
    {
        $artists = Artist::all();
        return Inertia::render('Admin/Concert/Create', [
            'artists' => $artists,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

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
            'latitude' => $latitude,   
            'longitude' => $longitude,
            'picture_url' => $validated['picture_url']->store('concerts', 'public'),
            'ticket_link' => $validated['ticket_link'],
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        if (isset($validated['artist_ids'])) {
            $concert->artists()->attach($validated['artist_ids']);
        }

        return redirect()->route('admin.concert.index')->with('success', 'Concert created successfully.');
    }

    public function detail(Concert $concert)
    {
        $concert->load('artists');
        $provinces = Province::all()->keyBy('id');
        return Inertia::render('Admin/Concert/Detail', [
            'concert' => $concert,
            'provinces' => $provinces,
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

        foreach ($validated as $key => $value) {
            if ($key === 'start_datetime') {
                $oldDate = Carbon::parse($concert->start_datetime);
                $newDate = Carbon::parse($value);

                if ($oldDate->ne($newDate)) {
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
            unset($validated['picture_url']);
        }

        $concert->update($validated);

        if ($request->has('artist_ids')) {
            $concert->artists()->sync($validated['artist_ids'] ?? []);
        }

        return redirect()->route('admin.concert.detail', $concert)->with('success', 'Concert updated successfully.');
    }

    public function destroy(Concert $concert)
    {
        $concert->delete();
        return redirect()->route('admin.concert.index')->with('success', 'Concert deleted successfully.');
    }

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