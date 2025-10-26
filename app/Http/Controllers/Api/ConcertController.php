<?php

namespace App\Http\Controllers\Api;

use App\Models\Concert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConcertController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator =  Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'genre' => 'nullable|string|max:100',
                'event_type' => 'nullable|string|max:100',
                'venue_name' => 'nullable|string|max:255',
                'province_id' => 'nullable|integer|exists:provinces,id',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0|gte:price_min',
                'start_show_date' => 'nullable|date',
                'start_show_time' => 'nullable|date_format:H:i:s',
                'end_show_date' => 'nullable|date',
                'end_show_time' => 'nullable|date_format:H:i:s',
                'start_sale_date' => 'nullable|date',
                'end_sale_date' => 'nullable|date',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'picture_url' => 'nullable|url',
                'ticket_link' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $concert = Concert::create($data);

            return response()->json(['message' => 'Concert created successfully', 'concert' => $concert], 201);
        } catch (\Exception $e) {
            Log::error('Error creating concert: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the concert',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
