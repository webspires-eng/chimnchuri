<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        "uuid",
        "name",
        "slug",
        "short_description",
        "description",
        "label",
        "is_taxable",
        "is_discountable",
        "is_active",
        "is_featured",
        "is_popular",
        "sort_order",
    ];


    public function sizes()
    {
        return $this->hasMany(ItemSize::class);
    }


    public function addonGroups()
    {
        return $this->hasMany(AddonGroup::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }
}
