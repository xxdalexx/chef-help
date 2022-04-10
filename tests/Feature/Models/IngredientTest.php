<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;

it('has an as purchased relationship', function () {
    $ingredient = Ingredient::factory()->has(AsPurchased::factory(), 'asPurchased')->create();
    expect($ingredient->asPurchased)->toBeInstanceOf(AsPurchased::class);
});
