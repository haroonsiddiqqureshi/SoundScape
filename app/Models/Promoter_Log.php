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
        'field_name',
        'old_value',
        'new_value',
    ];

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }
}
