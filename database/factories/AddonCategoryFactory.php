<?php

namespace Database\Factories;

use App\Models\AddonCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddonCategory>
 */
class AddonCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AddonCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'uuid' => Str::uuid()->toString(),
            'name' => ucfirst($name),
            'description' => $this->faker->optional()->sentence(),
            'image' => null, 
            'is_active' => true,
        ];
    }
}
