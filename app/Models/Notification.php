<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'type',
        'is_sent',
        'follow_id',
        'concert_log_id',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'follow_id' => 'integer',
        'concert_log_id' => 'integer',
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
