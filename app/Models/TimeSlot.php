<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'order_type',
        'order_date_id',
        'start_time',
        'end_time',
        'max_capacity',
        'is_active'
    ];

    public function orderDate()
    {
        return $this->belongsTo(OrderDate::class);
    }
}
