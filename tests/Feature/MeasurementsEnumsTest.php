<?php

use App\Casts\MeasurementEnumCast;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;

it('returns a UsWeight enum case from a string', function ($from, $expected) {
    $type = get_debug_type($expected);
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
    ['Gallon', UsVolume::gallon]
]);

it('returns a factor used for unit conversions', function ($enum, $factor) {
    expect($enum->conversionFactor())->toBe($factor);
})->with([
    [UsWeight::oz, 1],
    [UsWeight::lb, 16],
    [UsWeight::ton, 2000],
    [UsVolume::floz, 1],
    [UsVolume::cup, 8],
    [UsVolume::pint, 16],
    [UsVolume::quart, 32],
    [UsVolume::gallon, 128],
]);

test('it can be casted to in a model', function ($value, $expected) {
    $cast = new MeasurementEnumCast();
    $casted = $cast->get(new AsPurchased(), 'unit', $value, []);
    expect($casted)->toBe($expected);
})->with([
    ['floz', UsVolume::floz],
    ['oz', UsWeight::oz],
]);

test('it can be casted from in a model', function ($value, $expected) {
    $cast = new MeasurementEnumCast();
    $casted = $cast->set(new AsPurchased(), 'unit', $value, []);
    expect($casted)->toBe($expected);
})->with([
    [UsVolume::floz, 'floz'],
    [UsWeight::oz, 'oz'],
]);
