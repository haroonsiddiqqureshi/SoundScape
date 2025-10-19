<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist_Concert extends Model
{
    protected $fillable = [
        'artist_id',
        'concert_id',
    ];

    protected $casts = [
        'promoter_id' => 'integer',
        'concert_id' => 'integer',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}
