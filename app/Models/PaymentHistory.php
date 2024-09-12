<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_name',
        'transaction_id',
        'success',
        'amount',
        'card_token',
        'intial_payment',
        'next_payment',
        'saved_card',
        'user_id',
        'nursery_id'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'card_token',
    ];

    public function nursery()
    {
        return $this->belongsTo(Nurseries::class, 'nursery_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
