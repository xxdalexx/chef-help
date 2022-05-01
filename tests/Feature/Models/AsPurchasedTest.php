<?php

use App\CustomCollections\AsPurchasedCollection;
use App\Measurements\MeasurementEnum;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\ValueObjects\ConvertableUnit;
use Database\Seeders\LobsterDishSeeder;

test('relations and casts', function () {

    $ap = AsPurchased::factory()->create();

    expect( $ap->ingredient )->toBeInstanceOf( Ingredient::class );
    expect( $ap->unit )->toBeInstanceOf( MeasurementEnum::class );
    expect( $ap->price )->toBeMoney();

});


test('custom collection AsPurchasedCollection', function () {

    AsPurchased::factory()->count(2)->create();
    $collection = AsPurchased::all();

    expect( $collection )->toBeInstanceOf(AsPurchasedCollection::class);

});


it('has a convertable unit value object', function () {

    $ap = AsPurchased::factory()->create();

    expect( $ap->getConvertableUnit() )->toBeInstanceOf( ConvertableUnit::class );

});


it('gives a price per base unit', function () {

    $this->seed(LobsterDishSeeder::class);
    $ap = AsPurchased::first();

    expect( $ap->getCostPerBaseUnitAsString() )->toBe( '$0.75' );

});


it('can have a quantity that is not a whole number', function () {

    Recipe::factory()->create();
    $ap = AsPurchased::factory()->for( Ingredient::factory() )->create([
        'quantity' => 1.5,
        'unit' => UsWeight::oz,
        'price' => 1.5
    ])->refresh();

    expect( $ap->getCostPerBaseUnitAsString() )->toBe( '$1.00' );
});
