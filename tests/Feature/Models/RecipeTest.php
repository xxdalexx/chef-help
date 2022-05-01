<?php

use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\MenuCategory;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Brick\Math\BigDecimal;
use Database\Seeders\LobsterDishSeeder;
use Database\Seeders\RandomRecipeSeeder;

test('casts and relationships', function () {

    $recipe = Recipe::factory()
        ->has(RecipeItem::factory()->count(3), 'items')
        ->for(MenuCategory::factory())
        ->create();

    expect( $recipe->price )->toBeMoney();
    expect( $recipe->portions )->toBeInstanceOf( BigDecimal::class );
    expect( $recipe->costing_goal )->toBeInstanceOf( BigDecimal::class );
    expect( $recipe->items )->toBeCollection();
    expect( $recipe->items->first() )->toBeInstanceOf( RecipeItem::class );
    expect( $recipe->menuCategory )->toBeInstanceOf( MenuCategory::class );
    expect( $recipe->ingredients )->toBeCollection();
    expect( $recipe->ingredients->first() )->toBeInstanceOf( Ingredient::class );

});


test('Lobster Dish Seeder', function () {

    $this->seed(LobsterDishSeeder::class);

    expect( Ingredient::count() )->toBe(4);
    expect( AsPurchased::count() )->toBe(4);
    expect( Recipe::count() )->toBe(1);
    expect( RecipeItem::count() )->toBe(4);

});


it('gives a total cost', function () {

    $this->seed(LobsterDishSeeder::class);
    /** @var Recipe $recipe */
    $recipe = Recipe::with('items.ingredient.asPurchased')->first();

    expect( $recipe->getTotalCostAsString() )->toBe( '$10.61' );

});


it('gives a cost per portion', function () {

    $this->seed(LobsterDishSeeder::class);
    /** @var Recipe $recipe */
    $recipe = Recipe::with('items.ingredient.asPurchased')->first();

    expect( $recipe->getCostPerPortionAsString() )->toBe( '$5.31' );

});


it('give portion cost percentage', function () {

    $this->seed(LobsterDishSeeder::class);
    /** @var Recipe $recipe */
    $recipe = Recipe::with('items.ingredient.asPurchased')->first();

    expect( $recipe->getPortionCostPercentageAsString() )->toBe( '29.5%' );

});


it('knows if the cost is inaccurate', function () {

    $this->seed(RandomRecipeSeeder::class);
    /** @var Recipe $recipe */
    $recipe = Recipe::with('items.ingredient.asPurchased')->first();

    expect( $recipe->hasInaccurateCost() )->toBeTrue();

});


it('knows if the cost is accurate', function () {

    $this->seed(LobsterDishSeeder::class);
    /** @var Recipe $recipe */
    $recipe = Recipe::with('items.ingredient.asPurchased')->first();

    expect( $recipe->hasInaccurateCost() )->toBeFalse();
});


it('knows what the mathematical difference is between the cost percent and goal', function () {

    $menuCategory = MenuCategory::factory()->create(['costing_goal' => 50]);
    $recipe = Recipe::factory()->for($menuCategory)->create([
        'portions' => 1,
        'price' => '1.00'
    ]);
    $ingredient = Ingredient::factory()->create([
        'cleaned_yield' => 100,
        'cooked_yield' => 100
    ]);
    AsPurchased::factory()->for($ingredient)->create([
        'unit' => UsWeight::oz,
        'quantity' => 1,
        'price' => '1.00'
    ]);
    RecipeItem::factory()->for($recipe)->for($ingredient)->create([
        'quantity' => 1,
        'unit' => UsWeight::oz,
    ]);
    $recipe->refresh();

    expect( $recipe->getCostPerPortionAsString() )->toBe( '$1.00' );
    expect( $recipe->getPortionCostPercentageAsString() )->toBe( '100%' );
    expect( $recipe->getCostingPercentageDifferenceFromGoalAsString() )->toBe( '50.0' );

});


it('can override the menu category costing goal', function () {

    $menuCategory = MenuCategory::factory()->create(['costing_goal' => 50]);
    $recipe = Recipe::factory()->for($menuCategory)->create([
        'costing_goal' => 100,
        'portions' => 1,
        'price' => '1.00'
    ]);
    $ingredient = Ingredient::factory()->create([
        'cleaned_yield' => 100,
        'cooked_yield' => 100
    ]);
    AsPurchased::factory()->for($ingredient)->create([
        'unit' => UsWeight::oz,
        'quantity' => 1,
        'price' => '1.00'
    ]);
    RecipeItem::factory()->for($recipe)->for($ingredient)->create([
        'quantity' => 1,
        'unit' => UsWeight::oz,
    ]);
    $recipe->refresh();

    expect( $recipe->getCostPerPortionAsString() )->toBe( '$1.00' );
    expect( $recipe->getPortionCostPercentageAsString() )->toBe( '100%' );
    expect( $recipe->getCostingPercentageDifferenceFromGoalAsString() )->not->toBe( '50.0' ); //Redundant, but for clarity compared to test above.
    expect( $recipe->getCostingPercentageDifferenceFromGoalAsString() )->toBe( '0.0' );

});
