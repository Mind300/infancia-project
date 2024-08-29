<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Kids extends Model implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kid_name',
        'gender',
        'birthdate',
        'city',
        'has_medical_case',
        'parent_id',
        'nursery_id',
        'class_id',
    ];

    public function nursery()
    {
        return $this->belongsTo(Nurseries::class, 'nursery_id');
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function meal_amount()
    {
        return $this->hasMany(MealAmounts::class, 'kid_id');
    }

    public function absent()
    {
        return $this->hasOne(Absence::class, 'kid_id');
    }

    public function activites()
    {
        return $this->hasMany(Activites::class, 'kid_id');
    }
    
    public function payment()
    {
        return $this->belongsTo(PaymentRequest::class, 'kid_id');
    }

    // Spatie Media Library Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('Kids')->singleFile();
    }
}
