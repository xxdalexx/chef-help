<?php

use App\Measurements\MeasurementEnum;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\RandomRecipeSeeder;

test('relationships and casts', function () {

    $item = RecipeItem::factory()->create();

    expect($item->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($item->recipe)->toBeInstanceOf(Recipe::class);
    expect($item->unit)->toBeInstanceOf(MeasurementEnum::class);

});


it('has a calculated cost', function ($id, $expectedCost) {

    $this->seed(LobsterDishSeeder::class);

    $item = RecipeItem::withFullIngredientRelation()->find($id);

    expect($item->getCost())->toBeMoney();
    expect($item->getCostAsString())->toBe($expectedCost);

})->with([
    [1, '$7.50'],
    [2, '$1.61'],
    [3, '$0.50'],
    [4, '$1.00']
]);


it('throws an exception', function () {

    $this->seed(RandomRecipeSeeder::class);
    $this->expectException(Exception::class);

    $item = RecipeItem::first();
    $item->unit = UsWeight::oz;

    $item->getCost();
});


it('calculates a cost with a US Metric conversion', function ($id, $expectedCost) {

    $this->seed(RandomRecipeSeeder::class);

    $item = RecipeItem::find($id);

    expect($item->getCostAsString())->toBe($expectedCost);

})->with([
    [1, '$4.74'],  //AP: Metric Volume, RI: US Volume
    [2, '$16.91'], //AP: US Volume, RI: Metric Volume
    [3, '$17.64'], //AP: US Weight, RI: Metric Weight
    [4, '$14.75'], //AP: Metric Weight, RI: US Weight
]);
