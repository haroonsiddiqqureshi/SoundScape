<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScraperJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_type',
        'target_website',
        'status',
        'progress',
        'results',
        'error_message'
    ];

    protected $casts = [
        'results' => 'array',
    ];
}