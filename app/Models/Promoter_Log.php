<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promoter_Log extends Model
{
    use HasFactory;

    protected $table = 'promoter_logs';

    protected $fillable = [
        'promoter_id',
        'new_value',
        'field_name',
        'old_value',
    ];

    protected $casts = [
        'promoter_id' => 'integer',
    ];

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }
}
