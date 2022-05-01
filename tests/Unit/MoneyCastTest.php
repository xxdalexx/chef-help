<?php

use App\Casts\MoneyCast;
use App\Models\AsPurchased;

it('can be casted to in a model', function () {

    $cast = new MoneyCast();
    $casted = $cast->get(new AsPurchased(), 'price', '10.00', []);

    expect( $casted )->toBeMoney();

});


it('can be casted from in a model', function () {

    $money = money(10);
    $cast = new MoneyCast();
    $casted = $cast->set(new AsPurchased(), 'price', $money, []);

    expect( $casted )->toBeString();

});
