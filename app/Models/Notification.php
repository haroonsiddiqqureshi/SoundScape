<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'follow_id',
        'concert_log_id',
        'message',
        'type',
        'is_sent',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
    ];

    public function follow()
    {
        return $this->belongsTo(Follow::class);
    }

    public function concertLog()
    {
        return $this->belongsTo(Concert_Log::class);
    }
}
