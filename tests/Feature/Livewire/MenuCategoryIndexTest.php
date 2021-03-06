<?php

use App\Http\Livewire\MenuCategoryIndex;
use App\Models\MenuCategory;
use App\Models\Recipe;

it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(MenuCategoryIndex::class)
        ->set($parameter, $value)
        ->call('process')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'nameInput' => 'required',
//    'costingGoalInput' => 'required|numeric',
    ['nameInput'],
    ['costingGoalInput'],
    ['costingGoalInput', 'not a number', 'numeric'],
]);


it('creates a new MenuCategory', function () {

    Livewire::test(MenuCategoryIndex::class)
        ->set('nameInput', 'string')
        ->set('costingGoalInput', '30')
        ->call('process')
        ->assertEmitted('alertWithToast')
        ->assertSet('nameInput', '')
        ->assertSet('costingGoalInput', '');

    expect( MenuCategory::count() )->toBeOne();
    $category = MenuCategory::first();
    expect( $category->name )->toBe( 'string' );
    expect( $category->getCostingGoalAsString() )->toBe( '30' );

});


it('edits an existing MenuCategory', function () {

    $menuCategory = MenuCategory::factory()->create();

    Livewire::test(MenuCategoryIndex::class)
        ->set('menuCategoryEditing', $menuCategory->id)
        ->set('nameInput', 'string')
        ->set('costingGoalInput', '30')
        ->call('process')
        ->assertEmitted('alertWithToast')
        ->assertSet('nameInput', '')
        ->assertSet('costingGoalInput', '')
        ->assertSet('menuCategoryEditing', '');

    expect( MenuCategory::count() )->toBeOne();
    $category = MenuCategory::first();
    expect( $category->name )->toBe( 'string' );
    expect( $category->getCostingGoalAsString() )->toBe( '30' );

});


it('deletes a menu category', function () {

    $menuCategory = MenuCategory::factory()->count(2)->create()->first();

    Livewire::test(MenuCategoryIndex::class)
        ->call('deleteMenuCategory', $menuCategory->id)
        ->assertEmitted('alertWithToast');

    expect( MenuCategory::count() )->toBe(1);

});


it('moves recipes to another category before deleting', function () {

    $originallyEmpty = MenuCategory::factory()->create();
    $originallyFull = MenuCategory::factory()->has( Recipe::factory()->count(3) )->create();

    $livewire = Livewire::test(MenuCategoryIndex::class)
        ->call('deleteMenuCategory', $originallyFull)
        ->assertEmitted('showModal');

    expect( $originallyFull->recipes->count() )->toBe(3);
    expect( MenuCategory::count() )->toBe(2);

    $livewire->set('wantingToDelete', $originallyFull->id)
        ->set('categoryIdToMoveTo', $originallyEmpty->id)
        ->call('moveAllToCategory')
        ->assertEmitted('alertWithToast');

    expect( MenuCategory::count() )->toBeOne();
    expect( $originallyEmpty->recipes->count() )->toBe(3);

});
