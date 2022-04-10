<?php

use App\Measurements\UsVolume;
use App\Measurements\UsWeights;

it('returns a UsWeight enum case from a string', function ($from, $expected) {
    $type = get_debug_type($expected);
    $returned = $type::from($from);
    expect($returned === $expected)->toBeTrue();
})->with([
    ['oz', UsWeights::oz],
    ['Ounce', UsWeights::oz],
    ['lb', UsWeights::lb],
    ['LB', UsWeights::lb],
    ['pound', UsWeights::lb],
    ['ton', UsWeights::ton],
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
    [UsWeights::oz, 1],
    [UsWeights::lb, 16],
    [UsWeights::ton, 2000],
    [UsVolume::floz, 1],
    [UsVolume::cup, 8],
    [UsVolume::pint, 16],
    [UsVolume::quart, 32],
    [UsVolume::gallon, 128],
]);
