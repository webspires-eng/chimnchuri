<?php

namespace Database\Seeders;

use App\Models\AddonItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddonItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AddonItem::factory()->count(15)->create();

    }
}
