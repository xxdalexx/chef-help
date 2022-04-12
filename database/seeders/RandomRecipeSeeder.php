<?php

namespace Database\Seeders;

use App\Measurements\MetricVolume;
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

    }

}
