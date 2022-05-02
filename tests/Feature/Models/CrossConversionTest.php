<?php

use App\Measurements\MeasurementEnum;
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
