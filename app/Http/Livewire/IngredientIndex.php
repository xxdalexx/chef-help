<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithLiveValidation;
use App\Http\Livewire\Plugins\WithSearch;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use Livewire\WithPagination;

class IngredientIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch, WithLiveValidation;

    public bool $showCreateForm = false;

    public bool $createAsPurchase = true;

    public string $nameInput = '';
    public string $cleanedInput = '100';
    public string $cookedInput = '100';
    public string $apQuantityInput = '1';
    public string $apUnitInput = 'oz';
    public string $apPriceInput = '';

    protected array $rules = [
        'nameInput'       => 'required',
        'cleanedInput'    => 'required|numeric|between:1,100',
        'cookedInput'     => 'required|numeric|between:1,100',
    ];

    protected array $asPurchasedRules = [
        'apQuantityInput' => 'required|numeric',
        'apUnitInput'     => 'required',
        'apPriceInput'    => 'required|numeric',
    ];

    protected function validateInputs()
    {
        $this->validate();

        if ($this->createAsPurchase) {
            $this->validate($this->asPurchasedRules);
        }
    }

    public function createIngredient(): void
    {
        $this->validateInputs();

        $ingredient = Ingredient::create([
            'name'          => $this->nameInput,
            'cooked_yield'  => $this->cookedInput,
            'cleaned_yield' => $this->cleanedInput
        ]);

        if ($this->createAsPurchase) {
            $this->createAsPurchasedForIngredient($ingredient);
        }

        $this->setSearch($this->nameInput);
        $this->resetInputs();
    }

    protected function createAsPurchasedForIngredient(Ingredient $ingredient)
    {
        $asPurchased = AsPurchased::create([
            'quantity' => $this->apQuantityInput,
            'unit' => findMeasurementUnitEnum($this->apUnitInput),
            'price' => money($this->apPriceInput),
            'ingredient_id' => $ingredient->id
        ]);
    }

    public function resetInputs()
    {
        $this->nameInput    = '';
        $this->cleanedInput = '100';
        $this->cookedInput  = '100';
        $this->apQuantityInput = '1';
        $this->apUnitInput = 'oz';
        $this->apPriceInput = '';
    }

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)->orderBy('name')->paginate(10);
        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
