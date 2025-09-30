<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => env('USER_NAME'),
            'email' => env('USER_EMAIL'),
            'password' => Hash::make(env('USER_PASSWORD')),
            'phone' => env('USER_PHONE'),
        ]);
    }
}
