<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Promoter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromoterController extends Controller
{
    public function index()
    {
        $promoter = Promoter::where('user_id', auth('web')->user()->id)->first();

        // If the promoter is not verified, send them to the Pending/Edit page
        if (!$promoter->is_verified) {
            return Inertia::render('Promoter/Components/Pending', [
                'promoter' => $promoter,
            ]);
        }

        // If verified, send them to the default Dashboard
        return Inertia::render('Promoter/Index', [
            'promoter' => $promoter,
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

    // Add this method to handle information updates while pending
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