<?php

use App\Models\OtherMeasurement;
use Database\Seeders\OtherMeasurementSeeder;

beforeEach()->seed(OtherMeasurementSeeder::class);

it('returns a model instance for OtherMeasurement', function () {

    $fromString = OtherMeasurement::fromString('each');
    $expected = OtherMeasurement::whereName('each')->first();

    expect($fromString->is($expected))->toBeTrue();

});


it('shows in select component', function () {

    $this->blade('<x-form.select-units/>')
        ->assertSee('each');

});


it('has a value', function () {

    $each = OtherMeasurement::first();
    expect($each->value)->toBe('each');

});


it('can be found using helper method', function () {

    $result = findMeasurementUnitEnum('each');
    expect($result)->toBeInstanceOf(OtherMeasurement::class);
    expect($result->value)->toBe('each');

});
