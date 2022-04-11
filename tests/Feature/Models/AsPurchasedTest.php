<?php

use App\Measurements\MeasurementEnum;
use App\Models\AsPurchased;
use App\Models\Ingredient;

test('relations and casts', function () {

    $ap = AsPurchased::factory()->make();

    expect($ap->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($ap->unit)->toBeInstanceOf(MeasurementEnum::class);
    expect($ap->price)->toBeMoney();

});
