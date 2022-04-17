<?php

use App\Http\Livewire\IngredientIndex;
use App\Models\Ingredient;

it('can create a new ingredient', function () {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', 'string')
        ->set('cleanedInput', '90')
        ->set('cookedInput', '90')
        ->call('createIngredient')
        ->assertSet('nameInput', '')
        ->assertSet('cleanedInput', '100')
        ->assertSet('cookedInput', '100')
        ->assertSet('searchString', 'string');
    +

    expect(Ingredient::count())->toBe(1);

});

it('validates input', function ($name, $cleaned, $cooked) {

    Livewire::test(IngredientIndex::class)
        ->set('nameInput', $name)
        ->set('cleanedInput', $cleaned)
        ->set('cookedInput', $cooked)
        ->call('createIngredient')
        ->assertHasErrors();

})->with([
    ['', '100', '100'],
    ['name', '1000', '100'],
    ['name', '100', '1000']
]);
