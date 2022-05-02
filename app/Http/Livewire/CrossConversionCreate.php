<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\Refreshable;
use App\Models\CrossConversion;
use App\Models\Ingredient;

class CrossConversionCreate extends LivewireBaseComponent
{
    use Refreshable;

    public Ingredient $ingredient;

    public string $quantityOneInput = '';

    public string $unitOneInput = 'oz';

    public string $quantityTwoInput = '';

    public string $unitTwoInput = 'oz';

    protected function rules(): array
    {
        return [
            'quantityOneInput' => 'required|numeric',
            'unitOneInput' => 'required',
            'quantityTwoInput' => 'required|numeric',
            'unitTwoInput' => 'required',
        ];
    }

    public function create(): void
    {
        $this->validate();

        CrossConversion::createWithoutCasting([
            'ingredient_id' => $this->ingredient->id,
            'quantity_one' => $this->quantityOneInput,
            'unit_one' => $this->unitOneInput,
            'quantity_two' => $this->quantityTwoInput,
            'unit_two' => $this->unitTwoInput
        ]);

        $this->ingredient->refresh();

        $this->alertWithToast('Created.');
        $this->emitUp('refresh');
    }

    public function resetInputs(): void
    {
        $this->quantityOneInput = '';
        $this->unitOneInput = '';
        $this->quantityTwoInput = '';
        $this->unitTwoInput = '';
    }

    public function render()
    {
        return view('livewire.cross-conversion-create');
    }
}
