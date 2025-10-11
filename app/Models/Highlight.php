<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $fillable = [
        'title',
        'picture_url',
        'description',
        'concert_id',
        'link',
        'is_active',
    ];

    protected $casts = [
        'concert_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}
