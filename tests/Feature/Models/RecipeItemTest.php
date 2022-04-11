<?php

use App\Measurements\MeasurementEnum;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;

test('relationships and casts', function () {
    $item = RecipeItem::factory()->create();

    expect($item->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($item->recipe)->toBeInstanceOf(Recipe::class);
    expect($item->unit)->toBeInstanceOf(MeasurementEnum::class);
});
