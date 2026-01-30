<?php

namespace App\Models;

use App\Services\Api\V1\Admin\AddonCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonCategory extends Model
{

    use SoftDeletes, HasFactory;


    protected $fillable = [
        "uuid",
        "name",
        "description",
        "image",
        "is_active",
    ];

}
