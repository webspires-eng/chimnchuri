<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $food = Category::create([
            'uuid' => Str::uuid(),
            'name' => 'Food',
            'slug' => 'food',
            'level' => 0,
            'sort_order' => 1,
        ]);

        $pizza = Category::create([
            'uuid' => Str::uuid(),
            'parent_id' => $food->id,
            'level' => 1,
            'name' => 'Pizza',
            'slug' => 'pizza',
            'sort_order' => 1,
        ]);

        Category::create([
            'uuid' => Str::uuid(),
            'parent_id' => $pizza->id,
            'level' => 2,
            'name' => 'Stuffed Crust',
            'slug' => 'stuffed-crust',
            'sort_order' => 1,
        ]);
    }
}
