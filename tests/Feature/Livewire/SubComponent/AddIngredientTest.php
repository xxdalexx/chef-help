<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;

it('validates inputs', function ($parameter, $value = '', $violation = 'required') {

    $recipe = Recipe::factory()->create();
    Ingredient::factory()->create();

    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
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


it('links an ingredient to a recipe by creating a RecipeItem', function () {

    $recipe = Recipe::factory()->create();
    $ap = AsPurchased::factory()->create();
    $ingredient = $ap->ingredient;

    Livewire::test(\App\Http\Livewire\SubComponent\AddIngredient::class, ['recipe' => $recipe])
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
