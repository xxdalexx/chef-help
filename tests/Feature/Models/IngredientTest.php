<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\RecipeItem;

test('relationships and casts', function () {

    $ingredient = Ingredient::factory()
        ->has(AsPurchased::factory(), 'asPurchased')
        ->has(RecipeItem::factory()->count(3), 'recipeItems')
        ->create();

    expect($ingredient->asPurchased)->toBeInstanceOf(AsPurchased::class);
    expect($ingredient->recipeItems)->toBeCollection();

});
