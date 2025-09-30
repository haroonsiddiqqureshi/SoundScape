<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Promoter extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'user_id',
        'fullname',
        'business_category',
        'proof_of_identity',
        'logo_url',
        'context_info',
        'bio',
    ];

    protected $hidden = [
        'verified',
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(Promoter_Log::class);
    }
}
