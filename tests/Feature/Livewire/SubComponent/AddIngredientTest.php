<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;

it('validates inputs using existing ingredient', function ($parameter, $value = '', $violation = 'required') {

    $recipe = Recipe::factory()->create();
    Ingredient::factory()->create();

    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
        ->set('showingExistingIngredient', 1)
        ->set($parameter, $value)
        ->call('addIngredient')
        ->assertHasErrors([$parameter => $violation]);

})->with([
    ['ingredientInput'],
    ['ingredientInput', 999, 'exists'],
    ['unitInput'],
    ['unitQuantityInput'],
    ['unitQuantityInput', 'not a number', 'numeric'],
    ['cleanedInput', 'not a bool', 'boolean'],
    ['cookedInput', 'not a bool', 'boolean'],
]);


it('validates inputs for new ingredient', function ($parameter, $value = '', $violation = 'required') {

    $recipe = Recipe::factory()->create();

    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
        ->set('showingExistingIngredient', 0)
        ->set($parameter, $value)
        ->call('addIngredient')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'nameInput'          => 'required',
//    'cleanedInput'       => 'required|numeric|between:1,100',
//    'cookedInput'        => 'required|numeric|between:1,100',
//    'apQuantityInput'    => 'required|numeric',
//    'apUnitInput'        => 'required',
//    'apPriceInput'       => 'required|numeric',
//    'unitInput'          => 'required',
//    'unitQuantityInput'  => 'required|numeric',
//    'cleanedYieldInput' => 'boolean',
//    'cookeYieldInput'  => 'boolean'
    ['nameInput'],
    ['cleanedYieldInput'],
    ['cleanedYieldInput', 'not a number', 'numeric'],
    ['cleanedYieldInput', '999', 'between'],
    ['cookedYieldInput'],
    ['cookedYieldInput', 'not a number', 'numeric'],
    ['cookedYieldInput', '999', 'between'],
    ['apQuantityInput'],
    ['apQuantityInput', 'not a number', 'numeric'],
    ['apUnitInput'],
    ['apPriceInput'],
    ['apPriceInput', 'not a number', 'numeric'],
]);


it('attaches an existing ingredient to a recipe by creating a RecipeItem', function () {

    $recipe     = Recipe::factory()->create();
    $ap         = AsPurchased::factory()->create();
    $ingredient = $ap->ingredient;

    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
        ->set('showingExistingIngredient', 1)
        ->set('ingredientInput', $ingredient->id)
        ->set('unitInput', $ap->unit->value)
        ->set('unitQuantityInput', '1')
        ->set('cleanedInput', false)
        ->set('cookedInput', false)
        ->call('addIngredient');

    $recipe->refresh();

    expect(RecipeItem::count())->toBeOne();
    expect($recipe->items)->not->toBeNull();
    expect($recipe->items->first()->ingredient->is($ingredient))->toBeTrue();
});


it('creates an ingredient and attaches it to a recipe by creating a RecipeItem', function () {

    $recipe = Recipe::factory()->create();


    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
        ->set('showingExistingIngredient', 0)
        ->set('nameInput', 'Name')
        ->set('cleanedInput', false)
        ->set('cookedInput', false)
        ->set('apQuantityInput', '1')
        ->set('apUnitInput', 'lb')
        ->set('apPriceInput', '10.00')
        ->set('unitInput', 'oz')
        ->set('unitQuantityInput', '5')
        ->set('cleanedYieldInput', '100')
        ->set('cookedYieldInput', '100')
        ->call('addIngredient')
        ->assertHasNoErrors();

    $recipe->refresh();

    expect(RecipeItem::count())->toBeOne();
    expect(Ingredient::count())->toBeOne();
    expect(AsPurchased::count())->toBeOne();

    $recipeItem = RecipeItem::first();
    $ingredient = Ingredient::first();
    $asPurchased = AsPurchased::first();

    expect($recipe->items->first()->is($recipeItem))->toBeTrue();
    expect($recipe->items->first()->ingredient->is($ingredient))->toBeTrue();
    expect($ingredient->asPurchased->is($asPurchased))->toBeTrue();
});
