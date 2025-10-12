<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Highlight;
use App\Models\Concert;

class HighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::all();
        return Inertia::render('Admin/Highlight/Index', [
            'highlights' => $highlights,
        ]);
    }

    public function create()
    {
        $concerts = Concert::all();
        return Inertia::render('Admin/Highlight/Create', [
            'concerts' => $concerts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules(true));

        Highlight::create([
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'picture_url' => $validated['picture_url']->store('highlights', 'public'),
            'link' => $validated['link'] ?? null,
            'concert_id' => $validated['concert_id'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
        ]);

        return redirect()->route('admin.highlight.index')->with('success', 'Highlight created successfully.');
    }

    public function edit(Highlight $highlight)
    {
        $concerts = Concert::all();
        return Inertia::render('Admin/Highlight/Edit', [
            'highlight' => $highlight,
            'concerts' => $concerts,
        ]);
    }

    public function update(Request $request, Highlight $highlight)
    {
        $validated = $request->validate($this->validationRules(true));

        if (isset($validated['picture_url'])) {
            $highlight->picture_url = $validated['picture_url']->store('highlights', 'public');
        }

        $highlight->title = $validated['title'] ?? $highlight->title;
        $highlight->description = $validated['description'] ?? $highlight->description;
        $highlight->link = $validated['link'] ?? $highlight->link;
        $highlight->concert_id = $validated['concert_id'] ?? $highlight->concert_id;
        $highlight->is_active = $validated['is_active'] ?? $highlight->is_active;

        $highlight->save();

        return redirect()->route('admin.highlight.index')->with('success', 'Highlight updated successfully.');
    }

    public function updateActiveStatus(Highlight $highlight)
    {
        $highlight->is_active = !$highlight->is_active;
        $highlight->save();

        return back()->with('success', 'Highlight status updated.');
    }

    private function validationRules($isUpdate = false)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'concert_id' => 'nullable|exists:concerts,id',
        ];

        if ($isUpdate) {
            $rules['picture_url'] = 'nullable|image|max:1024';
        } else {
            $rules['picture_url'] = 'required|image|max:1024';
        }

        return $rules;
    }
}
