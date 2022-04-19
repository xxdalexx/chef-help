<?php

use App\Measurements\MeasurementEnum;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\RandomRecipeSeeder;
use function Spatie\PestPluginTestTime\testTime;

test('relationships and casts', function () {

    $item = RecipeItem::factory()->create();

    expect($item->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($item->recipe)->toBeInstanceOf(Recipe::class);
    expect($item->unit)->toBeInstanceOf(MeasurementEnum::class);

});


it('has a calculated cost', function ($id, $expectedCost) {

    $this->seed(LobsterDishSeeder::class);

    $item = RecipeItem::withFullIngredientRelation()->find($id);

    expect($item->getCost())->toBeMoney();
    expect($item->getCostAsString())->toBe($expectedCost);

})->with([
    [1, '$7.50'],
    [2, '$1.61'],
    [3, '$0.50'],
    [4, '$1.00']
]);


it('returns $0.00 cost on weight/volume conversion attempt', function () {

    $this->seed(RandomRecipeSeeder::class);

    $item = RecipeItem::first();
    $item->unit = UsWeight::oz;

    expect($item->getCostAsString())->toBe('$0.00');
});


it('calculates a cost with a US Metric conversion', function ($id, $expectedCost) {

    $this->seed(RandomRecipeSeeder::class);

    $item = RecipeItem::find($id);

    expect($item->getCostAsString())->toBe($expectedCost);

})->with([
    [1, '$4.74'],  //AP: Metric Volume, RI: US Volume
    [2, '$16.91'], //AP: US Volume, RI: Metric Volume
    [3, '$17.64'], //AP: US Weight, RI: Metric Weight
    [4, '$14.75'], //AP: Metric Weight, RI: US Weight
]);

test('get cost caching through attribute accessor', function () {

    $this->seed(LobsterDishSeeder::class);
    $item = RecipeItem::first();
    testTime()->addMinutes(5);

    expect(Cache::has($item->costCacheKey()))->toBeFalse();
    $originalCost = $item->cost;
    expect(Cache::has($item->costCacheKey()))->toBeTrue();
    $originalKey = $item->costCacheKey();

    $item->update(['quantity' => 100]);
    expect($item->cost)->not->toBe($originalCost);
    expect($item->costCacheKey())->not->toBe($originalKey);
});
