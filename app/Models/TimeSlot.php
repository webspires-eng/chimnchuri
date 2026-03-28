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

    public function getBookedCapacityAttribute()
    {
        return \App\Models\OrderTimeSlot::where('time_slot_id', $this->id)
            ->whereHas('order', function ($q) {
                $q->where('order_status', '!=', 'cancelled')
                  ->where(function ($sub) {
                      $sub->where('payment_method', 'cod')
                          ->orWhere('payment_status', 'paid');
                  });
            })->sum('capacity');
    }

    public function getAvailableCapacityAttribute()
    {
        return max(0, $this->max_capacity - $this->booked_capacity);
    }
}
