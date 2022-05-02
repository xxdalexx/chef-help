<?php

use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\RandomRecipeSeeder;
use function Spatie\PestPluginTestTime\testTime;


test('relationships and casts', function () {

    $item = RecipeItem::factory()->create();

    expect( $item->ingredient )->toBeInstanceOf( Ingredient::class );
    expect( $item->recipe )->toBeInstanceOf( Recipe::class );
    expect( $item->unit )->toBeInstanceOf( MeasurementEnum::class );

});


//Should figure out a better way to get ids.
it('has a calculated cost', function ($idOffset, $expectedCost) {
    $this->seed(LobsterDishSeeder::class);
    $id = RecipeItem::first()->id + $idOffset;

    /** @var RecipeItem $item */
    $item = RecipeItem::with('ingredient.asPurchased')->find($id);

    expect( $item->getCost() )->toBeMoney();
    expect( $item->getCostAsString() )->toBe( $expectedCost );

})->with([
    //Refresh database doesn't reset auto incrementing ids.
    [0, '$7.50'],  //Lobster
    [1, '$1.61'],  //Heavy Cream
    [2, '$0.50'], //Sesame Seed Oil
    [3, '$1.00']  //Imported Aged White Balsamic Vinegar
]);


it('returns 0.00 money object when cost cannot be calculated', function () {

    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->create();
    $item = RecipeItem::factory()->for($recipe)->for($ingredient)->create();

    expect( $item->getCostAsString() )->toBe( '$0.00' );

});


it('knows that it cannot calculate cost when ingredient is missing AP record', function () {

    //Create Recipe, Item, and an Ingredient with no AP Record.
    $recipe = Recipe::factory()->has(
        RecipeItem::factory()->has(
            Ingredient::factory()
        ), 'items'
    )->create();

    expect( RecipeItem::first()->canCalculateCost() )->toBeFalse();

});


it('can tell the reasoning for not calculating cost', function () {

    //Create Recipe, Item, and an Ingredient with no AP Record.
    $recipe = Recipe::factory()->has(
        RecipeItem::factory(['unit' => UsWeight::oz])->has(
            Ingredient::factory()
        ), 'items'
    )->create();

    $item = RecipeItem::first();
    expect( $item->canNotCalculateCostReason() )->toBe( 'No As Purchased Data' );

    AsPurchased::factory()->for($item->ingredient)->create(['unit' => UsVolume::floz]);
    $item->refresh();

    expect( $item->canNotCalculateCostReason() )->toBe( 'No Weight <-> Volume Conversion' );

});


it('knows that it cannot calculate cost when there is a weight volume mismatch without a crossconversion', function () {

    //Create Recipe, Item, and an Ingredient with no CrossConversion Record.
    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->has( AsPurchased::factory(['unit' => UsWeight::oz]) )->create();
    $item = RecipeItem::factory()->for($recipe)->for($ingredient)->create(['unit' => UsVolume::floz]);

    expect( $item->canCalculateCost() )->toBeFalse();
    expect( $item->getCostAsString() )->toBe('$0.00');

});


it('can calculate cost with a CrossConversion where the weight systems of AP and CrossConversion match', function () {

    $this->seed(RandomRecipeSeeder::class);
    // We take flour as purchased at $26/kg, using a cross type conversion that 128 grams = 1 cup
    // and find that 2 cups in a recipe would cost $1.67
    // Testing when the "left" side of the conversion are both metric weights.

    $ingredient = Ingredient::with(['asPurchased', 'crossConversions'])->whereName('Flour')->first();
    $recipe = Recipe::factory()->create();
    $recipeItem = RecipeItem::factory()->for($ingredient)->for($recipe)->create([
        'quantity' => 2,
        'unit' => UsVolume::cup
    ]);

    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$1.67' );

});

it('can calculate cost with a CrossConversion where the weight system of AP is metric and cross', function () {

    // We take flour as purchased at $26/kg, using a cross type conversion that 4.5oz = 1 cup
    // and find that 2 cups in a recipe would cost $1.67
    // Testing when the "left" side of the conversion has a metric AP and US CrossConversion weights.
    $this->seed(RandomRecipeSeeder::class);

    // Change the existing one to match test.
    $conversion = CrossConversion::first();
    $conversion->update([
        'unit_one' => UsWeight::oz,
        'quantity_one' => '4.5'
    ]);

    $ingredient = Ingredient::with(['asPurchased', 'crossConversions'])->whereName('Flour')->first();
    $recipe = Recipe::factory()->create();
    $recipeItem = RecipeItem::factory()->for($ingredient)->for($recipe)->create([
        'quantity' => 2,
        'unit' => UsVolume::cup
    ]);

    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$1.66' );

});


