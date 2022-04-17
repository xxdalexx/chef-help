<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithSearch;
use App\Models\Ingredient;
use Livewire\WithPagination;

class IngredientIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch;

    public bool $showCreateForm = false;

    public string $nameInput = '';

    public string $cleanedInput = '100';

    public string $cookedInput = '100';

    protected array $rules = [
        'nameInput'    => 'required',
        'cleanedInput' => 'required|numeric|between:1,100',
        'cookedInput'  => 'required|numeric|between:1,100',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createIngredient(): void
    {
        $this->validate();

        Ingredient::create([
            'name' => $this->nameInput,
            'cooked_yield' => $this->cookedInput,
            'cleaned_yield' => $this->cleanedInput
        ]);

        $this->setSearch($this->nameInput);
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->nameInput = '';
        $this->cleanedInput = '100';
        $this->cookedInput = '100';
    }

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)->orderBy('name')->paginate(10);
        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
