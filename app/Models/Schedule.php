<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Schedule extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'days',
        'class_id',
        'subject_id',
        'nursery_id',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_id');
    }

    public function nursery()
    {
        return $this->belongsTo(User::class, 'nursery_id');
    }

    public function subject()
    {
        return $this->hasMany(Subject::class, 'subject_id');
    }
}
