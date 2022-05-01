<?php

use App\Http\Livewire\RecipeIndex;
use App\Models\MenuCategory;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('renders with records', function () {

    Recipe::factory()->create(['name' => 'Recipe Name']);
    Livewire::test(RecipeIndex::class)
        ->assertSee('Recipe Name');

});


test('recipe search', function () {

    Recipe::factory()
        ->count(2)
        ->state(new Sequence(
            ['name' => 'First'],
            ['name' => 'Second']
        ))->create();

    Livewire::test(RecipeIndex::class)
        ->set('searchString', 'fir')
        ->assertSee('First')
        ->assertDontSee('Second');

});
