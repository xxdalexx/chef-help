<?php

use App\Http\Livewire\RecipeShow;
use App\Http\Livewire\RecipeUpdate;
use App\Models\MenuCategory;
use App\Models\Recipe;

it('can update a recipe without the costing goal', function () {

    $newCategory = MenuCategory::factory()->create();

    Livewire::test(RecipeUpdate::class, ['recipe' => Recipe::factory()->create()])
        ->set('recipeNameInput', 'string')
        ->set('menuCategoryInput', $newCategory->id)
        ->set('menuPriceInput', '$12.34')
        ->set('portionsInput', 5.5)
        ->call('update')
        ->assertHasNoErrors();

    $recipe = Recipe::first();

    expect( (string) $recipe->portions )->toBe( '5.5' );
    expect( $recipe->getPriceAsString() )->toBe( '$12.34' );
    expect( $recipe->menuCategory->id )->toBe( $newCategory->id );

});


it('can update a recipe with the costing goal', function () {

    $newCategory = MenuCategory::factory()->create();

    Livewire::test(RecipeUpdate::class, ['recipe' => Recipe::factory()->create()])
        ->set('recipeNameInput', 'string')
        ->set('menuCategoryInput', $newCategory->id)
        ->set('menuPriceInput', '$12.34')
        ->set('portionsInput', 5.5)
        ->set('costingGoalInput', 33)
        ->call('update')
        ->assertHasNoErrors();

    $recipe = Recipe::first();

    expect( (string) $recipe->portions )->toBe( '5.5' );
    expect( $recipe->getPriceAsString() )->toBe( '$12.34' );
    expect( $recipe->menuCategory->id )->toBe( $newCategory->id );
    expect( (string) $recipe->costing_goal )-> toBe( '33' );

});


it('validates input for updating a recipe', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(RecipeUpdate::class, ['recipe' => Recipe::factory()->create()])
        ->set($parameter, $value)
        ->call('update')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//  'recipeNameInput' => 'required',
//  'menuPriceInput'  => 'nullable|numeric',
//  'portionsInput'   => 'required|numeric',
//  'menuCategoryInput' => 'exists:menu_categories,id'
//    'costingGoalInput'  => 'nullable|numeric'
    ['recipeNameInput'],
    ['menuPriceInput', 'not a number', 'numeric'],
    ['portionsInput'],
    ['portionsInput', 'not a number', 'numeric'],
    ['menuCategoryInput', 999, 'exists'],
    ['costingGoalInput', 'not a number', 'numeric']
]);
