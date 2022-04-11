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

    expect(Ingredient::count())->toBe(2);
    expect(AsPurchased::count())->toBe(2);
    expect(Recipe::count())->toBe(1);
    expect(RecipeItem::count())->toBe(2);

});
