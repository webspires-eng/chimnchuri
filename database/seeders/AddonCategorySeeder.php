<?php

namespace Database\Seeders;

use App\Models\AddonCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddonCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // $categories = [
        //     ['name' => 'Toppings'],
        //     ['name' => 'Sauces'],
        //     ['name' => 'Drinks'],
        //     ['name' => 'Extras'],
        // ];

        // foreach ($categories as $category) {
        //     AddonCategory::factory()->create($category);
        // }
        
        AddonCategory::factory()->count(5)->create();
    }
}
