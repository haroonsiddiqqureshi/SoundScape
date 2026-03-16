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
            ->orderBy('name', 'desc')
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'picture_url' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('picture_url')) {
            $path = $request->file('picture_url')->store('artists', 'public');
            $validated['picture_url'] = $path;
        }

        Artist::create($validated);

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'picture_url' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('picture_url')) {
            if ($artist->picture_url) {
                Storage::disk('public')->delete($artist->picture_url);
            }

            $path = $request->file('picture_url')->store('artists', 'public');
            $validated['picture_url'] = $path;
        } else {
            unset($validated['picture_url']);
        }

        $artist->update($validated);

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
        if ($artist->picture_url) {
            Storage::disk('public')->delete($artist->picture_url);
        }

        $artist->delete();

        return Redirect::route('admin.artist.index')->with('success', 'Artist deleted.');
    }
}