<?php

use App\Http\Livewire\RecipeCreate;
use App\Models\MenuCategory;
use App\Models\Recipe;

it('can create a new recipe', function () {

    $menuCategoryId = MenuCategory::factory()->create()->id;
    Livewire::test(RecipeCreate::class)
        ->set('recipeNameInput', 'string')
        ->set('menuPriceInput', '$10.50')
        ->set('portionsInput', 1)
        ->set('menuCategoryInput', $menuCategoryId)
        ->call('create')
        ->assertRedirect();

    expect( Recipe::count() )->toBe(1);

    $recipe = Recipe::first();

    expect( $recipe->name )->toBe('string');

    expect( $recipe->getPriceAsString() )->toBe( '$10.50' );

    expect( $recipe->portions->isEqualTo(1) )->toBeTrue();

    expect( $recipe->menuCategory->id )->toBe( $menuCategoryId );

});

it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(RecipeCreate::class)
        ->set($parameter, $value)
        ->call('create')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'recipeNameInput' => 'required',
//    'menuPriceInput'  => 'required|numeric',
//    'portionsInput'   => 'required|numeric',
//    'menuCategoryInput' => 'exists:menu_categories,id'
['recipeNameInput'],
['menuPriceInput'],
['menuPriceInput', 'not a number', 'numeric'],
['portionsInput'],
['portionsInput', 'not a number', 'numeric'],
['menuCategoryInput', 999, 'exists']
]);
