<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Highlight;
use App\Models\Concert;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HighlightController extends Controller
{
    public function index(Request $request) // <-- Accept Request
    {
        $highlights = Highlight::query()
            // Search filter (this remains)
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            // --- MODIFICATION ---
            // 1. Sort by active status first (true/1 comes before false/0)
            ->orderBy('is_active', 'desc')
            // 2. Then, sort by the newest created
            ->latest()
            // --------------------
            ->get();

        return Inertia::render('Admin/Highlight/Index', [
            'highlights' => $highlights,
            'filters' => $request->only('search'), // <-- 3. Only pass 'search' back
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
        $validated = $request->validate($this->validationRules());
        
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

        if ($request->hasFile('picture_url')) {
            $newPath = $request->file('picture_url')->store('highlights', 'public');
            $validated['picture_url'] = $newPath;
        } else {
            unset($validated['picture_url']);
        }

        $highlight->update($validated);

        return redirect()->route('admin.highlight.index', $highlight)->with('success', 'Highlight updated successfully.');
    }

    public function updateActiveStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $highlight = Highlight::findOrFail($id);
        $highlight->is_active = $request->input('is_active');
        $highlight->save();
    }

    public function destroy(Highlight $highlight)
    {
        if ($highlight->picture_url) {
            Storage::disk('public')->delete($highlight->picture_url);
        }
        $highlight->delete();
        return redirect()->route('admin.highlight.index')->with('success', 'Highlight deleted successfully.');
    }

    private function validationRules($isUpdate = false)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'concert_id' => 'nullable|exists:concerts,id',
            'link' => 'nullable|url|max:255',
        ];

        if ($isUpdate) {
            $rules['picture_url'] = 'nullable|image|max:2048';
        } else {
            $rules['picture_url'] = 'required|image|max:2048';
        }

        return $rules;
    }
}
