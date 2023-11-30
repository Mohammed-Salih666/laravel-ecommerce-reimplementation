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
            'name' => $this->faker->firstNameMale(), 
            'price' => $this->faker->randomFloat(),
            'old_price' => $this->faker->randomFloat(), 
            'description' => $this->faker->paragraph(),
            'slug' => $this->faker->word(), 
            'is_active' => true
        ];
    }
}
