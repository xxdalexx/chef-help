<?php


use App\Http\Livewire\IngredientCreate;
use App\Models\Ingredient;

it('can create a new ingredient', function () {

    Livewire::test(IngredientCreate::class)
        ->set('nameInput', 'string')
        ->set('cleanedInput', '90')
        ->set('cookedInput', '90')
        ->set('createAsPurchased', false)
        ->call('create')
        ->assertRedirect();

    expect( Ingredient::count() )->toBe(1);

});


it('can create a new ingredient with an asPurchased record', function () {

    Livewire::test(IngredientCreate::class)
        ->set('nameInput', 'string')
        ->set('cleanedInput', '90')
        ->set('cookedInput', '90')
        ->set('apQuantityInput', '1')
        ->set('apUnitInput', 'lb')
        ->set('apPriceInput', '1.00')
        ->set('createAsPurchased', true)
        ->call('create')
        ->assertRedirect();

    expect( Ingredient::count() )->toBe(1);
    $ingredient = Ingredient::first();
    expect( $ingredient->asPurchased )->not->toBeNull();

});


it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(IngredientCreate::class)
        ->set('createAsPurchased', true)
        ->set($parameter, $value)
        ->call('create')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'nameInput'       => 'required',
//    'cleanedInput'    => 'required|numeric|between:1,100',
//    'cookedInput'     => 'required|numeric|between:1,100',
//    'apQuantityInput' => ['numeric', Rule::requiredIf($creatingAsPurchased)],
//    'apUnitInput'     => [Rule::requiredIf($creatingAsPurchased)],
//    'apPriceInput'    => ['numeric', Rule::requiredIf($creatingAsPurchased)],
    ['nameInput'],
    ['cleanedInput'],
    ['cleanedInput', 'not a number', 'numeric'],
    ['cleanedInput', '999', 'between'],
    ['cookedInput'],
    ['cookedInput', 'not a number', 'numeric'],
    ['cookedInput', '999', 'between'],
    ['apPriceInput', 'not a number', 'numeric'],
    ['apQuantityInput', 'not a number', 'numeric'],
]);

