<?php

namespace App\Http\Controllers\Api;

use App\Models\Artist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArtistController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator =  Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'picture_url' => 'nullable|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $artist = Artist::create($data);

            return response()->json(['message' => 'Artist created successfully', 'artist' => $artist], 201);
        } catch (\Exception $e) {
            Log::error('Error creating artist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the artist',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
