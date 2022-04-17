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


it('validates input', function ($testing, $name, $cleaned, $cooked) {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', $name)
        ->set('cleanedInput', $cleaned)
        ->set('cookedInput', $cooked)
        ->call('createIngredient')
        ->assertHasErrors([$testing]);

})->with([
    ['nameInput', '', '100', '100'],
    ['cleanedInput', 'name', '1000', '100'],
    ['cookedInput', 'name', '100', '1000']
]);


it('validates input when creating asPurchased', function ($property, $name, $cleaned, $cooked, $quantity, $unit, $price) {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', $name)
        ->set('cleanedInput', $cleaned)
        ->set('cookedInput', $cooked)
        ->set('apQuantityInput', $quantity)
        ->set('apUnitInput', $unit)
        ->set('apPriceInput', $price)
        ->call('createIngredient')
        ->assertHasErrors([$property]);

})->with([
    ['nameInput', '', '100', '100', '1', 'oz', '1.00'],
    ['cleanedInput', 'name', '1000', '100', '1', 'oz', '1.00'],
    ['cookedInput', 'name', '100', '1000', '1', 'oz', '1.00'],
    ['apQuantityInput', 'name', '100', '100', '', 'oz', '1.00'],
    ['apUnitInput', 'name', '100', '100', '1', '', '1.00'],
    ['apPriceInput', 'name', '100', '100', '1', 'oz', ''],
]);
