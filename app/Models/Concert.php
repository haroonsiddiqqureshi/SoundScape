<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Concert extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'genre',
        'event_type',
        'venue_name',
        'province_id',
        'price_min',
        'price_max',
        'start_sale_date',
        'end_sale_date',
        'start_show_date',
        'start_show_time',
        'end_show_date',
        'end_show_time',
        'latitude',
        'longitude',
        'picture_url',
        'view_count',
        'like_count',
        'ticket_link',
        'origin',
        'admin_id',
        'promoter_id',
    ];

    protected $casts = [
        'admin_id' => 'integer',
        'promoter_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        // 'start_sale_date' => 'date:Y-m-d',
        // 'end_sale_date'   => 'date:Y-m-d',
        // 'start_show_date' => 'date:Y-m-d',
        // 'end_show_date'   => 'date:Y-m-d',
        'price_min' => 'integer',
        'price_max' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
    ];

    protected function startShowDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }
    
    /**
     * Get and format the end_show_date.
     */
    protected function endShowDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    /**
     * Get and format the start_sale_date.
     */
    protected function startSaleDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    /**
     * Get and format the end_sale_date.
     */
    protected function endSaleDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

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
