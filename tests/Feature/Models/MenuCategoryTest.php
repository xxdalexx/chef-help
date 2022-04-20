<?php

use App\Models\MenuCategory;
use App\Models\Recipe;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

test('relationships and casts', function () {

    $menuCategory = MenuCategory::factory()->has(Recipe::factory()->count(3))->create();
    $menuCategory->load('recipes');

    expect(
        $menuCategory->recipes
    )->toBeInstanceOf(
        EloquentCollection::class
    );

    expect(
        $menuCategory->recipes->count()
    )->toBe(3);

    expect(
        $menuCategory->getCostingGoalAsString()
    )->toBeString()->toBe('28');

    expect(
        $menuCategory->costing_goal
    )->toBeInstanceOf(
        BigDecimal::class
    );
});



