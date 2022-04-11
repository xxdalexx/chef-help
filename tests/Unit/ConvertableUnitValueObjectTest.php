<?php

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\ValueObjects\ConvertableUnit;

it('returns a new object converted to the base unit with US weights', function () {

    $unit = new ConvertableUnit(UsWeight::lb, 1);
    $converted = $unit->convertToBase();

    expect($converted->getQuantityAsString())->toBe('16');
    expect($converted->getUnit())->toBe(UsWeight::oz);

});

//Also tests that the quantity parameter is optional.
it('returns a new object converted to the base unit with US volume', function () {

    $unit = new ConvertableUnit(UsVolume::gallon);
    $converted = $unit->convertToBase();

    expect($converted->getQuantityAsString())->toBe('128');
    expect($converted->getUnit())->toBe(UsVolume::floz);

});

it('returns a new converted object with weights', function () {

    $unit = new ConvertableUnit(UsWeight::lb, 1);
    $converted = $unit->convertTo(UsWeight::oz);

    expect($converted->getQuantityAsString())->toBe('16');
    expect($converted->getUnit())->toBe(UsWeight::oz);

});

it('returns a new converted object with volume', function () {

    $unit = new ConvertableUnit(UsVolume::pint, 1);
    $converted = $unit->convertTo(UsVolume::cup);

    expect($converted->getQuantityAsString())->toBe('2');
    expect($converted->getUnit())->toBe(UsVolume::cup);

});

it('converts to decimals', function () {

    $unit = new ConvertableUnit(UsVolume::cup, 1);
    $converted = $unit->convertTo(UsVolume::pint);

    expect($converted->getQuantityAsString())->toBe('0.5');
    expect($converted->getUnit())->toBe(UsVolume::pint);

});


