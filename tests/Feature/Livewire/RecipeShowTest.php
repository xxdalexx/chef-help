<?php

use App\Http\Livewire\RecipeShow;
use App\Measurements\MetricWeight;
use App\Models\MenuCategory;
use App\Models\Recipe;
use App\Models\RecipeItem;

it('validates input for updating a recipe item', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::factory()->create(), 'editingRecipeItem' => RecipeItem::factory()->create()])
        ->set($parameter, $value)
        ->call('updateItem')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//  'editUnitInput'         => 'required',
//  'editQuantityInput' => 'required|numeric',
//  'editCleanedInput'      => 'boolean',
//  'editCookedInput'       => 'boolean'
    ['editUnitInput'],
    ['editUnitInput', 'not a unit', 'App\Rules\MeasurementEnumExists'],
    ['editQuantityInput'],
]);


it('can update a recipe item', function () {

    Livewire::test(RecipeShow::class, ['recipe' => Recipe::factory()->create(), 'editingRecipeItem' => $item = RecipeItem::factory()->create()])
        ->set('editUnitInput', 'gram')
        ->set('editQuantityInput', '100')
        ->set('editCleanedInput', ! $item->cleaned)
        ->set('editCookedInput', ! $item->cooked)
        ->call('updateItem')
        ->assertHasNoErrors();

    $recipeItem = RecipeItem::first();

    expect( $recipeItem->unit->value )->toBe( 'gram' );
    expect( (string) $recipeItem->quantity )->toBe( '100' );
    expect( $recipeItem->cooked )->not->toBe( $item->cooked );
    expect( $recipeItem->cleaned )->not->toBe( $item->cleaned );

});
