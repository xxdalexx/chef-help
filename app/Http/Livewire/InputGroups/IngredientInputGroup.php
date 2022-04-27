<?php

namespace App\Http\Livewire\InputGroups;

use Illuminate\Validation\Rule;

trait IngredientInputGroup
{
    public string $nameInput = '';

    public string $cleanedInput = '100';

    public string $cookedInput = '100';

    public string $apQuantityInput = '1';

    public string $apUnitInput = 'oz';

    public string $apPriceInput = '';

    public function rules(): array
    {
        return [
            'nameInput'       => 'required',
            'cleanedInput'    => 'required|numeric|between:1,100',
            'cookedInput'     => 'required|numeric|between:1,100',

            'apQuantityInput' => ['numeric', Rule::requiredIf($this->createAsPurchased)],
            'apUnitInput'     => [Rule::requiredIf($this->createAsPurchased)],
            'apPriceInput'    => ['numeric', Rule::requiredIf($this->createAsPurchased)],
        ];
    }

    public function resetInputs(): void
    {
        $this->nameInput    = '';
        $this->cleanedInput = '100';
        $this->cookedInput  = '100';
        $this->apQuantityInput = '1';
        $this->apUnitInput = 'oz';
        $this->apPriceInput = '';
    }
}
