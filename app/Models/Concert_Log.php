<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert_Log extends Model
{
    use HasFactory;

    protected $table = 'concert_logs';

    public $timestamps = true;

    protected $fillable = [
        'admin_id',
        'promoter_id',
        'concert_id',
        'field_name',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'admin_id' => 'integer',
        'promoter_id' => 'integer',
        'concert_id' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
