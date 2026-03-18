<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Promoter;
use App\Models\Concert;
use App\Models\Comment;
use App\Models\Follow;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromoterController extends Controller
{
    public function index()
    {
        $promoter = Promoter::where('user_id', auth('web')->user()->id)->first();

        if (!$promoter->is_verified) {
            return Inertia::render('Promoter/Components/Pending', [
                'promoter' => $promoter,
            ]);
        }

        $promoterConcerts = Concert::where('promoter_id', $promoter->id);
        $promoterConcertIds = $promoterConcerts->pluck('id');

        $stats = [
            'total_concerts' => $promoterConcertIds->count(),
            'total_views' => (int) $promoterConcerts->sum('view_count'),
            'total_follows' => Follow::whereIn('concert_id', $promoterConcertIds)->count(),
            'total_comments' => Comment::whereIn('concert_id', $promoterConcertIds)->count(),
        ];

        $upcomingConcerts = Concert::where('promoter_id', $promoter->id)
            ->where('start_show_date', '>=', now())
            ->orderBy('start_show_date', 'asc')
            ->take(10)
            ->get();

        return Inertia::render('Promoter/Index', [
            'promoter' => $promoter,
            'stats' => $stats,
            'upcomingConcerts' => $upcomingConcerts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Promoter/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'business_category' => 'required|string|in:individual,company,nightclub',
            'business_address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
        ]);

        Promoter::create([
            'user_id' => auth('web')->user()->id,
            'fullname' => $validated['fullname'],
            'business_name' => $validated['business_name'],
            'business_category' => $validated['business_category'],
            'business_address' => $validated['business_address'],
            'bio' => $validated['bio'] ?? null,
            'social_links' => $validated['social_links'] ?? [],
            'is_verified' => false,
        ]);

        return redirect()->route('promoter.index')->with('success', 'Promoter profile created successfully.');
    }

    public function edit()
    {
        $promoter = Promoter::where('user_id', auth('web')->user()->id)->firstOrFail();

        return Inertia::render('Promoter/Profile', [
            'promoter' => $promoter,
        ]);
    }

    public function update(Request $request)
    {
        $promoter = Promoter::where('user_id', auth('web')->user()->id)->firstOrFail();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'business_category' => 'required|string|in:individual,company,nightclub',
            'business_address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
        ]);

        $promoter->update([
            'fullname' => $validated['fullname'],
            'business_name' => $validated['business_name'],
            'business_category' => $validated['business_category'],
            'business_address' => $validated['business_address'],
            'bio' => $validated['bio'] ?? null,
            'social_links' => $validated['social_links'] ?? [],
        ]);

        $message = $promoter->is_verified
            ? 'Promoter profile updated successfully.'
            : 'Promoter profile updated successfully. Awaiting verification.';

        return back()->with('success', $message);
    }
}
