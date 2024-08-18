<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Newsletters extends Model implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

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

    // Spatie Media Library Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('Newsletters')->singleFile();
    }
}
