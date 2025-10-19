<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'picture_url',
    ];

    public function concerts()
    {
        return $this->belongsToMany(Concert::class, 'artist_concerts');
    }
}
