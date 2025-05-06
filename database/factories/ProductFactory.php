<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'product_category_id' => fake()->numberBetween(1, 6), // 6 categories
            'manufacturer' => fake()->company(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1, 1000),
        ];
    }
}
