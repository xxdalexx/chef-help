<?php

use App\Http\Livewire\RecipeShow;
use App\Models\Recipe;


it('can update a recipe', function () {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::factory()->create()])
        ->set('recipeNameInput', 'string')
        ->set('menuPriceInput', '$12.34')
        ->set('portionsInput', 5)
        ->call('updateRecipe')
        ->assertHasNoErrors();

    $recipe = Recipe::first();

    expect($recipe->portions)->toBe(5);
    expect($recipe->getPriceAsString())->toBe('$12.34');
});

it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::factory()->create()])
        ->set($parameter, $value)
        ->call('updateRecipe')
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
