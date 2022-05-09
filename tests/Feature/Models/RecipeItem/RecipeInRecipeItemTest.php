<?php

use App\Measurements\UsWeight;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\EachMeasurementSeeder;

it('can have a recipe as an ingredient', function () {

    $this->seed(EachMeasurementSeeder::class);
    $portionUnit = new stdClass();
    $portionUnit->value = 'portion';
    $recipe = Recipe::factory()->create();
    $recipeForIngredient = Recipe::factory()->create();
    $recipeItem = RecipeItem::create([
        'ingredient_id' => $recipeForIngredient->id,
        'ingredient_type' => Recipe::class,
        'quantity' => 1,
        'unit' => $portionUnit,
        'recipe_id' => $recipe->id,
        'cooked' => false,
        'cleaned' => false
    ]);
    $recipeItem->refresh();

    expect($recipeItem->getCost())->toBeMoney();

});


test('any recipe can be used with portion as unit', function () {

    $portionUnit = new stdClass();
    $portionUnit->value = 'portion';
    $this->seed([EachMeasurementSeeder::class, LobsterDishSeeder::class]);
    $lobster = Ingredient::first(); // $12/lb

    $recipeAsIngredient = Recipe::factory()->create(['portions' => 1]);
    RecipeItem::factory()->create([
        'recipe_id' => $recipeAsIngredient->id,
        'ingredient_id' => $lobster->id,
        'ingredient_type' => Ingredient::class,
        'cleaned' => false,
        'cooked' => false,
        'quantity' => 1,
        'unit' => UsWeight::lb
    ]);
    // $recipeAsIngredient has a cost of $12/portion

    $recipe = Recipe::factory()->create(['portions' => 1]);
    $recipeItem = RecipeItem::create([
        'recipe_id' => $recipe->id,
        'ingredient_id' => $recipeAsIngredient->id,
        'ingredient_type' => Recipe::class,
        'quantity' => 1,
        'unit' => $portionUnit,
        'cooked' => false,
        'cleaned' => false
    ]);
    $recipeItem->refresh();

    expect( $recipeItem->getCostAsString() )->toBe( '$12.00' );

});
