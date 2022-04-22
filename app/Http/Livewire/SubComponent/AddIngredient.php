<?php

namespace App\Http\Livewire\SubComponent;

use App\Http\Livewire\LivewireBaseComponent;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;

class AddIngredient extends LivewireBaseComponent
{
    public Recipe $recipe;

    public int $showingExistingIngredient = 1;

    /*
    |--------------------------------------------------------------------------
    | Existing Ingredient Inputs
    |--------------------------------------------------------------------------
    */

    public string $ingredientInput   = '';
    public string $unitInput         = 'oz';
    public string $unitQuantityInput = '';
    public string $cleanedInput      = '';
    public string $cookedInput       = '';

    protected array $rules = [
        'ingredientInput'   => 'required|exists:ingredients,id',
        'unitInput'         => 'required',
        'unitQuantityInput' => 'required|numeric',
        'cleanedInput'      => 'boolean',
        'cookedInput'       => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | New Ingredient Inputs
    |--------------------------------------------------------------------------
    */

    public string $nameInput          = '';
    public string $cleanedYieldInput = '100';
    public string $cookedYieldInput  = '100';
    public string $apQuantityInput    = '1';
    public string $apUnitInput        = 'oz';
    public string $apPriceInput       = '';

    protected array $rulesForNew = [
        'unitInput'         => 'required',
        'unitQuantityInput' => 'required|numeric',
        'cleanedInput'      => 'boolean',
        'cookedInput'       => 'boolean',
        //
        'nameInput'          => 'required',
        'cleanedYieldInput' => 'required|numeric|between:1,100',
        'cookedYieldInput'  => 'required|numeric|between:1,100',
        'apQuantityInput'    => 'required|numeric',
        'apUnitInput'        => 'required',
        'apPriceInput'       => 'required|numeric',
    ];

    public function updated($property)
    {
        $rules = collect($this->rules)->merge($this->rulesForNew)->toArray();
        $this->validateOnly($property, $rules);
    }

    public function mount()
    {
        $this->ingredientInput = Ingredient::first()->id ?? 0;
    }

    public function addIngredient()
    {
        if ($this->showingExistingIngredient) {
            $this->fromExisting();
        } else {
            $this->createNew();
        }

        $this->emit('refreshRecipeShow');
        $this->alertWithToast('Ingredient Added.');
        $this->resetInputs();
    }

    protected function fromExisting(): void
    {
        $this->validate();

        $recipeItem = RecipeItem::make([
            'ingredient_id' => $this->ingredientInput,
            'quantity'      => $this->unitQuantityInput,
            'unit'          => findMeasurementUnitEnum($this->unitInput),
            'cooked'        => (bool)$this->cookedInput,
            'cleaned'       => (bool)$this->cleanedInput
        ]);

        $this->recipe->items()->save($recipeItem);
    }

    protected function createNew(): void
    {
        $this->validate($this->rulesForNew);

        $ingredient = Ingredient::create([
            'name' => $this->nameInput,
            'cleaned_yield' => $this->cleanedYieldInput,
            'cooked_yield' => $this->cookedYieldInput
        ]);

        $ingredient->asPurchased()->save(
            AsPurchased::make([
                'quantity' => $this->apQuantityInput,
                'unit' => findMeasurementUnitEnum($this->apUnitInput),
                'price' => money($this->apPriceInput)
            ])
        );

        $this->recipe->items()->save(
            RecipeItem::make([
                'quantity' => $this->unitQuantityInput,
                'unit' => findMeasurementUnitEnum($this->unitInput),
                'cleaned' => (bool) $this->cleanedInput,
                'cooked' => (bool) $this->cookedInput,
                'ingredient_id' => $ingredient->id
            ])
        );
    }

    protected function resetInputs()
    {
        $this->ingredientInput    = Ingredient::first()->id ?? 0;
        $this->unitQuantityInput  = '';
        $this->unitInput          = 'oz';
        $this->cookedInput        = '';
        $this->cleanedInput       = '';
        $this->nameInput          = '';
        $this->cleanedForNewInput = '100';
        $this->cookedForNewInput  = '100';
        $this->apQuantityInput    = '1';
        $this->apUnitInput        = 'oz';
        $this->apPriceInput       = '';
    }

    public function render()
    {
        return view('livewire.sub-component.add-ingredient');
    }
}
