<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Artist;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $artists = Artist::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            // ->where('picture_url', '!=', 'https://static.joox.com/pc/prod/static/di/default/default-artist@300.png')
            ->latest()
            ->paginate(30);

        return Inertia::render('Admin/Artist/Index', [
            'artists' => $artists,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        // Just render the create page
        return Inertia::render('Admin/Artist/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'picture_url' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Store the uploaded file and get its path
        if ($request->hasFile('picture_url')) {
            $path = $request->file('picture_url')->store('artists', 'public');
            $validated['picture_url'] = $path;
        }

        // Create the new artist
        Artist::create($validated);

        // Redirect back to the index page with a success message
        return redirect()->route('admin.artist.index')->with('success', 'Artist created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Inertia\Response
     */
    public function edit(Artist $artist)
    {
        return Inertia::render('Admin/Artist/Edit', [
            'artist' => $artist,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Artist $artist)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'picture_url' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Check if a new picture has been uploaded
        if ($request->hasFile('picture_url')) {
            // Delete the old picture if it exists
            if ($artist->picture_url) {
                Storage::disk('public')->delete($artist->picture_url);
            }

            // Store the new picture and get its path
            $path = $request->file('picture_url')->store('artists', 'public');
            $validated['picture_url'] = $path;
        } else {
            // If no new file is uploaded, remove picture_url from the
            // validated array so it doesn't overwrite the existing one with null
            unset($validated['picture_url']);
        }

        // Update the artist
        $artist->update($validated);

        // Redirect back to the index page with a success message
        return redirect()->route('admin.artist.index')->with('success', 'Artist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Artist $artist)
    {
        // Delete the artist's picture from storage if it exists
        if ($artist->picture_url) {
            Storage::disk('public')->delete($artist->picture_url);
        }

        // Delete the artist record from the database
        $artist->delete();

        // Redirect back to the index page with a success message
        return Redirect::route('admin.artist.index')->with('success', 'Artist deleted.');
    }
}