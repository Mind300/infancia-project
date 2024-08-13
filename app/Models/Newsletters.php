<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Newsletters extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'likes_counts',
        'nursery_id',
    ];

    public function newslikes()
    {
        return $this->hasMany(NewslettersLikes::class, 'newsletter_id');
    }

    public function nursery()
    {
        return $this->belongsTo(Nurseries::class, 'nursery_id');
    }
}
