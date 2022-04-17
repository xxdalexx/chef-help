<?php

namespace App\Http\Livewire\SubComponent;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Livewire\Component;

class AddIngredient extends Component
{
    public Recipe $recipe;

    public string $ingredientInput = '';

    public string $unitInput = 'oz';

    public string $unitQuantityInput = '';

    public string $cleanedInput = '';

    public string $cookedInput = '';

    protected array $rules = [
        'ingredientInput'   => 'required|exists:ingredients,id',
        'unitInput'         => 'required',
        'unitQuantityInput' => 'required|numeric',
        'cleanedInput'      => 'boolean',
        'cookedInput'       => 'boolean'
    ];

    public function mount()
    {
        $this->ingredientInput = Ingredient::first()->id;
    }

    public function addIngredient()
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
        $this->emit('refreshRecipeShow');
        $this->resetInputs();
    }

    protected function resetInputs()
    {
        $this->ingredientInput   = Ingredient::first()->id;
        $this->unitQuantityInput = '';
        $this->unitInput         = 'oz';
        $this->cookedInput       = '';
        $this->cleanedInput      = '';
    }

    public function render()
    {
        return view('livewire.sub-component.add-ingredient');
    }
}
