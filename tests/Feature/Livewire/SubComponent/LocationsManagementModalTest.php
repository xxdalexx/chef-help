<?php

use App\Http\Livewire\SubComponent\LocationsManagementModal;
use App\Models\Location;

it('validates input', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(LocationsManagementModal::class)
        ->set($parameter, $value)
        ->call('createLocation')
        ->assertHasErrors([$parameter => $violation]);

})->with([
    ['nameInput']
]);


it('deletes a location', function () {

    $location = Location::factory()->create();

    Livewire::test(LocationsManagementModal::class)
        ->call('deleteLocation', $location->id);

    expect(Location::count())->toBe(0);

});


it('creates a location', function () {

    Livewire::test(LocationsManagementModal::class)
        ->set('nameInput', 'string')
        ->call('createLocation')
        ->assertHasNoErrors();

    expect(Location::count())->toBeOne();
    expect(Location::first()->name)->toBe('string');
});
