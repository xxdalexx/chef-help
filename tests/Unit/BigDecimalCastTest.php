<?php

use App\Casts\BigDecimalCast;
use App\Models\AsPurchased;
use Brick\Math\BigDecimal;

test('it can be casted to in a model', function () {
    $cast = new BigDecimalCast();
    $casted = $cast->get(new AsPurchased(), 'unit', '10.00', []);

    expect($casted)->toBeInstanceOf(BigDecimal::class);
});


test('it can be casted from in a model', function () {
    $bigDecimal = BigDecimal::of(10);
    $cast = new BigDecimalCast();
    $casted = $cast->set(new AsPurchased(), 'unit', $bigDecimal, []);

    expect($casted)->toBeString();
});
