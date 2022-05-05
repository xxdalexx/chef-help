<?php

use App\Http\Livewire\IngredientShow;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Location;
use function Spatie\PestPluginTestTime\testTime;

it('validates inputs for adding ap record', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(IngredientShow::class, ['ingredient' => Ingredient::factory()->create()])
        ->set($parameter, $value)
        ->call('addAsPurchased')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'apQuantityInput' => 'required|numeric',
//    'apUnitInput'     => 'required',
//    'apPriceInput'    => 'required|numeric',
    ['apQuantityInput'],
    ['apQuantityInput', 'not a number', 'numeric'],
    ['apUnitInput'],
    ['apUnitInput', 'not a unit', 'App\Rules\MeasurementEnumExists'],
    ['apPriceInput'],
    ['apPriceInput', 'not a number', 'numeric'],
]);


it('creates an ap record', function () {

    $ingredient = Ingredient::factory()->has( AsPurchased::factory() )->create();
    testTime()->addDay();

    Livewire::test(IngredientShow::class, ['ingredient' => $ingredient])
        ->set('apQuantityInput', '1')
        ->set('apUnitInput', 'oz')
        ->set('apPriceInput', '1.25')
        ->call('addAsPurchased')
        ->assertHasNoErrors();
    $ingredient->refresh();
    $newAP = AsPurchased::latest()->first();

    expect( AsPurchased::count() )->toBe(2);
    expect( $ingredient->asPurchasedHistory->count() )->toBeOne();
    expect( (string) $newAP->quantity )->toBe('1');
    expect( $newAP->unit )->toBe( UsWeight::oz );
    expect( $newAP->getPriceAsString() )->toBe( '$1.25' );

});


it('adds a location to an ingredient', function () {

    $ingredient = Ingredient::factory()->create();
    $location   = Location::factory()->create();

    Livewire::test(IngredientShow::class, ['ingredient' => $ingredient])
        ->call('addLocation');

    $ingredient->refresh();
    expect( $ingredient->locations->count() )->toBeOne();
    expect( $ingredient->locations->first()->is($location) )->toBeTrue();

});


it('removes a location from an ingredient', function () {

    $ingredient = Ingredient::factory()->create();
    $location   = Location::factory()->hasAttached($ingredient)->create();

    Livewire::test(IngredientShow::class, ['ingredient' => $ingredient])
        ->call('removeLocation', $location->id);

    expect( $ingredient->locations()->count() )->toBe(0);

});
