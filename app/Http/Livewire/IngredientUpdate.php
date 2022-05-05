<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use Livewire\Component;

class IngredientUpdate extends Component
{
    public Ingredient $ingredient;

    public string $nameInput = '';

    public string $cleanedYieldInput = '';

    public string $cookedYieldInput = '';

    protected function rules(): array
    {
        return [
            'nameInput'         => 'required',
            'cleanedYieldInput' => 'required|numeric|between:1,100',
            'cookedYieldInput'  => 'required|numeric|between:1,100',
        ];
    }

    public function mount(): void
    {
        $this->nameInput         = $this->ingredient->name;
        $this->cleanedYieldInput = $this->ingredient->cleaned_yield;
        $this->cookedYieldInput  = $this->ingredient->cooked_yield;
    }

    public function update(): void
    {
        $this->validate();

        $this->ingredient->update([
            'name'          => $this->nameInput,
            'cooked_yield'  => $this->cookedYieldInput,
            'cleaned_yield' => $this->cleanedYieldInput
        ]);

        $this->emitUp('refresh');
    }

    public function render()
    {
        return view('livewire.ingredient-update');
    }
}
