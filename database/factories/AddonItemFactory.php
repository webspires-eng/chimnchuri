<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddonItem>
 */
class AddonItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => ucfirst($name),
            'uuid' => Str::uuid()->toString(),
            'slug' => Str::slug($name),
            'description' => $this->faker->optional()->sentence(),

            'price' => $this->faker->numberBetween(0, 300),

            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
