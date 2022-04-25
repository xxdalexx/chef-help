<?php

use App\Models\Ingredient;
use App\Models\Location;

test('Relationships', function () {

    $location = Location::factory()->has(Ingredient::factory()->count(2))->create()->refresh();

    expect($location->ingredients)->toBeCollection();
    expect($location->ingredients->first())->toBeInstanceOf(Ingredient::class);

});
