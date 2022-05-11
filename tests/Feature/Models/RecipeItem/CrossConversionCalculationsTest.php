<?php

use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\CrossConversion;
use App\Models\EachMeasurement;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\EachMeasurementSeeder;
use Database\Seeders\RandomRecipeSeeder;

it('calculates cost with a CrossConversion where the weight systems of AP and CrossConversion match', function () {

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


it('calculates cost with a CrossConversion where the weight system of AP is metric and cross', function () {

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


it('calculates cost with CrossConversion using OtherMeasurement in RecipeItem', function () {

    $this->seed(EachMeasurementSeeder::class);
    $eachMeasurement = new stdClass();
    $eachMeasurement->value = 'each';
    $shrimp = Ingredient::factory()->create([
        'name' => 'Shrimp',
    ]);
    AsPurchased::factory()->for($shrimp)->create([
        'price' => 10,
        'quantity' => 1,
        'unit' => 'lb'
    ]);
    $recipe = Recipe::factory()->create();
    RecipeItem::factory()->for($shrimp)->for($recipe)->create([
        'unit' => $eachMeasurement,
        'quantity' => 1,
        'cleaned' => false,
        'cooked' => false
    ]);
    CrossConversion::factory()->for($shrimp)->create([
        'quantity_one' => 1,
        'unit_one' => UsWeight::lb,
        'quantity_two' => 10,
        'unit_two' => $eachMeasurement
    ]);
    /** @var RecipeItem $recipe */
    $recipe = RecipeItem::with(['ingredient.asPurchased', 'ingredient.crossConversions'])->first();

    expect($recipe->getCostAsString())->toBe('$1.00');

});


it('calculates cost with CrossConversion using OtherMeasurement in AsPurchased', function () {

    $this->seed(EachMeasurementSeeder::class);
    $eachMeasurement = new stdClass();
    $eachMeasurement->value = 'each';
    $shrimp = Ingredient::factory()->create([
        'name' => 'Shrimp',
    ]);
    AsPurchased::factory()->for($shrimp)->create([
        'price' => 32,
        'quantity' => 16,
        'unit' => 'each'
    ]);
    $recipe = Recipe::factory()->create();
    RecipeItem::factory()->for($shrimp)->for($recipe)->create([
        'quantity' => 1,
        'unit' => UsWeight::oz,
        'cleaned' => false,
        'cooked' => false
    ]);
    CrossConversion::factory()->for($shrimp)->create([
        'quantity_one' => 16,
        'unit_one' => $eachMeasurement,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb,
    ]);
    /** @var RecipeItem $recipe */
    $recipe = RecipeItem::with(['ingredient.asPurchased', 'ingredient.crossConversions'])->first();

    expect($recipe->getCostAsString())->toBe('$2.00');

});


it('calculates cost with CrossConversion with two each types representing different units', function () {

    // Set up an asPurchased by 'bunch', used by 'sprig',
    // using a conversion of 1 bunch = 20 sprigs.
    $this->seed(EachMeasurementSeeder::class);
    $bunchMeasurement = new stdClass();
    $bunchMeasurement->value = 'bunch';
    $sprigMeasurement = new stdClass();
    $sprigMeasurement->value = 'sprig';
    $ingredient = Ingredient::factory()->create();
    AsPurchased::factory()->for($ingredient)->create([
        'quantity' => 1,
        'unit' => 'bunch',
        'price' => '10.00'
    ]);
    $crossConversion = CrossConversion::factory()->for($ingredient)->create([
        'quantity_two' => 1,
        'unit_two' => $bunchMeasurement,
        'quantity_one' => 20,
        'unit_one' => $sprigMeasurement
    ]);
    $crossConversion->refresh();
    $recipeItem = RecipeItem::factory()->for($ingredient)->create([
        'quantity' => 1,
        'unit' => $sprigMeasurement,
        'cleaned' => false,
        'cooked' => false,
    ])->refresh();


    expect( $recipeItem->crossConversionNeeded() )->toBeTrue();
    expect( $recipeItem->canCalculateCost() )->toBeTrue();
    expect( $recipeItem->crossConversionTypeNeeded() )->toBe(['each', 'each']);
    expect( $crossConversion->conversionType() )->toBe(['each', 'each']);
    expect( $crossConversion->canConvert( $recipeItem->crossConversionTypeNeeded() ))->toBeTrue();
    expect( $crossConversion->canConvertEachToEach() )->toBeTrue();
    expect( $crossConversion->canConvertEachToEach('sprig', 'bunch') )->toBeTrue();
    expect( $crossConversion->canConvertEachToEach('bunch', 'sprig') )->toBeTrue();
    expect( $crossConversion->getEachMeasurementUnitQuantityByName('bunch')->isEqualTo(1) )->toBeTrue();
    expect( $crossConversion->getEachMeasurementUnitQuantityByName('sprig')->isEqualTo(20) )->toBeTrue();
    expect( $recipeItem->getCostAsString() )->toBe( '$0.50' );
});


it('knows it can not calculate cost when only the wrong each to each CrossMeasurement exists', function () {

    // Set up an asPurchased by 'bunch', used by 'sprig',
    // using a conversion of 1 bunch = 20 sprigs.
    $this->seed(EachMeasurementSeeder::class);
    $bunchMeasurement        = new stdClass();
    $bunchMeasurement->value = 'bunch';
    $sprigMeasurement        = new stdClass();
    $sprigMeasurement->value = 'sprig';
    $ingredient              = Ingredient::factory()->create();
    AsPurchased::factory()->for($ingredient)->create([
        'quantity' => 1,
        'unit'     => 'case',
        'price'    => '10.00'
    ]);
    $crossConversion = CrossConversion::factory()->for($ingredient)->create([
        'quantity_two' => 1,
        'unit_two'     => $bunchMeasurement,
        'quantity_one' => 20,
        'unit_one'     => $sprigMeasurement
    ]);
    $crossConversion->refresh();
    $recipeItem = RecipeItem::factory()->for($ingredient)->create([
        'quantity' => 1,
        'unit'     => $sprigMeasurement,
        'cleaned'  => false,
        'cooked'   => false,
    ])->refresh();

    expect( $recipeItem->canCalculateCost() )->toBeFalse();

});
