<?php

namespace Database\Seeders;

use App\Measurements\MetricVolume;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use App\Models\MenuCategory;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Database\Seeder;

class MarinaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $romaTomatoes = Ingredient::firstOrCreate([
            'name'          => 'Roma Tomatoes',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $romaTomatoes->id,
            'quantity'      => '25',
            'unit'          => 'lb',
            'price'         => '11.20',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $garlicPeeledAndMinced = Ingredient::firstOrCreate([
            'name'          => 'Garlic Peeled and Minced',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $garlicPeeledAndMinced->id,
            'quantity'      => '5',
            'unit'          => 'lb',
            'price'         => '13.80',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $oliveOil = Ingredient::firstOrCreate([
            'name'          => 'Olive Oil',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $oliveOil->id,
            'quantity'      => '2',
            'unit'          => 'liter',
            'price'         => '17.70',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $parsley = Ingredient::firstOrCreate([
            'name'          => 'Parsley',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $parsley->id,
            'quantity'      => '1',
            'unit'          => 'bunch',
            'price'         => '1.00',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $oregano = Ingredient::firstOrCreate([
            'name'          => 'Oregano',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $oregano->id,
            'quantity'      => '4',
            'unit'          => 'oz',
            'price'         => '2.99',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $kosherSalt = Ingredient::firstOrCreate([
            'name'          => 'Kosher Salt',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $kosherSalt->id,
            'quantity'      => '3',
            'unit'          => 'lb',
            'price'         => '8.99',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $yellowOnion = Ingredient::firstOrCreate([
            'name'          => 'Yellow Onion',
            'cleaned_yield' => 65,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $yellowOnion->id,
            'quantity'      => '50',
            'unit'          => 'lb',
            'price'         => '25.10',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $cookingRedWine = Ingredient::firstOrCreate([
            'name'          => 'Cooking Red Wine',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $cookingRedWine->id,
            'quantity'      => '5',
            'unit'          => 'liter',
            'price'         => '18.99',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $crushedRedPepper = Ingredient::firstOrCreate([
            'name'          => 'Crushed Red Pepper',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $crushedRedPepper->id,
            'quantity'      => '4',
            'unit'          => 'lb',
            'price'         => '15.75',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        $sugar = Ingredient::firstOrCreate([
            'name'          => 'Sugar',
            'cleaned_yield' => 100,
            'cooked_yield'  => 100
        ]);

        AsPurchased::insertOrIgnore([
            'ingredient_id' => $sugar->id,
            'quantity'      => '50',
            'unit'          => 'lb',
            'price'         => '35.44',
            'created_at'    => '2022-05-10 11:11:16'
        ]);

        CrossConversion::insert([
            'ingredient_id'   => $sugar->id,
            'ingredient_type' => 'App\Models\Ingredient',
            'quantity_one'    => '1',
            'unit_one'        => 'cup',
            'quantity_two'    => '7.1',
            'unit_two'        => 'oz',
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        $recipe = Recipe::firstOrCreate([
            'name'             => 'Marinara',
            'portions'         => 1,
        ]);


        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $romaTomatoes->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'lb',
            'quantity'        => 12,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $garlicPeeledAndMinced->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'oz',
            'quantity'        => 2,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $oliveOil->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'floz',
            'quantity'        => 3,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $parsley->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'bunch',
            'quantity'        => 0.25,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $oregano->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'oz',
            'quantity'        => 1,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $kosherSalt->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'oz',
            'quantity'        => 4,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $yellowOnion->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'oz',
            'quantity'        => 8,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $cookingRedWine->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'cup',
            'quantity'        => 1,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $crushedRedPepper->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'oz',
            'quantity'        => 3,
            'created_at'      => '2022-05-10 11:11:16'
        ]);

        RecipeItem::insert([
            'recipe_id'       => $recipe->id,
            'ingredient_id'   => $sugar->id,
            'ingredient_type' => Ingredient::class,
            'cleaned'         => false,
            'cooked'          => false,
            'unit'            => 'cup',
            'quantity'        => 1,
            'created_at'      => '2022-05-10 11:11:16'
        ]);
    }
}
