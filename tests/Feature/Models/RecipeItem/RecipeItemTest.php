<?php

use App\Actions\RecipeItemGetCost\Steps\BothUnitsAreTheSame;
use App\Measurements\MeasurementEnum;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Database\Seeders\EachMeasurementSeeder;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\RandomRecipeSeeder;
use function Spatie\PestPluginTestTime\testTime;


test('relationships and casts', function () {

    $item = RecipeItem::factory()->create();

    expect( $item->ingredient )->toBeInstanceOf( Ingredient::class );
    expect( $item->recipe )->toBeInstanceOf( Recipe::class );
    expect( $item->unit )->toBeInstanceOf( MeasurementEnum::class );

});


//Should figure out a better way to get ids.
it('has a calculated cost', function ($idOffset, $expectedCost) {
    $this->seed(LobsterDishSeeder::class);
    $id = RecipeItem::first()->id + $idOffset;

    /** @var RecipeItem $item */
    $item = RecipeItem::with('ingredient.asPurchased')->find($id);

    expect( $item->getCost() )->toBeMoney();
    expect( $item->getCostAsString() )->toBe( $expectedCost );

})->with([
    //Refresh database doesn't reset auto incrementing ids.
    [0, '$7.50'],  // Lobster
    [1, '$1.61'],  // Heavy Cream
    [2, '$0.50'],  // Sesame Seed Oil
    [3, '$1.00']   // Imported Aged White Balsamic Vinegar
]);


it('returns 0.00 money object when cost cannot be calculated', function () {

    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->create();
    $item = RecipeItem::factory()->for($recipe)->for($ingredient)->create();

    expect( $item->getCostAsString() )->toBe( '$0.00' );

});


it('knows that it cannot calculate cost when ingredient is missing AP record', function () {

    //Create Recipe, Item, and an Ingredient with no AP Record.
    $recipe = Recipe::factory()->has(
        RecipeItem::factory()->has(
            Ingredient::factory()
        ), 'items'
    )->create();

    expect( RecipeItem::first()->canCalculateCost() )->toBeFalse();

});


it('can tell the reasoning for not calculating cost', function () {

    //Create Recipe, Item, and an Ingredient with no AP Record.
    $recipe = Recipe::factory()->has(
        RecipeItem::factory(['unit' => UsWeight::oz])->has(
            Ingredient::factory()
        ), 'items'
    )->create();

    $item = RecipeItem::first();
    expect( $item->canNotCalculateCostReason() )->toBe( 'No As Purchased Data' );

    AsPurchased::factory()->for($item->ingredient)->create(['unit' => UsVolume::floz]);
    $item->refresh();

    expect( $item->canNotCalculateCostReason() )->toBe( 'No Weight <-> Volume Conversion' );

});


it('knows that it cannot calculate cost when there is a type mismatch without a CrossConversion', function () {

    //Create Recipe, Item, and an Ingredient with no CrossConversion Record.
    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->has( AsPurchased::factory(['unit' => UsWeight::oz]) )->create();
    $item = RecipeItem::factory()->for($recipe)->for($ingredient)->create(['unit' => UsVolume::floz]);

    expect( $item->canCalculateCost() )->toBeFalse();
    expect( $item->getCostAsString() )->toBe('$0.00');

});


it('knows what the needed CrossConversion is', function () {

    $ingredient = Ingredient::factory()->has(
        AsPurchased::factory(['unit' => UsVolume::floz])
    )->create();
    $conversion = CrossConversion::factory()->create([
        'ingredient_id' => $ingredient->id,
        'quantity_one' => 10,
        'unit_one' => UsVolume::floz,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb
    ]);
    $recipeItem = RecipeItem::factory()->for($ingredient)->create([
        'unit' => UsWeight::lb
    ]);

    expect( $recipeItem->getCrossConversion()->is($conversion) )->toBeTrue();

});


