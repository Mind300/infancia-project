<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'service',
        'is_paid',
        'paid_at',
        'kid_id',
        'nursery_id',
    ];

    public function nursery()
    {
        return $this->hasMany(Nurseries::class, 'nursery_id');
    }

    public function kids()
    {
        return $this->belongsTo(Kids::class, 'kid_id');
    }
}
