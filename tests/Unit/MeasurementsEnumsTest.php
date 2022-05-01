<?php

use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;

it('returns a UsWeight enum case from a string', function ($from, $expected) {

    /** @var MeasurementEnum $type */
    $type     = get_class($expected);
    $returned = $type::fromString($from);

    expect($returned === $expected)->toBeTrue();

})->with([
    ['oz', UsWeight::oz],
    ['Ounce', UsWeight::oz],
    ['lb', UsWeight::lb],
    ['LB', UsWeight::lb],
    ['pound', UsWeight::lb],
    ['ton', UsWeight::ton],
    ['fl oz', UsVolume::floz],
    ['fluid oz', UsVolume::floz],
    ['cup', UsVolume::cup],
    ['PINT', UsVolume::pint],
    ['quart', UsVolume::quart],
    ['gal', UsVolume::gallon],
    ['pt', UsVolume::pint],
    ['qt', UsVolume::quart],
    ['Gallon', UsVolume::gallon],
    ['g', MetricWeight::gram],
    ['kilogram', MetricWeight::kg],
    ['l', MetricVolume::liter],
    ['ml', MetricVolume::ml]
]);


it('returns a factor used for unit conversions', function ($enum, $factor) {

    expect( (string) $enum->conversionFactor() )->toBe( $factor );

})->with([
    [UsWeight::oz, '1'],
    [UsWeight::lb, '16'],
    [UsWeight::ton, '2000'],
    [UsVolume::floz, '1'],
    [UsVolume::cup, '8'],
    [UsVolume::pint, '16'],
    [UsVolume::quart, '32'],
    [UsVolume::gallon, '128'],
    [MetricWeight::kg, '1000'],
    [MetricWeight::gram, '1'],
    [MetricVolume::ml, '0.001'],
    [MetricVolume::liter, '1']
]);


test('it can be casted to in a model', function ($value, $expected) {

    $cast = new MeasurementEnumCast();
    $casted = $cast->get(new AsPurchased(), 'unit', $value, []);

    expect( $casted )->toBe( $expected );

})->with([
    ['floz', UsVolume::floz],
    ['oz', UsWeight::oz],
    ['gram', MetricWeight::gram],
    ['liter', MetricVolume::liter]
]);


test('it can be casted from in a model', function ($value, $expected) {

    $cast = new MeasurementEnumCast();
    $casted = $cast->set(new AsPurchased(), 'unit', $value, []);

    expect( $casted )->toBe( $expected );

})->with([
    [UsVolume::floz, 'floz'],
    [UsWeight::oz, 'oz'],
    [MetricWeight::gram, 'gram'],
    [MetricVolume::ml, 'ml']
]);


test('it returns a type', function (MeasurementEnum $unit, $expected) {

    expect( $unit->getType() )->toBe( $expected );

})->with([
    [UsWeight::oz, 'weight'],
    [UsVolume::floz, 'volume'],
    [MetricWeight::gram, 'weight'],
    [MetricVolume::ml, 'volume']
]);


test('it returns a system', function (MeasurementEnum $unit, $expected) {

    expect( $unit->getSystem() )->toBe ($expected );

})->with([
    [UsWeight::oz, 'us'],
    [UsVolume::floz, 'us'],
    [MetricWeight::gram, 'metric'],
    [MetricVolume::ml, 'metric']
]);
