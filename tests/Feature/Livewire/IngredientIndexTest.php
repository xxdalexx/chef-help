<?php

use App\Http\Livewire\IngredientIndex;
use App\Models\Ingredient;

it('can create a new ingredient', function () {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', 'string')
        ->set('cleanedInput', '90')
        ->set('cookedInput', '90')
        ->set('createAsPurchase', false)
        ->call('createIngredient')
        ->assertSet('nameInput', '')
        ->assertSet('cleanedInput', '100')
        ->assertSet('cookedInput', '100')
        ->assertSet('searchString', 'string');

    expect(Ingredient::count())->toBe(1);

});


it('can create a new ingredient with an asPurchased record', function () {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', 'string')
        ->set('cleanedInput', '90')
        ->set('cookedInput', '90')
        ->set('apQuantityInput', '1')
        ->set('apUnitInput', 'lb')
        ->set('apPriceInput', '1.00')
        ->set('createAsPurchase', true)
        ->call('createIngredient')
        ->assertSet('nameInput', '')
        ->assertSet('cleanedInput', '100')
        ->assertSet('cookedInput', '100')
        ->assertSet('apQuantityInput', '1')
        ->assertSet('apUnitInput', 'oz')
        ->assertSet('apPriceInput', '');

    expect(Ingredient::count())->toBe(1);
    $ingredient = Ingredient::first();
    expect($ingredient->asPurchased)->not->toBeNull();

});


it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(IngredientIndex::class)
        ->set($parameter, $value)
        ->call('createIngredient')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'nameInput'       => 'required',
//    'cleanedInput'    => 'required|numeric|between:1,100',
//    'cookedInput'     => 'required|numeric|between:1,100',
    ['nameInput'],
    ['cleanedInput'],
    ['cleanedInput', 'not a number', 'numeric'],
    ['cleanedInput', '999', 'between'],
    ['cookedInput'],
    ['cookedInput', 'not a number', 'numeric'],
    ['cookedInput', '999', 'between'],
]);


it('validates input when creating asPurchased', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', 'name')
        ->set('cleanedInput', '100')
        ->set('cookedInput', '100')
        //Above is required because the tested validation happen separately.
        ->set($parameter, $value)
        ->call('createIngredient')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'apQuantityInput' => 'required|numeric',
//    'apUnitInput'     => 'required',
//    'apPriceInput'    => 'required|numeric',
    ['apQuantityInput'],
    ['apQuantityInput', 'not a number', 'numeric'],
    ['apUnitInput'],
    ['apPriceInput'],
    ['apPriceInput', 'not a number', 'numeric'],
]);
