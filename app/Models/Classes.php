<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Classes extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age_from',
        'age_to',
        'kids_count',
        'nursery_id',
    ];

    public function nursery()
    {
        return $this->hasOne(Nurseries::class, 'nursery_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function meals()
    {
        return $this->hasMany(Meals::class, 'class_id');
    }

    public function kids()
    {
        return $this->hasMany(Kids::class, 'class_id');
    }
    
    public function subjects()
    {
        return $this->hasMany(SubjectsClasses::class, 'class_id');
    }
}
