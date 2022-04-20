<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use function Spatie\PestPluginTestTime\testTime;

test('relationships and casts', function () {

    $ingredient = Ingredient::factory()
        ->has(AsPurchased::factory())
        ->has(RecipeItem::factory()->count(3))
        ->create();

    expect($ingredient->asPurchased)->toBeInstanceOf(AsPurchased::class);
    expect($ingredient->recipeItems)->toBeCollection();

});


test('multiple asPurchased relationships', function () {

    $ingredient = Ingredient::factory()->has(AsPurchased::factory())->create();
    $initialAP = $ingredient->load('asPurchased')->asPurchased;
    testTime()->addDay();
    $newAP = AsPurchased::factory()->for($ingredient)->create([
        'unit' => $initialAP->unit,
        'quantity' => $initialAP->quantity,
        'price' => $initialAP->price->plus(1)
    ]);

    $ingredient->refresh();
    //asPurchased is HasOne, returns the newest record.
    expect($ingredient->asPurchased->is($newAP))->toBeTrue();
    //history is last 10 except for the newest.
    expect($ingredient->asPurchasedHistory->first()->is($initialAP));
    //all
    expect($ingredient->asPurchasedAll->count())->toBe(2);
});
