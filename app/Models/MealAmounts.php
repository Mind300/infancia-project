<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MealAmounts extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'meal_id',
        'kid_id',
    ];
    
     protected $hidden = [
         'kid_id',
        'created_at',
        'updated_at',
    ];

    public function meal()
    {
        return $this->belongsTo(Meals::class, 'meal_id');
    }

    public function kids()
    {
        return $this->belongsToMany(Kids::class, 'kids_id');
    }
}
