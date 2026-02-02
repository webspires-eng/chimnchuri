<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySetting extends Model
{
    protected $fillable = [
        'payment_gateway_id',
        'config',
        'currency',
        'is_enabled'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
