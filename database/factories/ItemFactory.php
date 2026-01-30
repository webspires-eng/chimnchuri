<?php

namespace Database\Factories;

use App\Models\ItemSize;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{

    public function withSizes(int $count = 3)
    {
        return $this->has(ItemSize::factory()->count($count), 'sizes');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "uuid" => Str::uuid()->toString(),
            'name' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
