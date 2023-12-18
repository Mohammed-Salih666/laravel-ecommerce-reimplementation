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
            'name_en' => $this->faker->firstNameMale(), 
            'name_ar' => "غير مهم",
            'price' => $this->faker->randomFloat(),
            'old_price' => $this->faker->randomFloat(), 
            'description_en' => $this->faker->paragraph(),
            'description_ar' => 'غير مهم اخر', 
            'slug' => $this->faker->word(), 
            'is_active' => true
        ];
    }
}
