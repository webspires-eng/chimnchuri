<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "uuid",
        "name",
        "slug",
        "description",
        "image",
        "price",
        "sort_order",
        "is_active",
    ];

}
