<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'min_distance',
        'max_distance',
        'delivery_fee',
        'minimum_order_amount',
        'is_active',
    ];

    protected $casts = [
        'min_distance' => 'decimal:2',
        'max_distance' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Find the delivery zone for a given distance in miles.
     */
    public static function getZoneForDistance($distance)
    {
        return self::where('is_active', true)
            ->where('min_distance', '<=', $distance)
            ->where('max_distance', '>=', $distance)
            ->first();
    }
}
