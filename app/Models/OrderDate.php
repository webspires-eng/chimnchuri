<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDate extends Model
{
    protected $fillable = [
        'date',
        'day_name',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
