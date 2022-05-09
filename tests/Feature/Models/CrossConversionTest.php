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


it('knows that one item is weight and another is volume', function () {

    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);

    expect( $conversion->canConvertTypes() )->toBeTrue();

    $conversionTwo = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsWeight::oz
    ]);

    expect( $conversionTwo->canConvertTypes() )->toBeFalse();

});


it('knows if one of the units is other', function () {

    $this->seed(\Database\Seeders\OtherMeasurementSeeder::class);

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


it('knows conversion factors', function () {

    $conversion = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsVolume::cup
    ]);

    expect( (float) (string) $conversion->weightToVolumeFactor() )->toBe(.0625);
    expect( (integer) (string) $conversion->volumeToWeightFactor() )->toBe(16);

});
