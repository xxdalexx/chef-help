<?php

use App\Models\Recipe;

test('casts and relationships', function () {
    $recipe = Recipe::factory()
        ->has(\App\Models\RecipeItem::factory()->count(3), 'items')
        ->create();

    expect($recipe->price)->toBeMoney();
    expect($recipe->items)->toBeCollection();
});
