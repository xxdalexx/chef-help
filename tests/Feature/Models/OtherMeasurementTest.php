<?php

use App\Models\EachMeasurement;
use Database\Seeders\OtherMeasurementSeeder;

beforeEach()->seed(OtherMeasurementSeeder::class);

it('returns a model instance for OtherMeasurement', function () {

    $fromString = EachMeasurement::fromString('each');
    $expected = EachMeasurement::whereName('each')->first();

    expect($fromString->is($expected))->toBeTrue();

});


it('shows in select component', function () {

    $this->blade('<x-form.select-units/>')
        ->assertSee('each');

});


it('has a value', function () {

    $each = EachMeasurement::first();
    expect($each->value)->toBe('each');

});


it('can be found using helper method', function () {

    $result = findMeasurementUnitEnum('each');
    expect($result)->toBeInstanceOf(EachMeasurement::class);
    expect($result->value)->toBe('each');

});
