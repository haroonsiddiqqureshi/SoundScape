<?php

namespace App\Http\Controllers\Api;

use App\Models\Concert;
use App\Models\Concert_Log;
use App\Models\Artist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ConcertController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'ticket_link' => 'required|string',
                'description' => 'nullable|string',
                'genre' => 'nullable|string|max:100',
                'event_type' => 'nullable|string|max:100',
                'venue_name' => 'nullable|string|max:255',
                'province_id' => 'nullable|integer|exists:provinces,id',
                'province_name' => 'nullable|string|max:255',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0',
                'start_show_date' => 'nullable|date',
                'start_show_time' => 'nullable|date_format:H:i:s',
                'end_show_date' => 'nullable|date',
                'end_show_time' => 'nullable|date_format:H:i:s',
                'start_sale_date' => 'nullable|date',
                'end_sale_date' => 'nullable|date',
                'picture_url' => 'nullable|url',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'origin' => 'nullable|string',
                'artists' => 'nullable|array',
                'artists.*.name' => 'required_with:artists|string',
                'artists.*.image_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $artistsData = $data['artists'] ?? [];
            unset($data['artists']);

            // จัดการเรื่อง Province
            if (empty($data['province_id']) && !empty($data['province_name'])) {
                $name = $data['province_name'];
                $prov = Province::where('name_en', $name)->orWhere('name_th', $name)->first();
                if ($prov) {
                    $data['province_id'] = $prov->id;
                }
                unset($data['province_name']);
            }

            // ตรวจสอบข้อมูลซ้ำจาก ticket_link 
            $concert = Concert::withTrashed()->where('ticket_link', $data['ticket_link'])->first();

            $statusMessage = '';
            $statusCode = 200;

            if ($concert) {
                // --- กรณีเจอข้อมูลเดิม (Update Mode) ---
                if ($concert->trashed()) {
                    $concert->restore();
                }

                $fieldsToCheck = ['name', 'description', 'price_min', 'start_show_date', 'venue_name', 'picture_url'];

                foreach ($fieldsToCheck as $field) {
                    $oldVal = (string)$concert->$field;
                    $newVal = isset($data[$field]) ? (string)$data[$field] : null;

                    if ($newVal !== null && $oldVal !== $newVal) {
                        Concert_Log::create([
                            'concert_id' => $concert->id,
                            'field_name' => $field,
                            'old_value' => $oldVal,
                            'new_value' => $newVal,
                        ]);
                    }
                }

                $concert->update($data);
                $concert->touch();
                $statusMessage = 'Concert updated';
            } else {
                // --- กรณีไม่เจอ (Create Mode) ---
                $concert = Concert::create($data);
                $statusMessage = 'Concert created';
                $statusCode = 201;
            }

            // จัดการเรื่อง Artist
            if (!empty($artistsData)) {
                $artistIds = [];

                foreach ($artistsData as $artistItem) {
                    $artist = Artist::firstOrCreate(
                        ['name' => $artistItem['name']],
                        ['picture_url' => $artistItem['image_url'] ?? null]
                    );

                    $artistIds[] = $artist->id;
                }

                $concert->artists()->syncWithoutDetaching($artistIds);
            }

            return response()->json(['message' => $statusMessage, 'concert' => $concert], $statusCode);
        } catch (\Exception $e) {
            Log::error('Error processing concert: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function cleanup(Request $request)
    {
        $origin = $request->input('origin');

        if (!$origin) {
            return response()->json(['message' => 'Origin required'], 400);
        }

        $threshold = Carbon::now()->subMinutes(30);

        $deletedCount = Concert::where('origin', $origin)
            ->where('updated_at', '<', $threshold)
            ->delete();

        return response()->json([
            'message' => "Cleanup completed for $origin",
            'deleted_count' => $deletedCount
        ]);
    }
}
