<?php

namespace App\Http\Livewire;

use App\Http\Livewire\InputGroups\IngredientInputGroup;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IngredientCreate extends Component
{
    use IngredientInputGroup;

    public bool $createAsPurchased = true;

    public function create()//: Livewire\Redirector
    {
        $this->validate();

        $ingredient = Ingredient::create([
            'name'          => $this->nameInput,
            'cooked_yield'  => $this->cookedInput,
            'cleaned_yield' => $this->cleanedInput
        ]);

        if ($this->createAsPurchased) {
            $this->createAsPurchasedForIngredient($ingredient);
        }

        return redirect()->route('ingredient.show', $ingredient);
    }

    protected function createAsPurchasedForIngredient(Ingredient $ingredient)
    {
        AsPurchased::create([
            'quantity' => $this->apQuantityInput,
            'unit' => findMeasurementUnitEnum($this->apUnitInput),
            'price' => money($this->apPriceInput),
            'ingredient_id' => $ingredient->id
        ]);
    }

    public function render(): View
    {
        return view('livewire.ingredient-create');
    }
}
