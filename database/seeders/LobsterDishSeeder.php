<?php

namespace Database\Seeders;

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\MenuCategory;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Database\Seeder;

class LobsterDishSeeder extends Seeder
{
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

        $oil = Ingredient::create([
            'name'          => 'Sesame Seed Oil',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::create([
            'ingredient_id' => $oil->id,
            'quantity'      => 10,
            'unit'          => UsVolume::floz,
            'price'         => money('10.00')
        ]);

        $vin = Ingredient::create([
            'name'          => 'Imported Aged White Balsamic Vinegar',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::create([
            'ingredient_id' => $vin->id,
            'quantity'      => 12,
            'unit'          => UsVolume::floz,
            'price'         => money('72.00')
        ]);



        $entreeCategory = MenuCategory::create([
            'name' => 'Entrees',
            'costing_goal' => '28'
        ]);

        $recipe = Recipe::create([
            'name'     => 'Lobster Dish',
            'portions' => 2,
            'price'    => money('18'),
            'menu_category_id' => $entreeCategory->id
        ]);



        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $lobster->id,
            'cleaned'       => true,
            'cooked'        => false,
            'unit'          => UsWeight::oz,
            'quantity'      => 8,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $cream->id,
            'cleaned'       => false,
            'cooked'        => true,
            'unit'          => UsVolume::cup,
            'quantity'      => 1,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $oil->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => UsVolume::tbsp,
            'quantity'      => 1,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $vin->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => UsVolume::tsp,
            'quantity'      => 1,
        ]);

    }
}
