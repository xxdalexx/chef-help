<?php

use App\Http\Livewire\IngredientUpdate;
use App\Models\Ingredient;

it('validates inputs for editing ingredient', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(IngredientUpdate::class, ['ingredient' => Ingredient::factory()->create()])
        ->set($parameter, $value)
        ->call('update')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'nameInput'         => 'required',
//    'cleanedYieldInput' => 'required|numeric|between:1,100',
//    'cookedYieldInput'  => 'required|numeric|between:1,100',
    ['nameInput'],
    ['cleanedYieldInput'],
    ['cleanedYieldInput', 'not a number', 'numeric'],
    ['cleanedYieldInput', '999', 'between'],
    ['cookedYieldInput'],
    ['cookedYieldInput', 'not a number', 'numeric'],
    ['cookedYieldInput', '999', 'between'],
]);


it('updates an ingredient record', function () {

    $ingredient = Ingredient::factory()->create();

    Livewire::test(IngredientUpdate::class, ['ingredient' => $ingredient])
        ->set('nameInput', $updatedName = 'New Name')
        ->set('cookedYieldInput', $updatedCookedYield = '55.55')
        ->set('cleanedYieldInput', $updatedCleanedYield = '66.66')
        ->call('update')
        ->assertEmitted('refresh')
        ->assertHasNoErrors();

    $ingredient->refresh();

    expect( $ingredient->name )->toBe( $updatedName );
    expect( (string) $ingredient->cooked_yield )->toBe( $updatedCookedYield );
    expect( (string) $ingredient->cleaned_yield )->toBe( $updatedCleanedYield );

});
