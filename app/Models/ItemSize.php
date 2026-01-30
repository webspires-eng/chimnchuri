<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSize extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        "uuid",
        "item_id",
        "name",
        "price",
        "discount",
        "discount_type",
        "size_image",
        "serves",
        "is_active",
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
