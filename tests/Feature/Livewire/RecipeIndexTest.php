<?php

use App\Http\Livewire\RecipeIndex;
use App\Models\Recipe;

it('can create a new recipe', function () {

    Livewire::test(RecipeIndex::class)
        ->set('recipeNameInput', 'string')
        ->set('menuPriceInput', '$10.50')
        ->set('portionsInput', 1)
        ->call('createRecipe')
        ->assertSet('recipeNameInput', '')
        ->assertSet('menuPriceInput', '')
        ->assertSet('portionsInput', '')
        ->assertSet('searchString', 'string');

    expect(Recipe::count())->toBe(1);

});

it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(RecipeIndex::class)
        ->set($parameter, $value)
        ->call('createRecipe')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'recipeNameInput' => 'required',
//    'menuPriceInput'  => 'required|numeric',
//    'portionsInput'   => 'required|numeric',
    ['recipeNameInput'],
    ['menuPriceInput'],
    ['menuPriceInput', 'not a number', 'numeric'],
    ['portionsInput'],
    ['portionsInput', 'not a number', 'numeric'],
]);
