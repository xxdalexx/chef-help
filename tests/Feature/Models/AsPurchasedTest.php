<?php

use App\Measurements\MeasurementEnum;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\ValueObjects\ConvertableUnit;
use Database\Seeders\LobsterDishSeeder;

test('relations and casts', function () {

    $ap = AsPurchased::factory()->create();

    expect($ap->ingredient)->toBeInstanceOf(Ingredient::class);
    expect($ap->unit)->toBeInstanceOf(MeasurementEnum::class);
    expect($ap->price)->toBeMoney();

});

it('has a convertable unit value object', function () {

    $ap = AsPurchased::factory()->create();

    expect($ap->getConvertableUnit())->toBeInstanceOf(ConvertableUnit::class);

});

it('gives a price per base unit', function () {

    $this->seed(LobsterDishSeeder::class);
    $ap = AsPurchased::first();

    $costPerUnit = moneyToString($ap->getCostPerBaseUnit());

    expect($costPerUnit)->toBe('$0.75');

});
