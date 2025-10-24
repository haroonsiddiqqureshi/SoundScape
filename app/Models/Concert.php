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
        'genre',
        'event_type',
        'venue_name',
        'province_id',
        'price_min',
        'price_max',
        'start_show_date',
        'start_show_time',
        'end_show_date',
        'end_show_time',
        'start_sale_date',
        'end_sale_date',
        'latitude',
        'longitude',
        'picture_url',
        'view_count',
        'like_count',
        'ticket_link',
        'admin_id',
        'promoter_id',
    ];

    protected $casts = [
        'admin_id' => 'integer',
        'promoter_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'start_show_date' => 'date',
        'end_show_date' => 'date',
        'start_sale_date' => 'date',
        'end_sale_date' => 'date',
        'price_min' => 'integer',
        'price_max' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'artist_concerts');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
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
