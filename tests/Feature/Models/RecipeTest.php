<?php

use App\Models\Recipe;

test('casts work', function () {
    $recipe = Recipe::factory()->create();
    expect($recipe->price)->toBeMoney();
});
