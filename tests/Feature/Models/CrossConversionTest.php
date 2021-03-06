<?php

use App\Measurements\MeasurementEnum;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use Brick\Math\BigDecimal;
use Database\Seeders\EachMeasurementSeeder;

test('relationships and casts', function () {

    $conversion = CrossConversion::factory()->create();

    expect( $conversion->ingredient )->toBeInstanceOf(Ingredient::class);
    expect( $conversion->unit_one )->toBeInstanceOf(MeasurementEnum::class);
    expect( $conversion->unit_two )->toBeInstanceOf(MeasurementEnum::class);
    expect( $conversion->quantity_one )->toBeInstanceOf(BigDecimal::class);
    expect( $conversion->quantity_two )->toBeInstanceOf(BigDecimal::class);

});


it('knows if one of the units is other', function () {

    $this->seed(EachMeasurementSeeder::class);

    $each = new stdClass();
    $each->value = 'each';

    //set one is other
    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb
    ])->refresh();

    expect($conversion->containsEach())->toBeTrue();

    //set two is other
    $conversionTwo = CrossConversion::factory()->create([
        'quantity_one' => 1,
        'unit_one' => UsWeight::lb,
        'quantity_two' => 10,
        'unit_two' => $each
    ]);
    $conversionTwo->refresh();

    expect($conversionTwo->containsEach())->toBeTrue();

});


it('knows conversion factors for weight and volume', function () {

    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);

    expect( (float) (string) $conversion->weightToVolumeFactor() )->toBe(.0625);
    expect( (integer) (string) $conversion->volumeToWeightFactor() )->toBe(16);

});


it('knows its conversion type', function () {

    $this->seed(EachMeasurementSeeder::class);
    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);
    $conversionTwo = CrossConversion::factory()->create([
        'quantity_two' => 128,
        'unit_two' => MetricWeight::gram,
        'quantity_one' => 1,
        'unit_one' => UsVolume::cup
    ]);
    $each = new stdClass;
    $each->value = 'each';
    $conversionThree = CrossConversion::factory()->create([
        'quantity_two' => 128,
        'unit_two' => MetricWeight::gram,
        'quantity_one' => 1,
        'unit_one' => $each
    ])->refresh();

    expect( $conversion->conversionType() )->toBe(['weight', 'volume']);
    expect( $conversionTwo->conversionType() )->toBe(['volume', 'weight']);
    expect( $conversionThree->conversionType() )->toBe(['each', 'weight']);

});


it('knows that it can convert a type', function () {

    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);

    expect( $conversion->canConvert(['weight', 'volume']) )->toBeTrue();
    expect( $conversion->canConvert(['volume', 'weight']) )->toBeTrue();

});


it('knows it can not convert a type', function () {

    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);

    expect( $conversion->canConvert(['each', 'volume']) )->toBeFalse();
    expect( $conversion->canConvert(['each', 'weight']) )->toBeFalse();

});


it('knows that measurement values must match for each to each conversions.', function () {

    $this->seed(EachMeasurementSeeder::class);

    $each = new stdClass;
    $each->value = 'each';
    $bunch = new stdClass;
    $bunch->value = 'bunch';

    //set one is other
    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => $bunch
    ])->refresh();

    expect( $conversion->canConvertEachToEach() )->toBeTrue(); // still needed without parameters?
    expect( $conversion->canConvertEachToEach('each', 'bunch') )->toBeTrue();
    expect( $conversion->canConvertEachToEach('bunch', 'each') )->toBeTrue();
    expect( $conversion->canConvertEachToEach('other', 'each') )->toBeFalse();
    expect( $conversion->canConvertEachToEach('other', 'bunch') )->toBeFalse();

});
