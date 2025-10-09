<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'venue_name',
        'city',
        'latitude',
        'longitude',
        'concert_datetime',
        'price',
        'picture_url',
        'ticket_link',
        'admin_id',
        'promoter_id',
    ];

    protected $casts = [
        'admin_id' => 'integer',
        'promoter_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'concert_datetime' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows');
    }

    public function logs()
    {
        return $this->hasMany(Concert_Log::class);
    }
}
