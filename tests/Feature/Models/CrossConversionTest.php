<?php

use App\Measurements\MeasurementEnum;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\CrossConversion;
use App\Models\Ingredient;
use Brick\Math\BigDecimal;

test('relationships and casts', function () {

    $conversion = CrossConversion::factory()->create();

    expect( $conversion->ingredient )->toBeInstanceOf(Ingredient::class);
    expect( $conversion->unit_one )->toBeInstanceOf(MeasurementEnum::class);
    expect( $conversion->unit_two )->toBeInstanceOf(MeasurementEnum::class);
    expect( $conversion->quantity_one )->toBeInstanceOf(BigDecimal::class);
    expect( $conversion->quantity_two )->toBeInstanceOf(BigDecimal::class);

});


it('knows if one of the units is other', function () {

    $this->seed(\Database\Seeders\EachMeasurementSeeder::class);

    $each = new stdClass();
    $each->value = 'each';

    //set one is other
    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 10,
        'unit_one' => $each,
        'quantity_two' => 1,
        'unit_two' => UsWeight::lb
    ]);
    $conversion->refresh();

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

    expect( $conversion->conversionType() )->toBe(['weight', 'volume']);
    expect( $conversionTwo->conversionType() )->toBe(['volume', 'weight']);

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
