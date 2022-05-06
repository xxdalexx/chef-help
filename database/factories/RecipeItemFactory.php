<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeItem>
 * @method fakeUnit() Macro in appServiceProvider
 */
class RecipeItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'recipe_id'     => Recipe::factory(),
            'ingredient_id' => Ingredient::factory(),
            'ingredient_type' => Ingredient::class,
            'cleaned'       => $this->faker->boolean(),
            'cooked'        => $this->faker->boolean(),
            'unit'          => $this->fakeUnit(),
            'quantity'      => $this->faker->randomDigit()
        ];
    }
}
