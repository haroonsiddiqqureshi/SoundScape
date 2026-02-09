<?php

namespace App\Http\Controllers\Api;

use App\Models\Concert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\Log;

class ConcertController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', // ต้องมี Name
                'description' => 'nullable|string',
                'genre' => 'nullable|string|max:100',
                'event_type' => 'nullable|string|max:100',
                'venue_name' => 'nullable|string|max:255',
                'province_id' => 'nullable|integer|exists:provinces,id',
                'province_name' => 'nullable|string|max:255',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0|gte:price_min',
                'start_show_date' => 'nullable|date',
                'start_show_time' => 'nullable|date_format:H:i:s',
                'end_show_date' => 'nullable|date',
                'end_show_time' => 'nullable|date_format:H:i:s',
                'start_sale_date' => 'nullable|date',
                'end_sale_date' => 'nullable|date',
                'picture_url' => 'nullable|url', // หรือ string ตาม Database คุณ
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'origin' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // (Logic หาจังหวัดเดิมของคุณ...)
            if (empty($data['province_id']) && !empty($data['province_name'])) {
                $name = $data['province_name'];
                $prov = Province::where('name_en', $name)
                    ->orWhere('name_th', $name)
                    ->first();

                if (!$prov) {
                    // ถ้าไม่เจอจังหวัด จะให้ Error หรือปล่อยผ่านก็ได้ (ตรงนี้ผมคงเดิมไว้)
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation error',
                        'errors' => ['province_name' => ['Province not found']]
                    ], 422);
                }

                $data['province_id'] = $prov->id;
                unset($data['province_name']);
            }

            $concert = Concert::updateOrCreate(
                [
                    'name' => $data['name'], 
                ],
                $data
            );
            // ---------------------------------------------------------

            $status = $concert->wasRecentlyCreated ? 201 : 200;
            $msg = $concert->wasRecentlyCreated ? 'Concert created successfully' : 'Concert updated successfully';

            return response()->json(['message' => $msg, 'concert' => $concert], $status);

        } catch (\Exception $e) {
            Log::error('Error processing concert: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}