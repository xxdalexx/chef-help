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

    expect( $conversion->canConvertWeightAndVolume() )->toBeTrue();

    $conversionTwo = CrossConversion::factory()->create([
        'quantity_one' => 128,
        'unit_one' => MetricWeight::gram,
        'quantity_two' => 1,
        'unit_two' => UsWeight::oz
    ]);

    expect( $conversionTwo->canConvertWeightAndVolume() )->toBeFalse();

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