it('calculates a cost with a US Metric conversion', function ($idOffset, $expectedCost) {

    $this->seed(RandomRecipeSeeder::class);
    $id = RecipeItem::first()->id + $idOffset;

    $item = RecipeItem::find($id);

    expect( $item->getCostAsString() )->toBe( $expectedCost );

})->with([
    [0, '$4.74'],  //AP: Metric Volume, RI: US Volume
    [1, '$16.91'], //AP: US Volume, RI: Metric Volume
    [2, '$17.64'], //AP: US Weight, RI: Metric Weight
    [3, '$14.75'], //AP: Metric Weight, RI: US Weight
]);


test('get cost caching through attribute accessor', function () {

    $this->seed(LobsterDishSeeder::class);
    $item = RecipeItem::first();
    testTime()->addMinutes(5);

    expect(
        Cache::has( $item->costCacheKey() )
    )->toBeFalse();
    $originalCost = $item->cost;

    expect(
        Cache::has( $item->costCacheKey() )
    )->toBeTrue();
    $originalKey = $item->costCacheKey();

    $item->update(['quantity' => 100]);

    expect( $item->cost )->not->toBe( $originalCost );
    expect( $item->costCacheKey() )->not->toBe( $originalKey );

});


test('cost cache is updated when the ap price is updated', function () {

    $this->seed(LobsterDishSeeder::class);
    testTime()->addDay();
    /** @var RecipeItem $item */
    $item         = RecipeItem::with('ingredient.asPurchased')->first();
    $originalCost = $item->getCostAsString();
    $originalKey  = $item->costCacheKey();

    $ap = $item->ingredient->asPurchased;
    $ap->update(['price' => money(13)]);
    $item->refresh();

    expect( $item->costCacheKey() )->not->toBe( $originalKey );
    expect( $item->getCostAsString() )->not->toBe( $originalCost );

});


it('can have a recipe as an ingredient', function () {

    $this->seed(EachMeasurementSeeder::class);
    $portionUnit = new stdClass();
    $portionUnit->value = 'portion';
    $recipe = Recipe::factory()->create();
    $recipeForIngredient = Recipe::factory()->create();
    $recipeItem = RecipeItem::create([
        'ingredient_id' => $recipeForIngredient->id,
        'ingredient_type' => Recipe::class,
        'quantity' => 1,
        'unit' => $portionUnit,
        'recipe_id' => $recipe->id,
        'cooked' => false,
        'cleaned' => false
    ]);
    $recipeItem->refresh();

    expect($recipeItem->getCost())->toBeMoney();

});


test('action returns early when both units are the same testing with EachMeasurement Model', function () {

    $this->seed(EachMeasurementSeeder::class);
    $eachMeasurement = new stdClass();
    $eachMeasurement->value = 'each';
    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->has(
        AsPurchased::factory([
            'quantity' => 1,
            'unit' => 'each',
            'price' => '5.99'
        ])
    )->create();
    $recipeItem = RecipeItem::factory()->for($recipe)->for($ingredient)->create([
        'quantity' => 2,
        'unit' => $eachMeasurement,
        'cooked' => false,
        'cleaned' => false
    ]);
    $recipeItem->refresh();
    $action = new App\Actions\RecipeItemGetCost\RecipeItemGetCostAction($recipeItem);
    $action->setSteps([
        BothUnitsAreTheSame::class
    ]);

    expect(
        moneyToString( $recipeItem->getCost($action) )
    )->toBe('$11.98');

});


test('action returns early when both units are the same testing Measurement Enum', function () {

    $this->seed(EachMeasurementSeeder::class);
    $recipe = Recipe::factory()->create();
    $ingredient = Ingredient::factory()->has(
        AsPurchased::factory([
            'quantity' => 1,
            'unit' => 'oz',
            'price' => '5.99'
        ])
    )->create();
    $recipeItem = RecipeItem::factory()->for($recipe)->for($ingredient)->create([
        'quantity' => 2,
        'unit' => UsWeight::oz,
        'cooked' => false,
        'cleaned' => false
    ]);
    $recipeItem->refresh();
    $action = new App\Actions\RecipeItemGetCost\RecipeItemGetCostAction($recipeItem);
    $action->setSteps([
        BothUnitsAreTheSame::class
    ]);

    expect(
        moneyToString( $recipeItem->getCost($action) )
    )->toBe('$11.98');

});
