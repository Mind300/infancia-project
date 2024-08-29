<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SubjectsClasses extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nursery_id',
        'class_id',
        'subject_id',
    ];

      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}
