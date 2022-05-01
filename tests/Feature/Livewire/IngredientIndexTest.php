<?php

use App\Http\Livewire\IngredientIndex;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('renders with records', function () {

    Ingredient::factory()->create(['name' => 'Ingredient Name']);
    Livewire::test(IngredientIndex::class)
        ->assertSee('Ingredient Name');

});


test('recipe search', function () {

    Ingredient::factory()
        ->count(2)
        ->state(new Sequence(
            ['name' => 'First'],
            ['name' => 'Second']
        ))->create();

    Livewire::test(IngredientIndex::class)
        ->set('searchString', 'fir')
        ->assertSee('First')
        ->assertDontSee('Second');

});
