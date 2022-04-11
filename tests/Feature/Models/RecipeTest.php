<?php

use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;

test('casts and relationships', function () {

    $recipe = Recipe::factory()
        ->has(RecipeItem::factory()->count(3), 'items')
        ->create();

    expect($recipe->price)->toBeMoney();
    expect($recipe->items)->toBeCollection();

});


test('Lobster Dish Seeder', function () {

    $this->seed(LobsterDishSeeder::class);

    expect(Ingredient::count())->toBe(4);
    expect(AsPurchased::count())->toBe(4);
    expect(Recipe::count())->toBe(1);
    expect(RecipeItem::count())->toBe(4);

});


it('gives a total cost', function () {

    $this->seed(LobsterDishSeeder::class);

    $recipe = Recipe::first();

    expect($recipe->totalCostAsString())->toBe('$10.61');

});


it('gives a cost per portion', function () {

    $this->seed(LobsterDishSeeder::class);

    $recipe = Recipe::first();

    expect($recipe->costPerPortionAsString())->toBe('$5.31');

});

it('give portion cost percentage', function () {

    $this->seed(LobsterDishSeeder::class);

    $recipe = Recipe::first();

    expect($recipe->portionCostPercentageAsString())->toBe('29.5');

});
