<?php

use App\Casts\BigDecimalCast;
use App\Models\AsPurchased;
use Brick\Math\BigDecimal;

it('can be casted to in a model', function () {

    $cast = new BigDecimalCast();
    $casted = $cast->get(new AsPurchased(), 'quantity', '10.00', []);

    expect( $casted )->toBeInstanceOf( BigDecimal::class );

});


it('can be casted from in a model', function () {

    $bigDecimal = BigDecimal::of(10);
    $cast = new BigDecimalCast();
    $casted = $cast->set(new AsPurchased(), 'quantity', $bigDecimal, []);

    expect( $casted )->toBeString();

});
