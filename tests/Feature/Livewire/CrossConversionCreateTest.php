<?php

use App\Http\Livewire\CrossConversionCreate;
use App\Models\CrossConversion;
use App\Models\Ingredient;

it('validates inputs', function ($parameter, $value = '', $violation = 'required') {

    Livewire::test(CrossConversionCreate::class, ['ingredient' => Ingredient::factory()->create()])
        ->set($parameter, $value)
        ->call('create')
        ->assertHasErrors([$parameter => $violation]);

})->with([
//    'quantityOneInput' => 'required|numeric',
//    'unitOneInput' => 'required',
//    'quantityTwoInput' => 'required|numeric',
//    'unitTwoInput' => 'required',
    ['quantityOneInput'],
    ['quantityOneInput', 'not a number', 'numeric'],
    ['unitOneInput'],
    ['quantityTwoInput'],
    ['quantityTwoInput', 'not a number', 'numeric'],
    ['unitTwoInput'],
]);


it('creates a CrossConversion record', function () {

    $ingredient = Ingredient::factory()->create();
    Livewire::test(CrossConversionCreate::class, ['ingredient' => $ingredient])
        ->set('quantityOneInput', '1')
        ->set('unitOneInput', 'oz')
        ->set('quantityTwoInput', '2')
        ->set('unitTwoInput', 'ml')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmitted('alertWithToast');
    $ingredient->refresh()->load('crossConversions');
    /** @var CrossConversion $conversion */
    $conversion = $ingredient->crossConversions->first();

    expect( (string) $conversion->quantity_one )->toBe( '1' );
    expect( $conversion->unit_one )->toBe( \App\Measurements\UsWeight::oz );
    expect( (string) $conversion->quantity_two )->toBe( '2' );
    expect( $conversion->unit_two )->toBe( \App\Measurements\MetricVolume::ml );

});
