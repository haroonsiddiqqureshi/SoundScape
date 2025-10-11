<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Highlight;

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
        return Inertia::render('Admin/Highlight/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        Highlight::create([
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'picture_url' => $validated['picture_url']->store('concerts', 'public'),
            'link' => $validated['link'] ?? null,
            'concert_id' => $validated['concert_id'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
        ]);

        return redirect()->route('admin.highlight.index')->with('success', 'Highlight created successfully.');
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
