<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;

it('belongs to an ingredient', function () {
    $ap = AsPurchased::factory()->make();
    expect($ap->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($ap->unit)->not->toBeNull();
    expect($ap->price)->not->toBeNull();
});
