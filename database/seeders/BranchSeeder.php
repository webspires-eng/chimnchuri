<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    public function run()
    {
        DB::table('branches')->insert([
            [
                'name' => 'Main Branch',
                'slug' => Str::slug('Main Branch'),
                'address' => 'Main Market, Lahore, Pakistan',
                'phone' => '03001234567',
                'email' => 'main@restaurant.com',

                'latitude' => 31.5204,
                'longitude' => 74.3587,

                'currency_code' => 'PKR',
                'currency_symbol' => 'Rs.',

                'is_online_enabled' => true,
                'is_cod_enabled' => true,
                'is_delivery_enabled' => true,
                'is_pickup_enabled' => true,

                'delivery_fee' => 50,
                'min_order_amount' => 500,
                'estimated_delivery_time' => 35,

                'tax_percentage' => 5,

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Johar Town Branch',
                'slug' => Str::slug('Johar Town Branch'),
                'address' => 'Johar Town, Lahore, Pakistan',
                'phone' => '03007654321',
                'email' => 'johar@restaurant.com',

                'latitude' => 31.4697,
                'longitude' => 74.2728,

                'currency_code' => 'PKR',
                'currency_symbol' => 'Rs.',

                'is_online_enabled' => true,
                'is_cod_enabled' => true,
                'is_delivery_enabled' => true,
                'is_pickup_enabled' => true,

                'delivery_fee' => 70,
                'min_order_amount' => 700,
                'estimated_delivery_time' => 40,

                'tax_percentage' => 5,

                'is_active' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
