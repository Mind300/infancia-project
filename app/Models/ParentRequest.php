<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ParentRequest extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'seen',
        'user_id',
        'recipient_id',
        'nursery_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function recipient()
    // {
    //     return $this->belongsTo(User::class, 'recipient_id');
    // }
}
