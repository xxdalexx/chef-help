<?php

it('returns false on failure', function () {

    $rule = new \App\Rules\MeasurementEnumExists();

    expect( $rule->passes('unit', 'non existent') )->toBeFalse();

});


it('returns true on passing', function () {

    $rule = new \App\Rules\MeasurementEnumExists();

    expect( $rule->passes('unit', 'oz') )->toBeTrue();

});
