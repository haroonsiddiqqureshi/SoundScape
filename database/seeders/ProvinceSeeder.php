<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->insert([
            ['name_th' => 'กระบี่'],
            ['name_th' => 'กรุงเทพมหานคร'],
            ['name_th' => 'กาญจนบุรี'],
            ['name_th' => 'กาฬสินธุ์'],
            ['name_th' => 'กำแพงเพชร'],
            ['name_th' => 'ขอนแก่น'],
            ['name_th' => 'จันทบุรี'],
            ['name_th' => 'ฉะเชิงเทรา'],
            ['name_th' => 'ชลบุรี'],
            ['name_th' => 'ชัยนาท'],
            ['name_th' => 'ชัยภูมิ'],
            ['name_th' => 'ชุมพร'],
            ['name_th' => 'เชียงราย'],
            ['name_th' => 'เชียงใหม่'],
            ['name_th' => 'ตรัง'],
            ['name_th' => 'ตราด'],
            ['name_th' => 'ตาก'],
            ['name_th' => 'นครนายก'],
            ['name_th' => 'นครปฐม'],
            ['name_th' => 'นครพนม'],
            ['name_th' => 'นครราชสีมา'],
            ['name_th' => 'นครศรีธรรมราช'],
            ['name_th' => 'นครสวรรค์'],
            ['name_th' => 'นนทบุรี'],
            ['name_th' => 'นราธิวาส'],
            ['name_th' => 'น่าน'],
            ['name_th' => 'บึงกาฬ'],
            ['name_th' => 'บุรีรัมย์'],
            ['name_th' => 'ปทุมธานี'],
            ['name_th' => 'ประจวบคีรีขันธ์'],
            ['name_th' => 'ปราจีนบุรี'],
            ['name_th' => 'ปัตตานี'],
            ['name_th' => 'พระนครศรีอยุธยา'],
            ['name_th' => 'พะเยา'],
            ['name_th' => 'พังงา'],
            ['name_th' => 'พัทลุง'],
            ['name_th' => 'พิจิตร'],
            ['name_th' => 'พิษณุโลก'],
            ['name_th' => 'เพชรบุรี'],
            ['name_th' => 'เพชรบูรณ์'],
            ['name_th' => 'แพร่'],
            ['name_th' => 'ภูเก็ต'],
            ['name_th' => 'มหาสารคาม'],
            ['name_th' => 'มุกดาหาร'],
            ['name_th' => 'แม่ฮ่องสอน'],
            ['name_th' => 'ยโสธร'],
            ['name_th' => 'ยะลา'],
            ['name_th' => 'ร้อยเอ็ด'],
            ['name_th' => 'ระนอง'],
            ['name_th' => 'ระยอง'],
            ['name_th' => 'ราชบุรี'],
            ['name_th' => 'ลพบุรี'],
            ['name_th' => 'ลำปาง'],
            ['name_th' => 'ลำพูน'],
            ['name_th' => 'เลย'],
            ['name_th' => 'ศรีสะเกษ'],
            ['name_th' => 'สกลนคร'],
            ['name_th' => 'สงขลา'],
            ['name_th' => 'สตูล'],
            ['name_th' => 'สมุทรปราการ'],
            ['name_th' => 'สมุทรสงคราม'],
            ['name_th' => 'สมุทรสาคร'],
            ['name_th' => 'สระแก้ว'],
            ['name_th' => 'สระบุรี'],
            ['name_th' => 'สิงห์บุรี'],
            ['name_th' => 'สุโขทัย'],
            ['name_th' => 'สุพรรณบุรี'],
            ['name_th' => 'สุราษฎร์ธานี'],
            ['name_th' => 'สุรินทร์'],
            ['name_th' => 'หนองคาย'],
            ['name_th' => 'หนองบัวลำภู'],
            ['name_th' => 'อ่างทอง'],
            ['name_th' => 'อำนาจเจริญ'],
            ['name_th' => 'อุดรธานี'],
            ['name_th' => 'อุตรดิตถ์'],
            ['name_th' => 'อุทัยธานี'],
            ['name_th' => 'อุบลราชธานี'],
        ]);
    }
}