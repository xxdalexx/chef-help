<?php

namespace Database\Seeders;

use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RandomRecipeSeeder extends Seeder
{
    public function run()
    {
        $wine = Ingredient::create([
            'name' => 'Wine',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100,
        ]);

        AsPurchased::create([
            'ingredient_id' => $wine->id,
            'quantity'      => 750,
            'unit'          => MetricVolume::ml,
            'price'         => money(15)
        ]);

        $iceCream = Ingredient::create([
            'name' => 'Ice Cream',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100,
        ]);

        AsPurchased::create([
            'ingredient_id' => $iceCream->id,
            'quantity'      => 1,
            'unit'          => UsVolume::quart,
            'price'         => money(16)
        ]);

        $meat = Ingredient::create([
            'name' => 'Meat',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100,
        ]);

        AsPurchased::create([
            'ingredient_id' => $meat->id,
            'quantity'      => 1,
            'unit'          => UsWeight::lb,
            'price'         => money(16)
        ]);

        $flour = Ingredient::create([
            'name' => 'Flour',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100,
        ]);

        AsPurchased::create([
            'ingredient_id' => $flour->id,
            'quantity'      => 4,
            'unit'          => MetricWeight::kg,
            'price'         => money(26)
        ]);




        $recipe = Recipe::create([
            'name'     => 'Random Items',
            'portions' => 1,
            'price'    => money('10'),
        ]);



        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $wine->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => UsVolume::cup,
            'quantity'      => 1,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $iceCream->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => MetricVolume::liter,
            'quantity'      => 1,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $meat->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => MetricWeight::gram,
            'quantity'      => 500,
        ]);

        RecipeItem::create([
            'recipe_id'     => $recipe->id,
            'ingredient_id' => $flour->id,
            'cleaned'       => false,
            'cooked'        => false,
            'unit'          => UsWeight::lb,
            'quantity'      => 5,
        ]);

    }

}
