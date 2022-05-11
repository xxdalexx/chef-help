<?php

use App\CustomCollections\AsPurchasedCollection;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use App\Models\Location;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\EachMeasurementSeeder;
use function Spatie\PestPluginTestTime\testTime;

test('relationships and casts', function () {

    $ingredient = Ingredient::factory()
        ->has( AsPurchased::factory() )
        ->has( RecipeItem::factory()->count(3) )
        ->has( Location::factory()->count(2) )
        ->has( CrossConversion::factory() )
        ->create()->refresh();

    expect( $ingredient->asPurchased )->toBeInstanceOf( AsPurchased::class );
    expect( $ingredient->recipeItems )->toBeCollection();
    expect( $ingredient->recipeItems->first() )->toBeInstanceOf( RecipeItem::class );
    expect( $ingredient->locations )->toBeCollection();
    expect( $ingredient->locations->first() )->toBeInstanceOf( Location::class );
    expect( $ingredient->crossConversions )->toBeCollection();
    expect( $ingredient->crossConversions->first() )->toBeInstanceOf( CrossConversion::class );
    expect( $ingredient->recipes )->toBeCollection();
    expect( $ingredient->recipes->first() )->toBeInstanceOf( Recipe::class );

});


it('has multiple asPurchased relationships', function () {

    $ingredient = Ingredient::factory()->has( AsPurchased::factory() )->create();
    $initialAP = $ingredient->load('asPurchased')->asPurchased;
    testTime()->addDay();
    $newAP = AsPurchased::factory()->for($ingredient)->create([
        'unit' => $initialAP->unit,
        'quantity' => $initialAP->quantity,
        'price' => $initialAP->price->plus(1)
    ]);

    $ingredient->refresh();
    //asPurchased is HasOne, returns the newest record.
    expect( $ingredient->asPurchased->is($newAP) )->toBeTrue();
    //history is last 10 except for the newest.
    expect( $ingredient->asPurchasedHistory->first()->is($initialAP) )->toBeTrue();
    //all
    expect( $ingredient->asPurchasedAll->count() )->toBe(2);
    expect( $ingredient->asPurchasedAll )->toBeInstanceOf( AsPurchasedCollection::class );

});


it('returns locations that it does not have a relationship to', function () {

    $notAssignedLocations = Location::factory()->count(3)->create();
    $ingredient = Ingredient::factory()->has( Location::factory()->count(2) )->create();

    expect( Location::count() )->toBe(5);
    expect( $ingredient->inverseLocations()->count() )->toBe(3);
    expect(
        $notAssignedLocations->contains( $ingredient->inverseLocationIds() )
    )->toBeFalse();

});


it('returns the requested crossconversion record', function () {

    $this->seed(EachMeasurementSeeder::class);
    $ingredient = Ingredient::factory()->create();

    $each = new stdClass;
    $each->value = 'each';
    $bunch = new stdClass;
    $bunch->value = 'bunch';
    $eachWeight = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb
    ]);
    $eachVolume = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsVolume::floz
    ]);
    $eachEach = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => $bunch
    ]);
    $weightVolume = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => UsWeight::oz,
        'quantity_two' => 1,
        'unit_two' => UsVolume::floz
    ]);
    $ingredient->refresh();

    expect( $ingredient->getCrossConversion(['weight', 'volume'])->is($weightVolume) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['volume', 'weight'])->is($weightVolume) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['weight', 'each'])->is($eachWeight) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['each', 'weight'])->is($eachWeight) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['volume', 'each'])->is($eachVolume) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['each', 'volume'])->is($eachVolume) )->toBeTrue();
    expect( $ingredient->getCrossConversion(['each', 'each'])->is($eachEach) )->toBeTrue();

});


it('knows that it has the requested conversion', function () {

    $this->seed(EachMeasurementSeeder::class);
    $ingredient = Ingredient::factory()->create();

    $each = new stdClass;
    $each->value = 'each';
    $bunch = new stdClass;
    $bunch->value = 'bunch';
    $eachWeight = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb
    ]);
    $eachVolume = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsVolume::floz
    ]);
    $eachEach = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => $bunch
    ]);
    $weightVolume = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => UsWeight::oz,
        'quantity_two' => 1,
        'unit_two' => UsVolume::floz
    ]);
    $ingredient->refresh();

    expect( $ingredient->canCrossConvert(['weight', 'volume']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['volume', 'weight']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['weight', 'each']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['each', 'weight']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['volume', 'each']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['each', 'volume']) )->toBeTrue();
    expect( $ingredient->canCrossConvert(['each', 'each']) )->toBeTrue();

});
