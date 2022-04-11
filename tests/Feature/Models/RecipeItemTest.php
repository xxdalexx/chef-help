<?php

use App\Measurements\MeasurementEnum;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;

test('relationships and casts', function () {

    $item = RecipeItem::factory()->create();

    expect($item->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($item->recipe)->toBeInstanceOf(Recipe::class);
    expect($item->unit)->toBeInstanceOf(MeasurementEnum::class);

});

//no account for yield
it('has a calculated cost', function ($id, $expectedCost) {

    $this->seed(LobsterDishSeeder::class);

    $item = RecipeItem::withFullIngredientRelation()->find($id);

    expect($item->getCost())->toBeMoney();
    expect($item->getCostAsString())->toBe($expectedCost);

})->with([
    [1, '$6.00'],
    [2, '$1.61']
]);
