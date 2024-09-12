<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\Api\Kids\KidsController;
use App\Http\Controllers\Api\Nurseries\NurseriesController;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject, HasMedia, LaratrustUser
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRolesAndPermissions, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // --------------------- Spatie --------------------- //
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    // --------------------- Relations --------------------- //
    public function nursery()
    {
        return $this->hasOne(Nurseries::class, 'user_id');
    }

    public function parent()
    {
        return $this->hasOne(Parents::class, 'user_id');
    }

    public function newsliks()
    {
        return $this->hasOne(NewslettersLikes::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function review()
    {
        return $this->hasOne(Reviews::class, 'user_id');
    }

    // --------------------- Observe --------------------- //
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Check the context flag   
            if (KidsController::$creatingKid) {
                $model->password  = $model->phone . '@';
            }
            if (NurseriesController::$creatingNursery) {
                $model->password = Str::random(5);
            }
        });
    }
}
