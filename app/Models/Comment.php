<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'admin_id', 'promoter_id', 'concert_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
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
}
