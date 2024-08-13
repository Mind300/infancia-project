<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Nurseries extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'province',
        'address',
        'branches_number',
        'classes_number',
        'kids_number',
        'employees_number',
        'about',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'nursery_id');
    }

    public function kids()
    {
        return $this->hasMany(Kids::class, 'nursery_id');
    }

    // public function employee()
    // {
    //     return $this->hasMany(Employee::class, 'nursery_id');
    // }

    // public function classes()
    // {
    //     return $this->hasMany(Classes::class, 'nursery_id');
    // }

    // public function newsletters()
    // {
    //     return $this->hasMany(Newslater::class, 'nursery_id');
    // }

    // public function subjects()
    // {
    //     return $this->hasMany(Subject::class, 'nursery_id');
    // }

    // public function schedule()
    // {
    //     return $this->hasMany(Schedule::class, 'nursery_id');
    // }

    // public function absense()
    // {
    //     return $this->hasMany(Absense::class, 'nursery_id');
    // }

}
