<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    protected $fillable = [
        "category_id",
        "item_id"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
