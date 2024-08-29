<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Reviews extends Model
{
    use HasFactory, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'review',
        'rate',
        'user_id',
        'nursery_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
