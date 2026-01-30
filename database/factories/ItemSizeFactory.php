<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemSize>
 */
class ItemSizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "uuid" => Str::uuid()->toString(),
            'name' =>  $this->faker->unique()->word(),
            'price' => $this->faker->numberBetween(100, 500),
            'discount' => $this->faker->numberBetween(0, 50),
            'discount_type' => $this->faker->randomElement(['fixed', 'percentage']),
            'is_active' => true,
        ];
    }
}
