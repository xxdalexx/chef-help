<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CrossConversion>
 */
class CrossConversionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ingredient_id'   => Ingredient::factory(),
            'ingredient_type' => Ingredient::class,
            'quantity_one'    => $this->faker->numberBetween(1, 3),
            'quantity_two'    => $this->faker->numberBetween(1, 3),
            'unit_one'        => $this->fakeUnit(),
            'unit_two'        => $this->fakeUnit()
        ];
    }
}
