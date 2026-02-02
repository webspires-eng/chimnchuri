<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'code',
        'driver_class',
        'is_active'
    ];

    public function setting()
    {
        return $this->hasOne(PaymentGatewaySetting::class);
    }
}
