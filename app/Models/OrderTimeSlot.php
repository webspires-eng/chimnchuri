<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTimeSlot extends Model
{
    protected $fillable = [
        'order_id',
        'time_slot_id',
        'start_time',
        'end_time',
        'capacity',
    ];

    protected $casts = [
        'start_time' => 'datetime:h:i A',
        'end_time'   => 'datetime:h:i A',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
