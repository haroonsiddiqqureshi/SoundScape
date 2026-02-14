<?php

namespace App\Http\Controllers\Api;

use App\Models\Concert;
use App\Models\Concert_Log; // [เพิ่ม Model Log]
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // [เพิ่ม Carbon]

class ConcertController extends Controller
{
    public function store(Request $request)
    {
        try {
            // 1. Validate ข้อมูล
            // หมายเหตุ: ตัด unique:concerts ออกจาก name เพราะเราจะเช็คจาก link แทน
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'ticket_link' => 'required|string', // จำเป็นต้องมีเพื่อใช้เช็คซ้ำ
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
                'origin' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();

            // 2. จัดการเรื่อง Province (Logic เดิมของคุณ)
            if (empty($data['province_id']) && !empty($data['province_name'])) {
                $name = $data['province_name'];
                $prov = Province::where('name_en', $name)->orWhere('name_th', $name)->first();
                if ($prov) {
                    $data['province_id'] = $prov->id;
                }
                unset($data['province_name']);
            }

            // 3. ตรวจสอบข้อมูลซ้ำจาก ticket_link (พระเอกของงาน)
            $concert = Concert::withTrashed() // เช็คตัวที่ถูกลบไปแล้วด้วยเผื่อกู้คืน
                ->where('ticket_link', $data['ticket_link'])
                ->first();

            if ($concert) {
                // --- กรณีเจอข้อมูลเดิม (Update Mode) ---
                
                // ถ้าเคยถูก Soft Delete ให้กู้คืนกลับมา
                if ($concert->trashed()) {
                    $concert->restore();
                }

                // เช็คความเปลี่ยนแปลงเพื่อบันทึก Log
                $fieldsToCheck = ['name', 'description', 'price_min', 'start_show_date', 'venue_name', 'picture_url'];
                
                foreach ($fieldsToCheck as $field) {
                    // แปลงค่าเป็น string เพื่อเทียบ (ป้องกัน error คนละ type)
                    $oldVal = (string)$concert->$field;
                    $newVal = isset($data[$field]) ? (string)$data[$field] : null;

                    if ($newVal !== null && $oldVal !== $newVal) {
                        Concert_Log::create([
                            'concert_id' => $concert->id,
                            'field_name' => $field,
                            'old_value' => $oldVal,
                            'new_value' => $newVal,
                            // admin_id, promoter_id เป็น null เพราะระบบทำ
                        ]);
                    }
                }

                $concert->update($data);
                $concert->touch(); // สำคัญ! อัปเดต updated_at เป็นปัจจุบัน
                
                return response()->json(['message' => 'Concert updated', 'concert' => $concert], 200);

            } else {
                // --- กรณีไม่เจอ (Create Mode) ---
                $concert = Concert::create($data);
                return response()->json(['message' => 'Concert created', 'concert' => $concert], 201);
            }

        } catch (\Exception $e) {
            Log::error('Error processing concert: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    // ฟังก์ชันใหม่สำหรับลบข้อมูลที่หายไป (Sweep)
    public function cleanup(Request $request)
    {
        $origin = $request->input('origin'); // เช่น "The Concert"
        
        if (!$origin) {
            return response()->json(['message' => 'Origin required'], 400);
        }

        // ลบคอนเสิร์ตของ Origin นี้ ที่ไม่ได้ถูกอัปเดตในช่วง 30 นาทีที่ผ่านมา
        // แปลว่ารอบนี้ Scraper หาไม่เจอแล้ว
        $threshold = Carbon::now()->subMinutes(30);

        $deletedCount = Concert::where('origin', $origin)
            ->where('updated_at', '<', $threshold)
            ->delete(); // Soft Delete

        return response()->json([
            'message' => "Cleanup completed for $origin",
            'deleted_count' => $deletedCount
        ]);
    }
}