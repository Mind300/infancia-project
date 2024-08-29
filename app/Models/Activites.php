<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Activites extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'napping',
        'diaper',
        'potty',
        'toilet',
        'mood',
        'comment',
        'kid_id',
        'nursery_id',
    ];

    public function kid()
    {
        return $this->belongsTo(Kids::class, 'kid_id');
    }
}
