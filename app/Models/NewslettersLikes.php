<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NewslettersLikes extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'newsletter_id',
        'user_id'
    ];

    public function newletters()
    {
        return $this->belongsTo(Newsletters::class, 'newsletter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}