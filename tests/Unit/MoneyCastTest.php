<?php

use App\Casts\MoneyCast;
use App\Models\AsPurchased;

test('it can be casted to in a model', function () {
    $cast = new MoneyCast();
    $casted = $cast->get(new AsPurchased(), 'unit', '10.00', []);
    expect($casted)->toBeMoney();
});

test('it can be casted from in a model', function () {
    $money = money(10);
    $cast = new MoneyCast();
    $casted = $cast->set(new AsPurchased(), 'unit', $money, []);
    expect($casted)->toBeString();
});
