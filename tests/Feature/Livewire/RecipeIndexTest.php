<?php

use App\Http\Livewire\RecipeIndex;
use App\Models\Recipe;

it('can create a new recipe', function () {

    Livewire::test(RecipeIndex::class)
        ->set('recipeNameInput', 'string')
        ->set('menuPriceInput', '$10.50')
        ->set('portionsInput', 1)
        ->call('createRecipe');

    expect(Recipe::count())->toBe(1);

});

it('validates input', function ($name, $price, $portions) {

    Livewire::test(RecipeIndex::class)
        ->set('recipeNameInput', $name)
        ->set('menuPriceInput', $price)
        ->set('portionsInput', $portions)
        ->call('createRecipe')
        ->assertHasErrors();

})->with([
    ['', '10.00', 1],
    ['name', '', 1],
    ['name', '10.00', '']
]);
