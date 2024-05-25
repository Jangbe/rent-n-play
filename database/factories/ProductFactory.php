<?php

namespace Database\Factories;

use App\Models\Category;
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
            'category_id' => $this->faker->randomElement(Category::pluck('id')->toArray()),
            'picture' => $this->faker->imageUrl(),
            'name' => $this->faker->text(50),
            'description' => $this->faker->sentences(6, true),
            'price' => $this->faker->numberBetween(5000, 50000),
            'amount' => $this->faker->randomDigit(),
        ];
    }
}
