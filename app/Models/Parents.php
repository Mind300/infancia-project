<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Parents extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'father_name',
        'father_mobile',
        'father_job',
        'mother_name',
        'mother_mobile',
        'mother_job',
        'nursery_id',
        'user_id'
    ];

    // User Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Nursery Relations
    public function nursery()
    {
        return $this->belongsTo(Nurseries::class, 'nursery_id');
    }

    // Kids Relations
    public function kids()
    {
        return $this->hasMany(Kids::class, 'parent_id');
    }
}
