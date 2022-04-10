<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsPurchased>
 * @method fakeUnit
 * @method fakePrice
 */
class AsPurchasedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ingredient_id' => Ingredient::factory(),
            'quantity' => $this->faker->numberBetween(1,16),
            'unit' => $this->fakeUnit(),
            'price' => $this->fakePrice()
        ];
    }
}
