<?php

namespace Database\Seeders;

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LobsterDishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lobster = Ingredient::create([
            'name'          => 'Lobster',
            'cleaned_yield' => 80,
            'cooked_yield'  => 80
        ]);

        AsPurchased::create([
            'ingredient_id' => $lobster->id,
            'quantity'      => '1',
            'unit'          => UsWeight::lb,
            'price'         => money('12.00'),
        ]);

        $cream = Ingredient::create([
            'name'          => 'Heavy Cream',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::create([
            'ingredient_id' => $cream->id,
            'quantity'      => '1',
            'unit'          => UsVolume::quart,
            'price'         => money('6.42'),
        ]);

        $recipe = Recipe::create([
            'name'     => 'Lobster Dish',
            'portions' => 1,
            'price'    => money('35'),
        ]);

        $lobsterItem = RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $lobster->id,
            'cleaned'       => true,
            'cooked'        => false,
            'unit'          => UsWeight::oz,
            'quantity'      => 8,
        ]);

        $creamItem = RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $cream->id,
            'cleaned'       => false,
            'cooked'        => true,
            'unit'          => UsVolume::cup,
            'quantity'      => 1,
        ]);

    }
}
