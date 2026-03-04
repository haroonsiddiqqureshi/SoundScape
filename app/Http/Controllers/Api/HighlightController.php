<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Highlight;
use App\Models\Concert;

class HighlightController extends Controller
{
    public function sync(Request $request)
    {
        $request->validate([
            'highlights' => 'required|array',
        ]);

        $highlightsData = $request->input('highlights');
        $processedIds = [];

        // Notice we added $index here to track the order!
        foreach ($highlightsData as $index => $data) {
            $concert = Concert::where('ticket_link', $data['link'])->first();

            $highlight = Highlight::updateOrCreate(
                ['link' => $data['link']], 
                [
                    'title' => $data['title'],
                    'picture_url' => $data['picture_url'],
                    'concert_id' => $concert ? $concert->id : null, 
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );

            $processedIds[] = $highlight->id;
        }

        $deletedCount = Highlight::whereNotIn('id', $processedIds)->delete();

        return response()->json([
            'message' => 'Highlights synced successfully',
            'processed_count' => count($processedIds),
            'deleted_count' => $deletedCount,
        ], 200);
    }
}