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
    public function index(Request $request)
    {
        $highlights = Highlight::where('is_active', true)->get();
        $provinces = Province::get();

        $query = Concert::query();

        $query->when($request->input('search'), function ($q, $search) {
            return $this->buildSearchQuery($q, $search);
        });

        $query->when($request->input('event_type'), function ($q, $eventType) {
            return $q->where('event_type', $eventType);
        });

        $query->when($request->input('genre'), function ($q, $genre) {
            return $q->where('genre', $genre);
        });

        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'date_asc':
                $query->orderBy('start_show_date', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price_min', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_min', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $concerts = $query->get();

        $followedConcerts = [];
        if (Auth::check()) {
            $followedConcerts = Follow::where('user_id', Auth::id())
                ->pluck('concert_id')
                ->flip();
        }

        $concerts->each(function ($concert) use ($followedConcerts) {
            $concert->is_followed = isset($followedConcerts[$concert->id]);
        });

        return Inertia::render('Index', [
            'highlights' => $highlights,
            'concerts' => $concerts,
            'provinces' => $provinces,
            'filters' => (object) $request->only(['search', 'event_type', 'genre', 'sort']),
        ]);
    }

    public function searchQuery(Request $request)
    {
        $search = $request->input('q');

        if (empty($search) || strlen($search) < 2) {
            return response()->json(['data' => []]);
        }

        $query = Concert::query();
        $results = $this->buildSearchQuery($query, $search)
            ->with(['artists', 'province'])
            ->take(7)
            ->get();

        return response()->json(['data' => $results]);
    }

    public function concertDetail(Concert $concert)
    {
        $concert->load('artists');
        $provinces = Province::all()->keyBy('id');
        $followedConcerts = [];
        if (Auth::check()) {
            $followedConcerts = Follow::where('user_id', Auth::id())
                ->pluck('concert_id')
                ->flip();
        }

        $concert->is_followed = isset($followedConcerts[$concert->id]);

        return Inertia::render('User/Concert/Detail', [
            'concert' => $concert,
            'provinces' => $provinces,
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
            Follow::firstOrCreate([
                'user_id' => Auth::id(),
                'concert_id' => $concert->id,
            ]);
        } else {
            Follow::where('user_id', Auth::id())
                ->where('concert_id', $concert->id)
                ->delete();
        }
    }

    private function buildSearchQuery($query, $search)
    {
        return $query->where(function ($subQuery) use ($search) {
            $searchTerm = '%' . $search . '%';

            $subQuery->where('name', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm)
                ->orWhere('event_type', 'like', $searchTerm)
                ->orWhere('genre', 'like', $searchTerm)
                ->orWhere('venue_name', 'like', $searchTerm)

                ->orWhereHas('artists', function ($artistQuery) use ($searchTerm) {
                    $artistQuery->where('name', 'like', $searchTerm);
                })

                ->orWhereHas('province', function ($provinceQuery) use ($searchTerm) {
                    $provinceQuery->where('name_th', 'like', $searchTerm)
                        ->orWhere('name_en', 'like', $searchTerm);
                });
        });
    }
}