it('calculates cost with CrossConversion starting with volume', function () {

    $this->seed(LobsterDishSeeder::class);
    // We take heavy cream purchased at $6.42 a quart, using a cross type conversion of 1 cup = 8.15oz
    // and find that 4oz cost $0.76

    // This has a variance of +$0.03 due to the number of times rounding up is used in the math chain.
    // Constant rounding up was done on purpose and will be documented to the user why this is good for their
    // end of the month numbers.

    // Testing the "left" side with volumes

    $ingredient = Ingredient::with(['asPurchased', 'crossConversions'])->whereName('Heavy Cream')->first();
    $recipe = Recipe::factory()->create();
    $recipeItem = RecipeItem::factory()->for($ingredient)->for($recipe)->create([
        'quantity' => 4,
        'unit' => UsWeight::oz
    ]);

    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$0.79' );

});


it('calculates cost with CrossConversion and system conversion at the end', function () {

    $this->seed(LobsterDishSeeder::class);
    // We take heavy cream purchased at $6.42 a quart, using a cross type conversion of 1 cup = 8.15oz
    // and find that 114grams cost $0.76

    // This has a variance of +$0.04 due to the number of times rounding up is used in the math chain.
    // Constant rounding up was done on purpose and will be documented to the user why this is good for their
    // end of the month numbers.

    // Testing the "right" side when systems are mixed.

    $ingredient = Ingredient::with(['asPurchased', 'crossConversions'])->whereName('Heavy Cream')->first();
    $recipe = Recipe::factory()->create();
    $recipeItem = RecipeItem::factory()->for($ingredient)->for($recipe)->create([
        'quantity' => 114,
        'unit' => MetricWeight::gram
    ]);

    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$0.80' );

});


it('calculates cost with CrossConversion and system conversion all around', function () {

    // We take flour as purchased at $26/kg, using a cross type conversion that 4.5oz = 1 cup
    // and find that 1 liter in a recipe would cost $3.51
    // Testing when the "left" and "right" sides of the conversion has a metric AP and US CrossConversion weights.
    $this->seed(RandomRecipeSeeder::class);

    // Change the existing one to match test.
    $conversion = CrossConversion::first();
    $conversion->update([
        'unit_one' => UsWeight::oz,
        'quantity_one' => '4.5'
    ]);

    $ingredient = Ingredient::with(['asPurchased', 'crossConversions'])->whereName('Flour')->first();
    $recipe = Recipe::factory()->create();
    $recipeItem = RecipeItem::factory()->for($ingredient)->for($recipe)->create([
        'quantity' => 1,
        'unit' => MetricVolume::liter
    ]);

    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$3.51' );

});


it('calculates a cost with a US Metric conversion', function ($idOffset, $expectedCost) {

    $this->seed(RandomRecipeSeeder::class);
    $id = RecipeItem::first()->id + $idOffset;

    $item = RecipeItem::find($id);

    expect( $item->getCostAsString() )->toBe( $expectedCost );

})->with([
    [0, '$4.74'],  //AP: Metric Volume, RI: US Volume
    [1, '$16.91'], //AP: US Volume, RI: Metric Volume
    [2, '$17.64'], //AP: US Weight, RI: Metric Weight
    [3, '$14.75'], //AP: Metric Weight, RI: US Weight
]);


test('get cost caching through attribute accessor', function () {

    $this->seed(LobsterDishSeeder::class);
    $item = RecipeItem::first();
    testTime()->addMinutes(5);

    expect(
        Cache::has( $item->costCacheKey() )
    )->toBeFalse();
    $originalCost = $item->cost;

    expect(
        Cache::has( $item->costCacheKey() )
    )->toBeTrue();
    $originalKey = $item->costCacheKey();

    $item->update(['quantity' => 100]);

    expect( $item->cost )->not->toBe( $originalCost );
    expect( $item->costCacheKey() )->not->toBe( $originalKey );

});


test('cost cache is updated when the ap price is updated', function () {

    $this->seed(LobsterDishSeeder::class);
    testTime()->addDay();
    /** @var RecipeItem $item */
    $item         = RecipeItem::with('ingredient.asPurchased')->first();
    $originalCost = $item->getCostAsString();
    $originalKey  = $item->costCacheKey();

    $ap = $item->ingredient->asPurchased;
    $ap->update(['price' => money(13)]);
    $item->refresh();

    expect( $item->costCacheKey() )->not->toBe( $originalKey );
    expect( $item->getCostAsString() )->not->toBe( $originalCost );

});
