<?php

namespace Database\Factories;

use App\Dev\Factory\DisableCastingInFactory;
use App\Dev\FactoryExtended;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 * @method fakePrice
 */
class RecipeFactory extends Factory
{
    use DisableCastingInFactory;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'portions' => $this->faker->randomDigitNot(0),
            'price' => $this->faker->randomFloat(2,1,20),
            'menu_category_id' => MenuCategory::factory(),
            'costing_goal' => '0'
        ];
    }
}
