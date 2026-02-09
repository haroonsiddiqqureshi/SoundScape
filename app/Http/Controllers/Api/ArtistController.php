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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', // เปลี่ยนเป็น required เพื่อใช้เป็น Key หลัก
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

            // ---------------------------------------------------------
            // แก้ไข: ใช้ updateOrCreate แทน create
            // ความหมาย: ค้นหาจาก 'name', ถ้าเจอให้อัปเดตข้อมูลที่เหลือ, ถ้าไม่เจอให้สร้างใหม่
            // ---------------------------------------------------------
            $artist = Artist::updateOrCreate(
                ['name' => $data['name']], // เงื่อนไขในการค้นหา (Check duplicates by Name)
                $data                       // ข้อมูลที่จะ Save/Update
            );
            // ---------------------------------------------------------

            // เช็คว่าเป็นการสร้างใหม่หรืออัปเดตเพื่อส่ง Status Code ที่เหมาะสม (Optional)
            $status = $artist->wasRecentlyCreated ? 201 : 200;
            $msg = $artist->wasRecentlyCreated ? 'Artist created successfully' : 'Artist updated successfully';

            return response()->json(['message' => $msg, 'artist' => $artist], $status);

        } catch (\Exception $e) {
            Log::error('Error processing artist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}