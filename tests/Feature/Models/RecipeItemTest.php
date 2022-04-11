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

it('has a calculated cost', function () {

    $this->seed(LobsterDishSeeder::class);

    $item = RecipeItem::with('ingredient.asPurchased')->first();

    expect($item->getCost());
    dd($item);
});
