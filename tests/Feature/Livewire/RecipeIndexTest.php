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

it('validates input', function ($testing, $name, $price, $portions) {

    Livewire::test(RecipeIndex::class)
        ->set('recipeNameInput', $name)
        ->set('menuPriceInput', $price)
        ->set('portionsInput', $portions)
        ->call('createRecipe')
        ->assertHasErrors([$testing]);

})->with([
    ['recipeNameInput', '', '10.00', 1],
    ['menuPriceInput', 'name', '', 1],
    ['portionsInput', 'name', '10.00', '']
]);
