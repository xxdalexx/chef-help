<?php

use App\Http\Livewire\RecipeShow;
use App\Models\Recipe;
use Database\Seeders\LobsterDishSeeder;

beforeEach()->seed(LobsterDishSeeder::class);

it('can update a recipe', function () {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::first()])
        ->set('recipeNameInput', 'string')
        ->set('menuPriceInput', '$12.34')
        ->set('portionsInput', 5)
        ->call('updateRecipe')
        ->assertHasNoErrors();

    $recipe = Recipe::first();

    expect($recipe->portions)->toBe(5);
    expect($recipe->getPriceAsString())->toBe('$12.34');
});

it('validates input', function ($name, $price, $portions) {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::first()])
        ->set('recipeNameInput', $name)
        ->set('menuPriceInput', $price)
        ->set('portionsInput', $portions)
        ->call('updateRecipe')
        ->assertHasErrors();

})->with([
    ['', '10.00', 1],
    ['name', '', 1],
    ['name', '10.00', '']
]);
