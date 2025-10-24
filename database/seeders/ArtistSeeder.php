<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::create([
            'name' => env('ARTIST_NAME'),
            'bio' => env('ARTIST_BIO'),
            'picture_url' => env('ARTIST_PICTURE_URL'),
        ]);
    }
}
